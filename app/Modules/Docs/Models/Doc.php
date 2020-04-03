<?php

namespace App\Modules\Docs\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed channel_name
 */
class Doc extends Model
{
    protected $fillable = ['owner_id', 'title', 'data', 'metadata'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['role_id'])->withTimestamps();
    }

    public function getChannelNameAttribute()
    {
        return "presence-doc-$this->id";
    }

    /**
     * Get this docs viewers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function viewers()
    {
        return $this->belongsToMany(User::class, 'doc_viewers')->withTimestamps();
    }

}
