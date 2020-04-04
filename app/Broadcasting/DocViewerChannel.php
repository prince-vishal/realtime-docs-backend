<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Modules\Docs\Models\Doc;

class DocViewerChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User $user
     *
     * @param Doc               $doc
     *
     * @return array|bool
     */
    public function join(User $user, Doc $doc)
    {
        // If allowed to view doc
        // Currently allowing for all authenticated users
        return $user->toArray();
    }
}
