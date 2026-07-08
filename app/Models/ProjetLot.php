<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ProjetLot extends Model
{
    protected $table = 'projet_lots';

    protected $fillable = [
        'id_projet',
        'bien_immobilier_id',
        'type_logement',
        'ilot',
        'lot',
        'numero_page_guide',
        'superficie',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
    ];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'id_projet');
    }

    public function bienImmobilier(): BelongsTo
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_immobilier_id');
    }

    public function attributionLot(): HasOne
    {
        return $this->hasOne(AttributionLot::class, 'projet_lot_id');
    }

    /**
     * Lots libres pour ce projet, filtrés par bien / type de la souscription.
     * Si la souscription a un bien : le type de logement vient du bien — on ne filtre pas sur type_logement texte.
     */
    public function scopeDisponiblesPourSouscription(Builder $query, Souscription $souscription): Builder
    {
        $query
            ->where('id_projet', $souscription->programme)
            ->whereDoesntHave('attributionLot')
            ->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('attribution_lots')
                    ->whereColumn('attribution_lots.idProjet', 'projet_lots.id_projet')
                    ->whereColumn('attribution_lots.ilot', 'projet_lots.ilot')
                    ->whereColumn('attribution_lots.lot', 'projet_lots.lot');
            })
            ->where(function ($q) use ($souscription) {
                if ($souscription->bien_immobilier_id) {
                    $q->whereNull('bien_immobilier_id')
                        ->orWhere('bien_immobilier_id', $souscription->bien_immobilier_id);
                } else {
                    $q->whereNull('bien_immobilier_id');
                }
            });

        if (! $souscription->bien_immobilier_id) {
            $query->where(function ($q) use ($souscription) {
                $q->whereNull('type_logement')
                    ->orWhere('type_logement', $souscription->type_logement);
            });
        }

        return $query->with('bienImmobilier')->orderBy('ilot')->orderBy('lot');
    }

    /**
     * Libellé pour liste déroulante (sans superficie : elle est saisie à l’attribution
     * et peut différer de la référence inventaire ; préremplissage via data-superficie en vue).
     */
    public function libelleSelect(): string
    {
        $parts = ["Îlot {$this->ilot}", "Lot {$this->lot}"];
        if ($this->numero_page_guide) {
            $parts[] = "page {$this->numero_page_guide}";
        }
        $libelleType = $this->bienImmobilier?->titre ?? $this->type_logement;
        if ($libelleType) {
            $parts[] = $libelleType;
        }

        return implode(' — ', $parts);
    }
}
