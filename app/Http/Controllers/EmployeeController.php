<?php


namespace App\Http\Controllers;


use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function ee_list(Request $request){
        if ($request->has('de_member')){
            $de_member = employee::where('dept_id',null)->get(['id','ee_name']);
            return $this->returnMessage($de_member);
        }else{
            $currentPage = (int)$request->input('current_page','1');
            $perage = (int)$request->input('perpage','20');
            $limitprame = ($currentPage -1) * $perage;
            $ee_list = employee::skip($limitprame)->take($perage)->get();
            $ee_count = employee::all()->count();
            $all = ceil($ee_count/$perage);
            return $this->returnMessage($ee_list);

        }
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
            $employee->sex = $request->input('sex');
            $employee->job = $request->input('job');
            $employee->tel = $request->input('tel');
            $employee->address = $request->input('address');

            $employee->user_name = $request->input('user_name');
            $employee->password = sha1($request->input('password'));
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
        $employee->sex = $request->input('sex');
        $employee->job = $request->input('job');
        $employee->tel = $request->input('tel');
        $employee->address = $request->input('address');
        $employee->user_name = $request->input('user_name');
        $employee->password = sha1($request->input('password'));
        $employee->save();
        return $this->returnMessage('','ok');
    }
    /**
     * 删除
     */
    public function ee_delete(Request $request){
        $id = $request->input('id');
        try {
            DB::transaction(function () use ($id){
                employee::destroy($id);
            },2);
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('','no');
        }
    }
}
