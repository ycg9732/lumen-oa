<?php


namespace App\Http\Controllers;


use App\Models\employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * 搜索接口(名称搜索)
     */
    public function ee_search(Request $request){
        $name = $request->input('ee_name');
        $ee_list = employee::where('ee_name','like','%'.$name.'%')->get();
        return $this->returnMessage($ee_list);
    }
    /**
     *员工列表接口
     */
    public function ee_list(){
        //todo 分页
        $ee_list = employee::all();
        return $this->returnMessage($ee_list);
    }
    /**
     * 员工详情
     */
    public function ee_detil(Request $request){
        $ee_id = $request->input('ee_id');
        $ee_info = employee::where('id',$ee_id)->get();
        return $this->returnMessage($ee_info);
    }
    /**
     * 添加员工
     */
    public function ee_add(Request $request){
        $id_card = employee::where('id_card',$request->input('id_card'))->count();
        if ($id_card > 0){
            return 'employee exist';
        }else{
            $employee = new employee;
            $employee->ee_name = $request->input('ee_name');
            $employee->id_card = $request->input('id_card');
            $employee->save();
            return $this->returnMessage('','ok');
        }
    }
    /**
     *修改详情
     */
    public function ee_edit(Request $request){
        $ee_id = $request->input('ee_id');
        $employee = employee::find($ee_id);
        $employee->ee_name = $request->input('ee_name');
        $employee->id_card = $request->input('id_card');
        $employee->save();
        return $this->returnMessage('','ok');
    }
}
