<?php namespace Dfbag7\SessionLazyWrite;

use \Illuminate\Session\DatabaseSessionHandler as LaravelDatabaseSessionHandler;

class DatabaseSessionHandler extends LaravelDatabaseSessionHandler
{
    /**
     * {@inheritDoc}
     */
    public function write($sessionId, $data)
    {
        //
        // The standard Laravel implementation of this is prone to "key violation" SQL error,
        // because it may try to insert two or more records with the same session id
        // into sessions table.
        //
        // We're solving this by using "UPSERT" approach with table locking.
        // See http://www.the-art-of-web.com/sql/upsert/
        //
        // Note: we're not using PostgreSQL 9.5 UPSERT functionality for compatibility reasons.
        //
         \DB::transaction(function() use($sessionId, $data)
        {
            \DB::statement("lock table " . $this->table . " in share row exclusive mode");

            \DB::statement("
                with updated_rows as (
                   update " . $this->table . " set 
                   payload = :payload,
                   last_activity = :last_activity
                   where id = :session_id
                   returning *
                )
                insert into " . $this->table . "(id, payload, last_activity)
                select :session_id, :payload, :last_activity
                where not exists(select * from updated_rows)", [
                    ':session_id' => $sessionId,
                    ':payload' => base64_encode($data),
                    ':last_activity' => time(),
            ]);
        });

        $this->exists = true;
    }
}
