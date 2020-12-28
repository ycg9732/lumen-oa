<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class contract extends Model
{
    protected $table = 'contract';

    public function company(){
        return $this->belongsTo(company::class,'co_id','id');
    }
}
