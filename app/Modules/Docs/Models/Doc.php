<?php

namespace App\Modules\Docs\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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

}
