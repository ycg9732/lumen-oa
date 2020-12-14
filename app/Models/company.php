<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $table = 'company';

    public function dept(){
        return $this->hasMany(department::class,'co_id','id');
    }
}
