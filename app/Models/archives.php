<?php


namespace App\Models;


class archives extends \Illuminate\Database\Eloquent\Model
{
protected $table = 'archives';

    public function employee(){
        return $this->hasOne(employee::class,'user_id','user_id');
    }
}
