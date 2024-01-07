<?php 

namespace Contracts\Bootstrap;

use App\Application;

abstract class AbstractBootstrap
{
    /**
     * application instance
     */
    private Application $app;

    /**
     * make instance of class
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * bootstrapper action
     */
    public function run(): void
    {
        // 
    }

    /**
     * get application instance
     */
    public function app(): Application
    {
        return $this->app;
    }
}