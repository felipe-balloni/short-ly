<?php

namespace App\Console\Commands;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanVisitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::statement('SET foreign_key_checks=0');
        ShortURLVisit::truncate();
        DB::statement('SET foreign_key_checks=1');
    }
}
