<?php


namespace App\Models;


class goods extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'goods';

    public function cert(){
        return $this->hasMany(goods_cert::class,'good_id','id');
    }

    public function img(){
        return $this->hasMany(goods_img::class,'good_id','id');
    }
}
