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
        return $this->belongsToMany(bbs::class,'bbs_company','co_id','bbs_id');
    }

    /**
     * 公司所有的合同
     */
    public function contract(){
        return $this->hasMany(contract::class,'co_id','id');
    }
    /**
     * 公司所有的项目
     */
    public function project(){
        return $this->hasMany(project::class,'co_id','id');
    }
}
