<?php

namespace App\Modules\Docs\Models;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use Illuminate\Database\Eloquent\Model;

class DocUser extends Model
{

    public function role()
    {
        return $this->hasMany(Role::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function doc()
    {
        return $this->hasMany(Doc::class);
    }


}
