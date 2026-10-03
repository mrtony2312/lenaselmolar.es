<?php

namespace App\Jobs\Merchant;

use App\Domain\Merchant\GoogleFeedGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RefreshGoogleFeedJob implements ShouldQueue
{
    use Queueable;

    public function handle(GoogleFeedGenerator $generator): void
    {
        $generator->writeToDisk();
    }
}
