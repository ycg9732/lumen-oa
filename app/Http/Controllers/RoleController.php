<?php


namespace App\Http\Controllers;


use App\Models\menu;
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
        try {
            $role = role::find($role_id);
            $role->permission()->sync($p_id);
            $role->role_name = $this->request->input('role_name');
            $role->describe = $this->request->input('describe');
            $role->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
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
        $id = $this->request->input('id');
        try {
            $role = role::find($id);
            $role->role_name = $this->request->input('role_name');
            $role->describe = $this->request->input('describe');
            $role->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
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

    /**
     * by you
     * 角色界面权限数据
     */
    public function role_permission(){
        $role_id = $this->request->input('role_id');
        $menu = menu::all();
        $re = [];
        foreach ($menu as $k => $v){
            $re[$k] = [];
            $role = role::find($role_id);
            $has_permission = $role->permission->where('menu_id',$v['id']);
            $has = [];
            $has_id = [];
            $i = 0;//注意 多对多关联 查询的集合下标不是0开始的
            foreach ($has_permission as $k1 => $v1){
                $has[$i]['id'] = $v1['id'];
                $has_id[] = $v1['id'];
                $has[$i]['p_name'] = $v1['p_name'];
                $i++;
            }
            $without_p = permission::where('menu_id',$v['id'])->whereNotIn('id',$has_id)->get();
            $without = [];
            foreach ($without_p as $k2 => $v2){
                $without[$k2]['id'] = $v2['id'];
                $without[$k2]['p_name'] = $v2['p_name'];
            }
            $re[$k]['has'] = $has;
            $re[$k]['without'] = $without;
            $re[$k]['name'] = $v['menu_name'];
        }
        return $this->returnMessage($re);
    }

    /**
     * by you
     * 系统所有的权限
     */
    public function all_permission(){
        $p = permission::get(['id','p_name']);
        return $this->returnMessage($p);
    }
}
