<?php


namespace App\Http\Controllers;


use App\Models\department;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnValueMap;

class DepartmentController extends Controller
{
    /**
     * 搜索接口(名称搜索)
     */
    public function de_search(Request $request){
        $name = $request->input('de_name');
        $de_list = department::where('dept_name','like','%'.$name.'%')->get();
        return $this->returnMessage($de_list);
    }
    /**
     *组织架构列表接口
     */
    public function de_list(){
        //todo 分页
        $de_list = department::all();
        return $this->returnMessage($de_list);
    }
    /**
     * 组织架构详情
     */
    public function de_detil(Request $request){
        $de_id = $request->input('de_id');
        $de_info = department::where('id',$de_id)->get();
        return $this->returnMessage($de_info);
    }
    /**
     * 添加组织架构
     */
    public function de_add(Request $request){
        $name = department::where('dept_name',$request->input('de_name'))->count();
        if ($name > 0){
            return 'department exist';
        }else{
            $department = new department;
            $department->dept_name = $request->input('de_name');
//            $department->co_code = $request->input('co_code');
            $department->save();
            return $this->returnMessage('','ok');
        }
    }
    /**
     *修改详情
     */
    public function de_edit(Request $request){
        $de_id = $request->input('de_id');
        $department = department::find($de_id);
        $department->dept_name = $request->input('de_name');
//        $department->co_code = $request->input('co_code');
        $department->save();
        return $this->returnMessage('','ok');
    }

}
