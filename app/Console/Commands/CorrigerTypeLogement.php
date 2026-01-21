<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Souscription;
use App\Models\BienImmobilier;

class CorrigerTypeLogement extends Command
{
    /**
     * Le nom et la signature de la commande.
     *
     * @var string
     */
    protected $signature = 'souscriptions:corriger-type-logement';

    /**
     * La description de la commande.
     *
     * @var string
     */
    protected $description = 'Corriger les type_logement undefined en récupérant le titre du bien immobilier associé';

    /**
     * Exécuter la commande.
     */
    public function handle()
    {
        $this->info('=== CORRECTION DES TYPE_LOGEMENT UNDEFINED ===');
        
        // Compter les souscriptions à corriger
        $souscriptionsACorriger = Souscription::whereNotNull('bien_immobilier_id')
            ->where(function($query) {
                $query->whereNull('type_logement')
                      ->orWhere('type_logement', '')
                      ->orWhere('type_logement', 'undefined');
            })
            ->count();
        
        $this->info("Nombre de souscriptions à corriger : $souscriptionsACorriger");
        
        if ($souscriptionsACorriger > 0) {
            $this->info('Début de la correction...');
            
            $souscriptions = Souscription::whereNotNull('bien_immobilier_id')
                ->where(function($query) {
                    $query->whereNull('type_logement')
                          ->orWhere('type_logement', '')
                          ->orWhere('type_logement', 'undefined');
                })
                ->get();
            
            $corrigees = 0;
            $echecs = 0;
            
            $bar = $this->output->createProgressBar($souscriptions->count());
            $bar->start();
            
            foreach ($souscriptions as $souscription) {
                $bienImmobilier = BienImmobilier::find($souscription->bien_immobilier_id);
                
                if ($bienImmobilier && !empty($bienImmobilier->titre)) {
                    $souscription->type_logement = $bienImmobilier->titre;
                    $souscription->save();
                    $corrigees++;
                } else {
                    $this->warn("  - Échec pour souscription ID {$souscription->id}: Bien immobilier non trouvé ou titre vide");
                    $echecs++;
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            
            $this->info('=== RÉSULTAT ===');
            $this->info("Souscriptions corrigées : $corrigees");
            $this->info("Échecs : $echecs");
            
            // Vérification finale
            $restant = Souscription::whereNotNull('bien_immobilier_id')
                ->where(function($query) {
                    $query->whereNull('type_logement')
                          ->orWhere('type_logement', '')
                          ->orWhere('type_logement', 'undefined');
                })
                ->count();
            
            $this->info("Souscriptions problématiques restantes : $restant");
            
            if ($restant === 0) {
                $this->info('✓ Toutes les souscriptions ont maintenant un type_logement valide !');
            }
            
        } else {
            $this->info('Aucune correction nécessaire.');
        }
        
        return Command::SUCCESS;
    }
}