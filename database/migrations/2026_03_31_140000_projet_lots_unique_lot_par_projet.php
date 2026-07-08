<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Un numéro de lot doit être unique pour tout le projet (pas seulement par îlot).
     */
    public function up(): void
    {
        // L’index composite unique sert souvent seul pour la FK id_projet → projets : il faut un index dédié avant de le supprimer.
        $hasOtherIdProjetIndex = DB::selectOne("
            SELECT COUNT(*) AS c FROM information_schema.statistics
            WHERE table_schema = DATABASE() AND table_name = 'projet_lots'
              AND column_name = 'id_projet'
              AND index_name NOT IN ('projet_lots_projet_ilot_lot_unique', 'PRIMARY')
        ");
        if ((int) ($hasOtherIdProjetIndex->c ?? 0) === 0) {
            Schema::table('projet_lots', function (Blueprint $table) {
                $table->index('id_projet', 'projet_lots_id_projet_index');
            });
        }

        Schema::table('projet_lots', function (Blueprint $table) {
            $table->dropUnique('projet_lots_projet_ilot_lot_unique');
        });

        $duplicateGroups = DB::table('projet_lots')
            ->select('id_projet', 'lot')
            ->groupBy('id_projet', 'lot')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateGroups as $g) {
            $ids = DB::table('projet_lots')
                ->where('id_projet', $g->id_projet)
                ->where('lot', $g->lot)
                ->orderBy('id')
                ->pluck('id');

            $keepId = null;
            foreach ($ids as $id) {
                if (DB::table('attribution_lots')->where('projet_lot_id', $id)->exists()) {
                    $keepId = (int) $id;
                    break;
                }
            }
            if ($keepId === null) {
                $keepId = (int) $ids->first();
            }
            foreach ($ids as $id) {
                if ((int) $id !== $keepId) {
                    DB::table('projet_lots')->where('id', $id)->delete();
                }
            }
        }

        Schema::table('projet_lots', function (Blueprint $table) {
            $table->unique(['id_projet', 'lot'], 'projet_lots_projet_lot_unique');
        });
    }

    public function down(): void
    {
        Schema::table('projet_lots', function (Blueprint $table) {
            $table->dropUnique('projet_lots_projet_lot_unique');
        });

        Schema::table('projet_lots', function (Blueprint $table) {
            $table->unique(['id_projet', 'ilot', 'lot'], 'projet_lots_projet_ilot_lot_unique');
        });

        if (Schema::hasIndex('projet_lots', 'projet_lots_id_projet_index')) {
            Schema::table('projet_lots', function (Blueprint $table) {
                $table->dropIndex('projet_lots_id_projet_index');
            });
        }
    }
};
