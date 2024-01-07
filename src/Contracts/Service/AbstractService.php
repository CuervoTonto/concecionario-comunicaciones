<?php 

namespace Contracts\Service;

use App\Application;

abstract class AbstractService
{
    /**
     * application instance
     */
    private Application $app;

    /**
     * make a instance of class
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * register instances
     */
    public function register(): void
    {
        // some...
    }

    /**
     * boot service
     */
    public function boot(): void
    {
        // some...
    }

    /**
     * terminate service
     */
    public function terminate(): void
    {
        // some...
    }

    /**
     * get application instance
     */
    protected function app(): Application
    {
        return $this->app;
    }
}