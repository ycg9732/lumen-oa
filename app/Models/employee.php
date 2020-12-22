<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    protected $table = 'employee';

    public function department(){
        return $this->belongsTo(department::class,'id','dept_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'id','user_id');
    }
}
