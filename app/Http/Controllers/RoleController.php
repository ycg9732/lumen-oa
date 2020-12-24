<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request= $request;
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
    /**
     * 添加角色
     */
    public function role_add(){

    }


}
