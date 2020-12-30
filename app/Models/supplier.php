<?php


namespace App\Models;


class supplier extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'supplier';
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function goods(){
        return $this->hasMany(goods::class,'sup_id','id');
    }
}
