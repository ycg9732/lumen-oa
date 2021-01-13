<?php


namespace App\Models;


class permission extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'permission';

    public function role(){
        return $this->belongsToMany(role::class,'role_permission','p_id','role_id');
    }

    public function menu(){
        return $this->belongsTo(menu::class,'menu_id','id');
    }

}
