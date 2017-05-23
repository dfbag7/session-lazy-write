<?php namespace Dfbag7\SessionLazyWrite;

use Illuminate\Session\SessionManager as LaravelSessionManager;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NullSessionHandler;

class SessionManager extends LaravelSessionManager
{
    /**
     * Create an instance of the "array" session driver.
     *
     * @return \Dfbag7\SessionLazyWrite\Store
     */
    protected function createArrayDriver()
    {
        return new Store($this->app['config']['session.cookie'], new NullSessionHandler());
    }

    /**
     * Build the session instance.
     *
     * @param  \SessionHandlerInterface  $handler
     *
     * @return \Dfbag7\SessionLazyWrite\Store
     */
    protected function buildSession($handler)
    {
        return new Store($this->app['config']['session.cookie'], $handler);
    }
}
