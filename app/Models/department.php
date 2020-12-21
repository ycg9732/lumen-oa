<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $table = 'department';

    public function employee(){
        return $this->hasMany(employee::class,'dept_id','id');
    }
}
