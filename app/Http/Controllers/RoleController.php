<?php


namespace App\Http\Controllers;


use App\Models\permission;
use App\Models\role;
use App\Models\role_permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request= $request;
    }

    /**
     * 添加角色并分配权限
     */
    public function role_add(){
        $name = $this->request->input('role_name');
        $p_id = $this->request->input('p_id');
        $describe = $this->request->input('describe');
        if (!$p_id){
            return $this->returnMessage('','请选择角色的权限');
        }
        try {
            DB::transaction(function ()use ($name,$p_id,$describe){
                $role = new role();
                $role->role_name = $name;
                $role->describe = $describe;
                $role->save();
                $role_id = $role->id;
                if ($p_id){
                    $p_id = explode(',',$p_id);
                    foreach ($p_id as $k => $v){
                        $r_p = new role_permission();
                        $r_p->p_id = $v;
                        $r_p->role_id = $role_id;
                        $r_p->save();
                    }
                }
            });
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('',$e);
        }
    }

    /**
     * 添加权限
     */
    public function permission_add(){
        $p_name = $this->request->input('p_name');$p = new permission();
        $is_have = permission::where('p_name',$p_name)->count();
        if ($is_have > 0){
            return $this->returnMessage('','权限已经存在');
        }
        $p->p_name = $p_name;
        $p->save();
        return $this->returnMessage('','ok');
    }

    /**
     * 角色更改权限
     */
    public function role_change_permission(){
        $role_id = $this->request->input('role_id');
//        $permission = permission::findMany([1,2,3]);
        $id = $this->request->input('p_id');
        $p_id = explode(',',$id);
        $role = role::find($role_id);
        $role->permission()->sync($p_id);
        return $this->returnMessage();
    }
    /**
     * 删除角色
     */
    public function role_delete(){
        $role_id = $this->request->input('role_id');
        try {
            DB::transaction(function () use ($role_id){
                role::destroy($role_id);
                $s = role_permission::where('role_id',$role_id)->get();
                $t = [];
                foreach ($s as $k => $v){
                    $t[] = $v['id'];
                }
                role_permission::destroy($t);
            });
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('',$e);
        }

    }

    /**
     * by you
     * 角色信息编辑
     */
    public function role_info_edit(){

    }

    /**
     * by you
     * 角色信息详情
     */
    public function role_info_detail(){
        $id = $this->request->input('id');
        $info = role::find($id);
        return $this->returnMessage($info);
    }
}
