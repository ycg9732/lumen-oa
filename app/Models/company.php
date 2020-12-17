<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $table = 'company';

    public function children(){
        return $this->hasMany(get_class($this),'pid','id');
    }
    public function allchildren(){
        return $this->children()->with('allchildren');
    }
    public function parent(){
        return $this->hasOne(company::class,'id','pid');
    }
}
