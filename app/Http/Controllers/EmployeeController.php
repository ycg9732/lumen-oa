<?php


namespace App\Http\Controllers;


use App\Models\employee;
use App\Models\permission;
use App\Models\role;
use App\Models\role_permission;
use App\Models\User;
use App\Models\user_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * 搜索接口(名称搜索)
     */
    public function ee_search(Request $request){
        $co_id = $request->input('co_id','81');
        $name = $request->input('ee_name');
        if (empty($name)){
            $ee_list = employee::where('co_id',$co_id)->get();
        }else{
            $ee_list = employee::where('ee_name','like','%'.$name.'%')->where('co_id',$co_id)->get();
        }
        return $this->returnMessage($ee_list);
    }
    /**
     *员工列表接口
     */
    public function ee_list(Request $request){
        if ($request->has('de_member')){
            $de_member = employee::where('dept_id',null)->where('co_id',$request->input('co_id'))->get(['id','ee_name']);
            return $this->returnMessage($de_member);
        }else{
            $co_id = $request->input('co_id','81');
            $currentPage = (int)$request->input('current_page','1');
            $perage = (int)$request->input('perpage','20');
            $limitprame = ($currentPage -1) * $perage;
            $ee_list = employee::skip($limitprame)->take($perage)->where('co_id',$co_id)->get();
            $ee_count = employee::all()->count();
            $all = ceil($ee_count/$perage);
            return $this->returnMessage($ee_list);
        }
    }
    /**
     * 员工详情
     */
    public function ee_detil(Request $request){
        try {
            $ee_id = $request->input('ee_id');
            $ee_info = employee::where('id',$ee_id)->get()->toArray();
            $co_name = employee::find($ee_id)->company->co_name;
            $dept_name = employee::find($ee_id)->department->dept_name;
            $ee = employee::find($ee_id);
            $user = User::find(($ee_info[0]['user_id']));
            $ee_info[0]['co_name'] = $co_name;
            $ee_info[0]['dept_name'] = $dept_name;
            $ee_info[0]['user_name'] = $ee->user->name;
            $ee_info[0]['password'] = $ee->user->password;
            $ee_info[0]['role'] = $user->role()->get(['role_name','role_id']);
            return $this->returnMessage($ee_info);
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }
    /**
     * 添加员工
     */
    public function ee_add(Request $request){
        $user_name = employee::where('ee_name',$request->input('ee_name'))->count();
        if ($user_name > 0){
            return 'employee exist';
        }else{
            try {
                DB::transaction(function () use ($request){
                    $user = new User();
                    $user->name = $request->input('user_name');
                    $user->password = sha1($request->input('password'));
                    $user->save();
                    $user_id = $user->id;
                    $employee = new employee;
                    $employee->ee_name = $request->input('ee_name');
                    $employee->co_id = $request->input('co_id');
                    $employee->dept_id = $request->input('dept_id');
                    $employee->ee_name = $request->input('ee_name');
                    $employee->job = $request->input('job');
                    $employee->sex = $request->input('sex');
                    $employee->tel = $request->input('tel');
                    $employee->user_id = $user_id;
                    $employee->save();
                    $role_id = $request->input('role_id');
                    $user_role = new user_role();
                    $user_role->user_id = $user_id;
                    $user_role->role_id = $role_id;
                    $user_role->save();
                });
                return $this->returnMessage('','ok');
            }catch (\PDOException $e){
                return $this->returnMessage('',$e);
            }
        }
    }
    /**
     *修改详情
     */
    public function ee_edit(Request $request){
        try {
            DB::transaction(function () use ($request){
                $ee_id = $request->input('ee_id');
                $employee = employee::find($ee_id);
                $employee->ee_name = $request->input('ee_name');
                $employee->sex = $request->input('sex');
                $employee->job = $request->input('job');
                $employee->tel = $request->input('tel');
                $employee->address = $request->input('address');
                $employee->save();
                $user_name = $request->input('user_name');
                $password = $request->input('password');
                $role_id = $request->input('role_id');
                //修改用户名密码
                if (!empty($user_name) and !empty($password)){
                    $user = User::find($employee->first()->value('user_id'));
                    $user->name = $user_name;
                    $user->password = sha1('userloginregister'.$password);
                    $user->save();
                }
                //修改角色
                if (!empty($role_id)){
                    $user_id = employee::where('id',$ee_id)->value('user_id');
                    throw new \Exception($ee_id);
                    $user = User::find($user_id);
                    $ids = explode(',',$role_id);
                    $user->role()->sync($ids);
                }
            },2);
            return $this->returnMessage('','ok');
        }catch (\Exception $e){
            return $this->returnMessage($e->getMessage());
        }
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

    /**
     * 角色列表
     */
    public function role_list(){
        $role_list = role::all();
        return $this->returnMessage($role_list);
    }

    /**
     * 权限列表
     */
    public function permission_list(Request $request){
        $p = permission::all();
        $role_id = $request->input('role_id');
//        return $role_id;
        if ($role_id){
            $r_p = role::find($role_id)->permission;
            return $this->returnMessage($r_p);
        }
        return $this->returnMessage($p);
    }
}
