<?php
/**
 * Laravel Queue for AWS Batch.
 *
 * @author    Luke Waite <lwaite@gmail.com>
 * @copyright 2017 Luke Waite
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @link      https://github.com/dnxlabs/laravel-queue-aws-batch
 */

namespace DNXLabs\LaravelQueueAwsBatch;

use Illuminate\Support\ServiceProvider;
use DNXLabs\LaravelQueueAwsBatch\Connectors\BatchConnector;
use DNXLabs\LaravelQueueAwsBatch\Console\QueueWorkBatchCommand;

class BatchQueueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            'command.queueawsbatch.work-batch',
            function ($app) {
                return new QueueWorkBatchCommand(
                    $app['queue'],
                    $app['queue.worker']
                );
            }
        );

        $this->commands('command.queueawsbatch.work-batch');
    }

    public function boot()
    {
        $this->registerBatchConnector($this->app['queue']);
    }

    /**
     * Register the Batch queue connector.
     *
     * @param \Illuminate\Queue\QueueManager $manager
     *
     * @return void
     */
    protected function registerBatchConnector($manager)
    {
        $manager->addConnector('batch', function () {
            return new BatchConnector($this->app['db']);
        });
    }
}
