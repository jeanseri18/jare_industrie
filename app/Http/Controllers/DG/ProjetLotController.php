<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\BienImmobilier;
use App\Models\Projet;
use App\Models\ProjetIlot;
use App\Models\ProjetLot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjetLotController extends Controller
{
    public function index(Projet $projet)
    {
        // Source de vérité pour la superficie de référence : `projet_ilots` (une ligne par îlot).
        // L’ancre sur `projet_lots` seule pouvait mélanger les valeurs ou masquer les îlots sans lots.
        $lotAgg = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->select('ilot')
            ->selectRaw('COUNT(*) as nb_lots')
            ->selectRaw("MIN(CASE WHEN lot REGEXP '^[0-9]+$' THEN CAST(lot AS UNSIGNED) END) as lot_debut")
            ->selectRaw("MAX(CASE WHEN lot REGEXP '^[0-9]+$' THEN CAST(lot AS UNSIGNED) END) as lot_fin")
            ->groupBy('ilot');

        $attribParIlot = ProjetLot::query()
            ->where('projet_lots.id_projet', $projet->id)
            ->join('attribution_lots as al', 'al.projet_lot_id', '=', 'projet_lots.id')
            ->select('projet_lots.ilot')
            ->selectRaw('COUNT(al.id) as nb_attribues')
            ->groupBy('projet_lots.ilot');

        $ilots = ProjetIlot::query()
            ->where('projet_ilots.id_projet', $projet->id)
            ->leftJoinSub($lotAgg, 'lot_agg', function ($join) {
                $join->on('lot_agg.ilot', '=', 'projet_ilots.ilot');
            })
            ->leftJoinSub($attribParIlot, 'attrib', function ($join) {
                $join->on('attrib.ilot', '=', 'projet_ilots.ilot');
            })
            ->select([
                'projet_ilots.ilot',
                DB::raw('COALESCE(lot_agg.nb_lots, 0) as nb_lots'),
                DB::raw('projet_ilots.superficie_totale as superficie_total'),
                DB::raw('lot_agg.lot_debut'),
                DB::raw('lot_agg.lot_fin'),
                DB::raw('COALESCE(attrib.nb_attribues, 0) as nb_attribues'),
            ])
            ->orderBy('projet_ilots.ilot')
            ->paginate(config('pagination.per_page'))->withQueryString();

        $totalSuperficieReference = (float) ProjetIlot::query()
            ->where('id_projet', $projet->id)
            ->sum('superficie_totale');

        return view('dg.projets.lots.index', compact('projet', 'ilots', 'totalSuperficieReference'));
    }

    /**
     * Détail des lots pour un îlot donné.
     */
    public function showIlotLots(Projet $projet, string $ilot)
    {
        $lots = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->with(['attributionLot', 'bienImmobilier'])
            ->orderByRaw("CASE WHEN lot REGEXP '^[0-9]+$' THEN CAST(lot AS UNSIGNED) ELSE 999999 END")
            ->orderBy('lot')
            ->get();

        $projetIlot = ProjetIlot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->first();

        if ($lots->isEmpty() && ! $projetIlot) {
            abort(404);
        }

        $biensProjet = $projet->bien_immobiliers()->orderBy('titre')->get();

        return view('dg.projets.lots.ilot_lots', compact('projet', 'ilot', 'lots', 'projetIlot', 'biensProjet'));
    }

    public function storeLotManuel(Request $request, Projet $projet, string $ilot)
    {
        $data = $request->validate([
            'lot' => [
                'required',
                'string',
                'max:50',
                Rule::unique('projet_lots', 'lot')->where(fn ($q) => $q->where('id_projet', $projet->id)),
            ],
            'superficie' => ['nullable', 'numeric', 'min:0'],
        ], [
            'lot.unique' => 'Ce numéro de lot existe déjà pour ce projet (même sur un autre îlot).',
        ]);

        if ($request->filled('superficie')) {
            $redirect = $this->validerSommeSuperficiesVsReference(
                $projet,
                $ilot,
                (float) $data['superficie'],
                null
            );
            if ($redirect !== null) {
                return $redirect;
            }
        }

        $projet->projetLots()->create([
            'ilot' => $ilot,
            'lot' => $data['lot'],
            'type_logement' => null,
            'bien_immobilier_id' => null,
            'numero_page_guide' => null,
            'superficie' => $request->filled('superficie') ? $request->input('superficie') : null,
        ]);

        return redirect()
            ->route('dg.projets.lots.ilot.lots', [$projet, $ilot])
            ->with('success', 'Lot ajouté à l’îlot.');
    }

    public function updateLotSuperficie(Request $request, Projet $projet, ProjetLot $projet_lot)
    {
        if ((int) $projet_lot->id_projet !== (int) $projet->id) {
            abort(404);
        }

        $request->validate([
            'superficie' => ['nullable', 'numeric', 'min:0'],
        ]);

        $nouvelle = $request->filled('superficie') ? (float) $request->input('superficie') : 0.0;
        $redirect = $this->validerSommeSuperficiesVsReference(
            $projet,
            $projet_lot->ilot,
            $nouvelle,
            (int) $projet_lot->id
        );
        if ($redirect !== null) {
            return $redirect;
        }

        $projet_lot->update([
            'superficie' => $request->filled('superficie') ? $request->input('superficie') : null,
        ]);

        return redirect()
            ->route('dg.projets.lots.ilot.lots', [$projet, $projet_lot->ilot])
            ->with('success', 'Superficie du lot mise à jour.');
    }

    /**
     * Applique la même superficie à plusieurs lots de l’îlot (respect de la référence totale îlot).
     */
    public function updateSuperficieMasse(Request $request, Projet $projet, string $ilot)
    {
        $validated = $request->validate([
            'lot_ids' => ['required', 'array', 'min:1'],
            'lot_ids.*' => ['integer', 'exists:projet_lots,id'],
            'superficie_masse' => ['required', 'numeric', 'min:0'],
        ], [
            'lot_ids.required' => 'Cochez au moins un lot.',
            'lot_ids.min' => 'Cochez au moins un lot.',
            'superficie_masse.required' => 'Indiquez la superficie à appliquer.',
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['lot_ids'])));

        $lots = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->whereIn('id', $ids)
            ->get();

        if ($lots->count() !== count($ids)) {
            return back()
                ->withInput()
                ->withErrors([
                    'lot_ids' => 'Un ou plusieurs lots ne correspondent pas à cet îlot.',
                ])
                ->with('invalid_superficie_bulk', true);
        }

        $S = (float) $validated['superficie_masse'];
        $sommeAvant = $this->sommeSuperficiesLotsIlot($projet, $ilot);
        $sumSelectedOld = $lots->sum(fn ($l) => (float) ($l->superficie ?? 0));
        $n = $lots->count();
        $sommeApres = $sommeAvant - $sumSelectedOld + ($n * $S);

        $ref = $this->superficieReferenceIlot($projet, $ilot);
        if ($ref !== null && $sommeApres > $ref + 0.01) {
            return back()
                ->withInput()
                ->withErrors([
                    'superficie_masse' => sprintf(
                        'La somme des superficies ne peut pas dépasser la référence îlot (%s m²). Avec %s m² sur %d lot(s), le total serait de %s m².',
                        number_format($ref, 2, ',', ' '),
                        number_format($S, 2, ',', ' '),
                        $n,
                        number_format($sommeApres, 2, ',', ' ')
                    ),
                ])
                ->with('invalid_superficie_bulk', true);
        }

        foreach ($lots as $lot) {
            $lot->update(['superficie' => $S]);
        }

        return redirect()
            ->route('dg.projets.lots.ilot.lots', [$projet, $ilot])
            ->with('success', sprintf(
                'Superficie de %s m² appliquée à %d lot(s).',
                number_format($S, 2, ',', ' '),
                $n
            ));
    }

    /**
     * Associe le même bien immobilier à plusieurs lots de l’îlot (ou retire le lien).
     */
    public function updateBienImmobilierMasse(Request $request, Projet $projet, string $ilot)
    {
        $validated = $request->validate([
            'lot_ids' => ['required', 'array', 'min:1'],
            'lot_ids.*' => ['integer', 'exists:projet_lots,id'],
            'bien_immobilier_id' => [
                'nullable',
                'integer',
                Rule::exists('bien_immobiliers', 'id')->where(fn ($q) => $q->where('idprojet', $projet->id)),
            ],
        ], [
            'lot_ids.required' => 'Cochez au moins un lot.',
            'lot_ids.min' => 'Cochez au moins un lot.',
            'bien_immobilier_id.exists' => 'Ce bien immobilier n’appartient pas à ce projet.',
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['lot_ids'])));

        $lots = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->whereIn('id', $ids)
            ->get();

        if ($lots->count() !== count($ids)) {
            return back()
                ->withInput()
                ->withErrors([
                    'lot_ids' => 'Un ou plusieurs lots ne correspondent pas à cet îlot.',
                ])
                ->with('invalid_bien_bulk', true);
        }

        $bienId = $validated['bien_immobilier_id'] ?? null;

        foreach ($lots as $lot) {
            $lot->update(['bien_immobilier_id' => $bienId]);
        }

        $msg = $bienId === null
            ? sprintf('Lien bien immobilier retiré pour %d lot(s).', $lots->count())
            : sprintf(
                'Bien « %s » associé à %d lot(s).',
                BienImmobilier::whereKey($bienId)->value('titre') ?? '—',
                $lots->count()
            );

        return redirect()
            ->route('dg.projets.lots.ilot.lots', [$projet, $ilot])
            ->with('success', $msg);
    }

    public function updateLotBienImmobilier(Request $request, Projet $projet, ProjetLot $projet_lot)
    {
        if ((int) $projet_lot->id_projet !== (int) $projet->id) {
            abort(404);
        }

        $validated = $request->validate([
            'bien_immobilier_id' => [
                'nullable',
                'integer',
                Rule::exists('bien_immobiliers', 'id')->where(fn ($q) => $q->where('idprojet', $projet->id)),
            ],
        ], [
            'bien_immobilier_id.exists' => 'Ce bien immobilier n’appartient pas à ce projet.',
        ]);

        $projet_lot->update(['bien_immobilier_id' => $validated['bien_immobilier_id'] ?? null]);

        return redirect()
            ->route('dg.projets.lots.ilot.lots', [$projet, $projet_lot->ilot])
            ->with('success', 'Bien immobilier du lot mis à jour.');
    }

    public function store(Request $request, Projet $projet)
    {
        $data = $request->validate([
            'ilot' => ['required', 'string', 'max:50'],
            'lot_debut' => ['required', 'integer', 'min:1'],
            'nombre_lots' => ['required', 'integer', 'min:1', 'max:500'],
            'superficie_total_ilot' => ['required', 'numeric', 'min:0'],
        ], [
            'superficie_total_ilot.required' => 'La superficie totale de l’îlot est obligatoire.',
            'nombre_lots.max' => 'Maximum 500 lots par génération.',
        ]);

        $ilotNom = trim($data['ilot']);

        $lotDebut = (int) $data['lot_debut'];
        $nombreLots = (int) $data['nombre_lots'];
        $lotFin = $lotDebut + $nombreLots - 1;

        [$cree, $ignores] = $this->creerLotsPourIlot(
            $projet,
            $ilotNom,
            $lotDebut,
            $lotFin,
            (float) $data['superficie_total_ilot']
        );

        if ($cree === 0) {
            return redirect()->route('dg.projets.lots.index', $projet)
                ->with('error', "Aucun lot créé : les numéros {$lotDebut} à {$lotFin} existent déjà pour ce projet (chaque numéro de lot est unique pour tout le projet, quel que soit l’îlot).");
        }

        $msg = "{$cree} lot(s) créé(s) pour l’îlot « {$ilotNom} » — à partir du N° {$lotDebut} ({$nombreLots} position(s) demandée(s), jusqu’au N° {$lotFin})";
        if ($ignores > 0) {
            $msg .= ". {$ignores} numéro(s) déjà utilisé(s) sur ce projet, ignoré(s).";
        }

        return redirect()->route('dg.projets.lots.index', $projet)->with('success', $msg);
    }

    public function updateIlot(Request $request, Projet $projet, string $ilot)
    {
        $data = $request->validate([
            'ilot' => ['required', 'string', 'max:50'],
            'lot_debut' => ['required', 'integer', 'min:1'],
            'lot_fin' => ['required', 'integer', 'min:1'],
            'superficie_total_ilot' => ['required', 'numeric', 'min:0'],
        ]);

        if ($data['lot_fin'] < $data['lot_debut']) {
            return back()->withErrors(['lot_fin' => 'Le numéro de fin doit être ≥ au début.'])->withInput();
        }

        $lotsQuery = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot);

        if (! $lotsQuery->exists()) {
            abort(404);
        }

        $nbAttribues = (int) ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->whereHas('attributionLot')
            ->count();

        $ilotNouveau = $data['ilot'];

        $lotsNumeriques = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->whereRaw("lot REGEXP '^[0-9]+$'")
            ->selectRaw('MIN(CAST(lot AS UNSIGNED)) as min_lot, MAX(CAST(lot AS UNSIGNED)) as max_lot, COUNT(*) as cnt')
            ->first();

        $minLotExistant = (int) ($lotsNumeriques->min_lot ?? 0);
        $maxLotExistant = (int) ($lotsNumeriques->max_lot ?? 0);
        $cntExistant = (int) ($lotsNumeriques->cnt ?? 0);

        if ($nbAttribues > 0) {
            if ($ilotNouveau !== $ilot || $data['lot_debut'] !== $minLotExistant || $data['lot_fin'] !== $maxLotExistant) {
                return back()->with('error', "Impossible de modifier l’îlot {$ilot} (des lots sont déjà attribués).");
            }

            ProjetIlot::query()->updateOrCreate(
                [
                    'id_projet' => $projet->id,
                    'ilot' => $ilot,
                ],
                [
                    'superficie_totale' => (float) $data['superficie_total_ilot'],
                ]
            );

            return redirect()->route('dg.projets.lots.index', $projet)->with('success', 'Référence superficie totale de l’îlot mise à jour (les superficies par lot ne sont pas modifiées).');
        }

        if ($ilotNouveau !== $ilot) {
            ProjetLot::query()
                ->where('id_projet', $projet->id)
                ->where('ilot', $ilot)
                ->update(['ilot' => $ilotNouveau]);

            ProjetIlot::query()
                ->where('id_projet', $projet->id)
                ->where('ilot', $ilot)
                ->update(['ilot' => $ilotNouveau]);
        }

        $ilotCible = $ilotNouveau;

        ProjetIlot::query()->updateOrCreate(
            [
                'id_projet' => $projet->id,
                'ilot' => $ilotCible,
            ],
            [
                'superficie_totale' => (float) $data['superficie_total_ilot'],
            ]
        );

        ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilotCible)
            ->whereRaw("lot REGEXP '^[0-9]+$'")
            ->whereRaw('CAST(lot AS UNSIGNED) < ? OR CAST(lot AS UNSIGNED) > ?', [$data['lot_debut'], $data['lot_fin']])
            ->delete();

        for ($n = (int) $data['lot_debut']; $n <= (int) $data['lot_fin']; $n++) {
            $lotStr = (string) $n;
            $exists = ProjetLot::query()
                ->where('id_projet', $projet->id)
                ->where('lot', $lotStr)
                ->exists();
            if ($exists) {
                continue;
            }

            $projet->projetLots()->create([
                'ilot' => $ilotCible,
                'lot' => $lotStr,
                'type_logement' => null,
                'bien_immobilier_id' => null,
                'numero_page_guide' => null,
                'superficie' => null,
            ]);
        }

        return redirect()->route('dg.projets.lots.index', $projet)->with('success', 'Îlot modifié. Renseignez la superficie de chaque lot dans la liste des lots si besoin.');
    }

    public function destroyIlot(Projet $projet, string $ilot)
    {
        $nbAttribues = (int) ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->whereHas('attributionLot')
            ->count();

        if ($nbAttribues > 0) {
            return redirect()->route('dg.projets.lots.index', $projet)
                ->with('error', 'Cet îlot contient des lots attribués : suppression impossible.');
        }

        ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->delete();

        ProjetIlot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->delete();

        return redirect()->route('dg.projets.lots.index', $projet)->with('success', 'Îlot supprimé.');
    }

    public function duplicateIlot(Request $request, Projet $projet, string $ilot)
    {
        $data = $request->validate([
            'nouvel_ilot' => ['required', 'string', 'max:50'],
        ]);

        $nouvelIlot = $data['nouvel_ilot'];
        if ($nouvelIlot === $ilot) {
            return back()->with('error', 'Veuillez choisir un autre nom d’îlot pour dupliquer.');
        }

        $lots = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->get();

        if ($lots->isEmpty()) {
            abort(404);
        }

        $cree = 0;
        $ignores = 0;

        foreach ($lots as $lot) {
            $exists = ProjetLot::query()
                ->where('id_projet', $projet->id)
                ->where('lot', $lot->lot)
                ->exists();
            if ($exists) {
                $ignores++;

                continue;
            }

            $projet->projetLots()->create([
                'ilot' => $nouvelIlot,
                'lot' => $lot->lot,
                'type_logement' => null,
                'bien_immobilier_id' => null,
                'numero_page_guide' => null,
                'superficie' => $lot->superficie,
            ]);
            $cree++;
        }

        $meta = ProjetIlot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->first();
        if ($meta) {
            ProjetIlot::query()->updateOrCreate(
                [
                    'id_projet' => $projet->id,
                    'ilot' => $nouvelIlot,
                ],
                [
                    'superficie_totale' => $meta->superficie_totale,
                ]
            );
        }

        $msg = "Îlot dupliqué : {$cree} lot(s) créé(s).";
        if ($ignores > 0) {
            $msg .= " {$ignores} ligne(s) ignorée(s) (déjà présentes).";
        }

        return redirect()->route('dg.projets.lots.index', $projet)->with('success', $msg);
    }

    public function destroy(Projet $projet, ProjetLot $projet_lot)
    {
        if ((int) $projet_lot->id_projet !== (int) $projet->id) {
            abort(404);
        }

        if ($projet_lot->attributionLot()->exists()) {
            return redirect()->route('dg.projets.lots.ilot.lots', [$projet, $projet_lot->ilot])
                ->with('error', 'Ce lot est déjà attribué : suppression impossible.');
        }

        $ilot = $projet_lot->ilot;
        $projet_lot->delete();

        $encoreDesLots = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->exists();

        if (! $encoreDesLots) {
            ProjetIlot::query()
                ->where('id_projet', $projet->id)
                ->where('ilot', $ilot)
                ->delete();

            return redirect()->route('dg.projets.lots.index', $projet)
                ->with('success', 'Lot supprimé. L’îlot ne contient plus aucun lot.');
        }

        return redirect()->route('dg.projets.lots.ilot.lots', [$projet, $ilot])
            ->with('success', 'Lot supprimé.');
    }

    protected function creerLotsPourIlot(Projet $projet, string $ilot, int $lotDebut, int $lotFin, float $superficieTotalIlot): array
    {
        $ilot = trim($ilot);

        ProjetIlot::query()->updateOrCreate(
            [
                'id_projet' => $projet->id,
                'ilot' => $ilot,
            ],
            [
                'superficie_totale' => $superficieTotalIlot,
            ]
        );

        $cree = 0;
        $ignores = 0;

        for ($n = $lotDebut; $n <= $lotFin; $n++) {
            $lotStr = (string) $n;
            $exists = $projet->projetLots()
                ->where('lot', $lotStr)
                ->exists();
            if ($exists) {
                $ignores++;

                continue;
            }

            $projet->projetLots()->create([
                'ilot' => $ilot,
                'lot' => $lotStr,
                'type_logement' => null,
                'bien_immobilier_id' => null,
                'numero_page_guide' => null,
                'superficie' => null,
            ]);
            $cree++;
        }

        return [$cree, $ignores];
    }

    /**
     * Superficie totale de l’îlot (référence projet_ilots), ou null si non renseignée.
     */
    protected function superficieReferenceIlot(Projet $projet, string $ilot): ?float
    {
        $v = ProjetIlot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot)
            ->value('superficie_totale');

        return $v === null ? null : (float) $v;
    }

    /**
     * Somme des superficies des lots de l’îlot (NULL compté comme 0), en excluant éventuellement un lot.
     */
    protected function sommeSuperficiesLotsIlot(Projet $projet, string $ilot, ?int $excludeProjetLotId = null): float
    {
        $q = ProjetLot::query()
            ->where('id_projet', $projet->id)
            ->where('ilot', $ilot);
        if ($excludeProjetLotId !== null) {
            $q->where('id', '!=', $excludeProjetLotId);
        }

        return (float) $q->get()->sum(fn ($l) => (float) ($l->superficie ?? 0));
    }

    /**
     * Vérifie que la somme des superficies (autres lots + valeur saisie pour un lot) ne dépasse pas la référence îlot.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function validerSommeSuperficiesVsReference(Projet $projet, string $ilot, float $nouvelleSuperficiePourLeLot, ?int $excludeProjetLotId)
    {
        $ref = $this->superficieReferenceIlot($projet, $ilot);
        if ($ref === null) {
            return null;
        }

        $sommeAutres = $this->sommeSuperficiesLotsIlot($projet, $ilot, $excludeProjetLotId);
        $total = $sommeAutres + $nouvelleSuperficiePourLeLot;
        if ($total > $ref + 0.01) {
            $flashId = $excludeProjetLotId === null ? 'add' : $excludeProjetLotId;

            return back()
                ->withInput()
                ->withErrors([
                    'superficie' => sprintf(
                        'La somme des superficies des lots ne peut pas dépasser la superficie totale de l’îlot (référence : %s m²). Avec cette valeur, le total serait de %s m².',
                        number_format($ref, 2, ',', ' '),
                        number_format($total, 2, ',', ' ')
                    ),
                ])
                ->with('invalid_superficie_lot_id', $flashId);
        }

        return null;
    }
}
