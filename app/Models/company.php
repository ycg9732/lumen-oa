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
        return $this->children()->with('children');
    }
    //自关联
    public function parent(){
        return $this->hasOne(get_class($this), $this->getKeyName(), 'pid');
    }

    /**
     * 所有的部门
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function department(){
        return $this->hasMany(department::class,'co_id','id');
    }

    public function bbs(){
        return $this->hasMany(bbs::class,'co_id','id');
    }
}
