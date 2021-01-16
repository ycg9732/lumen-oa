<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function employer(){
        return $this->hasOne(employee::class,'user_id','id');
    }

    public function role(){
        return $this->belongsToMany(role::class,'user_role','user_id','role_id');
    }
    /**
     * by you
     * @param $p_name|判断用户是否拥有改权限
     * @return int|string
     */
    public function has($p_name)
    {
        $user_role = $this->role()->get();
        if (empty($user_role)){
            return '该用户没有分配角色';
        }
        $p_id = permission::where('p_name',$p_name)->value('id');
        if (empty($p_id)){
            return '系统没有该权限';
           }
        $permission = permission::find($p_id);
        $p_de_r = $permission->role()->get();
        $u_role_id = [];
        foreach ($user_role as $k1 => $v1){
            $u_role_id[] = $v1['id'];
        }
        $p_role_id = [];
        foreach ($p_de_r as $k2 => $v2){
            $p_role_id[] = $v2['id'];
        }
        if (empty(array_intersect($u_role_id,$p_role_id))){
            return 0;
        }else{
            return 1;
        }
    }
}
