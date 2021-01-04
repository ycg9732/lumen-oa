<?php

namespace App\Http\Controllers;


use App\Models\customer;
use App\Models\employee;
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
        try {
            $id = $this->request->input('customer_id');
            $cus = customer::where('id',$id)->get(['com_name','leg_person','leg_tel','charge_man','charge_tel','from']);
            return $this->returnMessage($cus);
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * 客户列表
     */
    public function customer_list(){
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $customer_list = customer::skip($limitprame)->take($perage)->get(['com_name','user_id','created_at','charge_man','id']);
        $su_count = customer::all()->count();
        $all = ceil($su_count/$perage);
        foreach ($customer_list as $sup){
            $sup['created'] = substr($sup['created_at'],0,10);
            unset($sup['created_at']);
            if ($sup['user_id'] != null){
                $sup['user'] = employee::where('user_id',$sup['user_id'])->value('ee_name');
            }else{
                $sup['user'] = null;
            }
        }
        return $this->returnMessage($customer_list);
    }

    /**
     * 供应商删除s
     */
    public function customer_delete(){
        try {

            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }
}
