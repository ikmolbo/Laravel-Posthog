<?php

namespace QodeNL\LaravelPosthog\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PostHog\PostHog;
use QodeNL\LaravelPosthog\Traits\UsesPosthog;

class PosthogAliasJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use UsesPosthog;

    public function __construct(private string $sessionId, private string $userId)
    {
    }

    public function handle(): void
    {
        $this->posthogInit();

        try {
            Posthog::alias([
                'distinctId' => $this->userId,
                'alias'      => $this->sessionId,
            ]);
        } catch (Exception $e) {
            Log::info('Posthog alias call failed:' . $e->getMessage());
        }
    }

}