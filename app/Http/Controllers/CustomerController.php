<?php

namespace App\Http\Controllers;


use App\Models\customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     *客户添加
     */
    public function customer_add(){
        try {
            $name = $this->request->input('com_name');
            $faren = $this->request->input('leg_person');
            $tell = $this->request->input('leg_tel');
            $charge_man = $this->request->input('charge_man');
            $charge_tel = $this->request->input('charge_tel');
            $from = $this->request->input('from');

            $cus = new customer();
            $cus->com_name = $name;
            $cus->leg_person = $faren;
            $cus->leg_tel = $tell;
            $cus->charge_man = $charge_man;
            $cus->charge_tel = $charge_tel;
            $cus->from = $from;

            $cus->save();
            return $this->returnMessage('','ok');
        }catch (\PDOException $E){
            return $this->returnMessage('',$E->getMessage());
        }

    }

    /**
     *客户详情
     */
    public function customer_detil(){

    }
}
