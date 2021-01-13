<?php


namespace App\Models;


class menu extends \Illuminate\Database\Eloquent\Model
{
    protected $table='menu';

    public function permission(){
        return $this->hasMany(permission::class,'menu_id','id');
    }

}
