<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $table = 'project';

    public function company(){
        return $this->belongsTo(company::class,'co_id','id');
    }

}
