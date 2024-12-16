<?php

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model{
    
    use SoftDeletes;

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->connection = config('database.default');
        });
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "notifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    /**
     * The attributes that created within the model.
     *
     * @var array
     */
    protected $appends = [];
    protected $dates = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_research' => 'boolean',
        'has_student_research' => 'boolean',
        'has_all_research' => 'boolean',
        'has_completed_research' => 'boolean',
        'has_posted_research' => 'boolean',
    ];

    public function user(){
		return $this->belongsTo('App\Laravel\Models\User', 'user_id', 'id');
	} 
}