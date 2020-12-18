<?php


namespace App\Http\Controllers;

use App\Models\contract;
use Illuminate\Http\Request;

class ContractContraller extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    /**
     * 搜索
     */
    public function con_search(){
        $name = $this->request->input('con_name');
        $co_list = contract::where('con_name','like','%'.$name.'%')->get();
        return $this->returnMessage($co_list);

    }
    /**
     * add
     */
    public function con_add(){
        $date = $this->request->input('con_date');

    }
    /**
     * edit
     */
    public function con_edit(){

    }
    /**
     * detil
     */
    public function con_detil(){

    }
    /**
     *
     */
}
