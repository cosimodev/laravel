<?php

namespace App\Console\Commands;

use App\Mail\WorkshopReminder;
use App\Models\Workshop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AcademyRemind extends Command
{
    protected $signature = 'academy:remind';
    protected $description = 'Send reminder emails for workshops happening tomorrow';

    public function handle(): int
    {
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
