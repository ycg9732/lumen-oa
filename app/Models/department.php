<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $table = 'department';

    public function employee(){
        return $this->hasMany(employee::class,'dept_id','id');
    }

    public function company(){
        return $this->belongsTo(company::class,'co_id','id');
    }

    public function children(){
        return $this->all_children()->with('children');
    }
    public function all_children(){

        return $this->hasMany(get_class($this),'pid','id');

    }
}
