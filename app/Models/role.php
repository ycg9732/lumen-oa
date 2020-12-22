<?php


namespace App\Models;


class role extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'role';

    public function user(){
        return $this->belongsToMany(User::class,'user_role','role_id','user_id');
    }
    public function permission(){
        return $this->belongsToMany(permission::class,'role_permission','role_id','p_id');
    }

}
