<?php

namespace App\Models;

use App\Modules\Docs\Models\Doc;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static create(array $array)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'source',
        'company',
        'oauth_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'oauth_token',
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Get docs created by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function docs()
    {
        return $this->hasMany(Doc::class, 'owner_id');
    }

    /**
     * Get docs shared with this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accessibleDocs()
    {
        return $this->belongsToMany(Doc::class, 'doc_users')->withPivot(['role_id'])->withTimestamps()
            ->orderBy('doc_viewers.updated_at', 'desc');
    }

    /**
     * Get docs that are viewed by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function viewedDocs()
    {
        return $this->belongsToMany(Doc::class, 'doc_viewers')->withTimestamps()
            ->orderBy('doc_viewers.updated_at', 'desc');
    }
}
