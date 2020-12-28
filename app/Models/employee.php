<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    protected $table = 'employee';

    public function department(){
        return $this->belongsTo(department::class,'dept_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function company(){
        return $this->hasOne(company::class,'id','co_id');
    }
}
