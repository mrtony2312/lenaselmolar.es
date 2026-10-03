<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Merchant\GoogleFeedGenerator;
use Illuminate\Console\Command;
use RuntimeException;

class GenerateGoogleFeedCommand extends Command
{
    protected $signature = 'merchant:google-feed';

    protected $description = 'Write a snapshot of the Google Shopping XML feed to storage/app/feeds (the live feed is the /feeds/google-shopping.xml route)';

    public function handle(GoogleFeedGenerator $generator): int
    {
        try {
            $path = $generator->writeToDisk();
        } catch (RuntimeException $e) {
            \Illuminate\Support\Facades\Log::channel('merchant')->error('Feed generation failed: '.$e->getMessage());
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Snapshot written to '.$path.' — '.count($generator->excluded()).' product(s) excluded (see storage/logs/merchant-*.log).');

        return self::SUCCESS;
    }
}
