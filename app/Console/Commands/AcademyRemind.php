<?php

namespace App\Console\Commands;

use App\Mail\WorkshopReminder;
use App\Models\Workshop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Comando per inviare promemoria via email ai partecipanti confermati
 * dei workshop previsti per il giorno successivo.
 *
 * Utilizzo: php artisan academy:remind
 *
 * Pensato per essere schedulato con cron (es. ogni giorno alle 18:00)
 * tramite il kernel di Laravel: $schedule->command('academy:remind')->dailyAt('18:00');
 */
class AcademyRemind extends Command
{
    protected $signature = 'academy:remind';
    protected $description = 'Invia email di promemoria per i workshop di domani';

    public function handle(): int
    {
        // Calcoliamo l'intervallo "domani" (dalle 00:00 alle 23:59)
        $tomorrow = now()->addDay()->startOfDay();
        $dayAfter = $tomorrow->copy()->addDay();

        $workshops = Workshop::whereBetween('date_time', [$tomorrow, $dayAfter])
            ->with(['confirmedRegistrations.user'])
            ->get();

        if ($workshops->isEmpty()) {
            $this->info('Nessun workshop in programma per domani.');
            return self::SUCCESS;
        }

        $totalSent = 0;

        foreach ($workshops as $workshop) {
            $confirmed = $workshop->confirmedRegistrations;

            foreach ($confirmed as $registration) {
                Mail::to($registration->user->email)->send(new WorkshopReminder($workshop));
                $totalSent++;
            }

            $this->info("Workshop \"{$workshop->title}\": {$confirmed->count()} email inviate.");
        }

        $this->info("Totale email inviate: {$totalSent}");

        return self::SUCCESS;
    }
}
