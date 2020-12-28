<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class bbs extends Model
{
    protected $table = 'bbs';

    public function company(){
        return $this->belongsTo(company::class,'co_id','id');
    }
}
