<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class bbs extends Model
{
    protected $table = 'bbs';

    public function company(){
        return $this->belongsToMany(company::class,'bbs_company','bbs_id','co_id');
    }
}
