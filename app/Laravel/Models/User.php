<?php

namespace App\Laravel\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable{
    
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $table = "users";

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            $model->connection = config('database.default');
        });

        static::deleting(function ($user) {
            $user->user_info()->delete();
            $user->activities()->delete();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    protected $appends = [];

    protected $dates = ['last_login_at'];

    public function user_info(){
		return $this->belongsTo('App\Laravel\Models\UserInfo', 'user_info_id', 'id');
	}

    public function activities(){
        return $this->hasMany('App\Laravel\Models\AuditTrail', 'user_id', 'id');
    }
}
