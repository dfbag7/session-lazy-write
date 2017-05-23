<?php namespace Dfbag7\SessionLazyWrite;

use Illuminate\Session\SessionServiceProvider as LaravelSessionServiceProvider;

class SessionLazyWriteServiceProvider extends LaravelSessionServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected function registerSessionManager()
    {
        $this->app->bindShared('session', function($app)
        {
            return new SessionManager($app);
        });
    }
}
