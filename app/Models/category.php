<?php


namespace App\Models;


class category extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'category';
    public function goods(){
        return $this->hasMany(goods::class,'ca_id','id');
    }
}
