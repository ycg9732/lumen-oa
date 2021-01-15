<?php


namespace App\Http\Controllers;


use App\Models\bbs;
use App\Models\department;
use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function de_list(Request $request){
        $co_id = $request->input('co_id');
        $currentPage = (int)$request->input('current_page','1');
        $perage = (int)$request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $de_list = department::skip($limitprame)->where('co_id',$co_id)->take($perage)->get();
        $de_count = department::all()->count();
        $all = ceil($de_count/$perage);
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
        $name = department::where('dept_name',$request->input('de_name'))->where('co_id',$request->input('co_id'))->count();
        if ($name > 0){
            return $this->returnMessage('','department exist');
        }else{
            $department = new department;
            $department->dept_name = $request->input('de_name');
            if (!empty($request->input('pid'))){
                $department->pid = $request->input('pid');
            }
            $department->co_id = $request->input('co_id');
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
        $department->pic = $request->input('pic');
        $department->save();
        return $this->returnMessage('','ok');
    }
    /**
     * 删除
     */
    public function de_delete(Request $request){
        $id = $request->input('id');
        try {
            DB::transaction(function () use ($id){
                department::destroy($id);
                employee::where('dept_id',$id)
                ->update(['dept_id' => null]);
            },2);
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('','no');
        }
    }
    /**
     * 部门下的员工
     */
    public function de_per_list(Request $request){
        $id = $request->input('id');
        $employee = department::find($id)->employee()->get(['id','ee_name','sex','job','tel']);
        return $this->returnMessage($employee);
    }

    /**
     * 部门添加员工
     * @param Request $request
     */
    public function de_add_ee(Request $request){
        $de_id = $request->input('de_id');
        $ee_id = $request->input('ee_id');
        $employee = employee::find($ee_id);
        $employee->dept_id = $de_id;
        $employee->save();
        //todo 捕获准确的操作结果
        return $this->returnMessage('','ok');
    }
    public function de_tree(){
        $tree = department::with('children')->where('co_id','81')->first()->toArray();
        $new_tree = $this->get_tree($tree);
        return $this->returnMessage($new_tree);
    }

    public function get_tree(&$right_tree){
        $tree = $right_tree;
        foreach ($tree as $k => $v) {
            if (is_array($tree[$k])) {
                $tree[$k] = $this->get_tree($tree[$k]);
            } else {
                if ($k != 'dept_name'){
                    unset($tree[$k]);
                }else{
                    $tree['label'] = $tree[$k];
                    unset($tree[$k]);
                }
            }
        }
        return $tree;
    }

}
