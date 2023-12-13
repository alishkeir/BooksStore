<?php

namespace App;

use Alomgyar\Writers\Writer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasFactory, LogsActivity;

    protected static $logAttributes = ['*'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'lastname',
        'last_login_at',
        'last_login_ip',
        'last_login_device',
        'shop_id',
        'writer_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_device' => 'array',
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%')
            ->orWhere('lastname', 'like', '%'.$term.'%')
            ->orWhere('firstname', 'like', '%'.$term.'%')
            ->orWhere('name', 'like', '%'.$term.'%')
            ->orWhere('email', 'like', '%'.$term.'%');
    }

    public function getFullNameAttribute()
    {
        if ($this->lastname || $this->firstname) {
            return $this->lastname.' '.$this->firstname;
        } else {
            return $this->name;
        }
    }

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }
}
