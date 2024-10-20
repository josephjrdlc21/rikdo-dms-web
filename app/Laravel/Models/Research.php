<?php

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Research extends Model{
    
    use SoftDeletes;

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->connection = config('database.default');
        });

        static::deleting(function ($research) {
            $research->logs()->delete();
            $research->shared()->delete();
        });
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "research";
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
    protected $casts = [];

    public function submitted_by(){
        return $this->belongsTo('App\Laravel\Models\User', 'submitted_by_id', 'id');
    }

    public function submitted_to(){
        return $this->belongsTo('App\Laravel\Models\User', 'submitted_to_id', 'id');
    }

    public function research_type(){
        return $this->belongsTo('App\Laravel\Models\ResearchType', 'research_type_id', 'id');
    }

    public function department(){
		return $this->belongsTo('App\Laravel\Models\Department', 'department_id', 'id');
	}

    public function course(){
		return $this->belongsTo('App\Laravel\Models\Course', 'course_id', 'id');
	}

    public function yearlevel(){
		return $this->belongsTo('App\Laravel\Models\Yearlevel', 'yearlevel_id', 'id');
	}

    public function logs(){
        return $this->hasMany('App\Laravel\Models\ResearchLog', 'research_id', 'id');
    }

    public function shared(){
        return $this->hasMany('App\Laravel\Models\SharedResearch', 'research_id', 'id');
    }

    public function modified_by(){
        return $this->belongsTo('App\Laravel\Models\User', 'modified_by_id', 'id');
    }

    public function processed_by(){
        return $this->belongsTo('App\Laravel\Models\User', 'process_by_id', 'id');
    }
}