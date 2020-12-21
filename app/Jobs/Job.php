<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class Job implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int The number of times the job may be attempted. */
    public $tries = 3;

    public function __construct()
    {
        if (env('APP_ENV') !== 'testing') {
            $this->onConnection('jobs');
            $this->onQueue('jobs');
        }
    }

    public function failed(Exception $e)
    {
        // Send user notification of failure, etc...
        Log::critical('A job failed: ', [
            'job'   => static::class,
            'error' => $e->getMessage(),
        ]);
    }
}
