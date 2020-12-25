<?php


namespace App\Http\Controllers;


use App\Models\permission;
use App\Models\role;
use App\Models\role_permission;
use Illuminate\Http\Request;

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
//        $has_name = ;
//        if (){
//
//        }
        try {
            $role = new role();
            $role->role_name = $name;
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
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('',$e);
        }
    }

    /**
     * 添加权限
     */
    public function permission_add(){
        $p_name = $this->request->input('p_name');
        $p = new permission();
        $p->p_name = $p_name;
        $p->save();
    }

    /**
     * 角色绑定权限
     */
    public function role_add_permission(){

    }
    /**
     * 角色更改权限
     */
    public function role_change_permission(){

    }
    /**
     * 删除角色
     */
    public function role_delete(){

    }
}
