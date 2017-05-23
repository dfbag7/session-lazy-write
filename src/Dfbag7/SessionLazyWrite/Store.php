<?php namespace Dfbag7\SessionLazyWrite;

use \Illuminate\Session\Store as LaravelSessionStore;

class Store extends LaravelSessionStore
{
    protected $originalAttributes;

    /**
     * {@inheritdoc}
     */
    protected function loadSession()
    {
        parent::loadSession();

        $this->originalAttributes = $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        // save if
        // 1. there are flash data in the session
        // 2. no flash data, but any attribute in the session has been changed

        if( ($this->get('flash.old', []) != [] || $this->get('flash.new', []) != [])
            || $this->originalAttributes != $this->attributes)
        {
            parent::save();
        }
        else
        {
            $this->started = false;
        }
    }
}
