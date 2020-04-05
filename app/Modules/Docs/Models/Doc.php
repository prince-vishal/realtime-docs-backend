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
     * Get the json decoded data.
     *
     * @return array
     */
    public function getMetadataAttribute()
    {
        if ($this->attributes['metadata'] != null) {
            return json_decode($this->attributes['metadata'], true);
        }
        return [];
    }

    /**
     * Get the json decoded data.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        if ($this->attributes['data'] != null) {
            return json_decode($this->attributes['data'], true);
        }
        return [];
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
        return $this->belongsToMany(User::class, 'doc_viewers')
            ->withTimestamps()
            ->orderBy('doc_viewers.updated_at', 'desc');
    }

}
