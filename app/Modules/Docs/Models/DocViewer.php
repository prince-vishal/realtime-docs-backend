<?php

namespace App\Modules\Docs\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DocViewer extends Model
{
    protected $fillable = ["doc_id", "user_id"];

    public function viewers()
    {
        return $this->belongsToMany(User::class);
    }
}
