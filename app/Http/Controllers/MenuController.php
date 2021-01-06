<?php


namespace App\Http\Controllers;


class MenuController extends Controller
{
    public function menu(){
        $res = [
            ['title'=>'企业管理','path'=>'/company','icon'=>'dashboard'],
//            ['title'=>'财务管理','path'=>'/finance','icon'=>'dashboard'],
            ['title'=>'人事管理','path'=>'/personnel','icon'=>'dashboard'],
//            ['title'=>'仓储管理','path'=>'/storage','icon'=>'dashboard'],
//            ['title'=>'物流管理','path'=>'/distribution','icon'=>'dashboard'],
//            ['title'=>'追踪管理','path'=>'/trace','icon'=>'dashboard'],
//            ['title'=>'资料管理','path'=>'/resource','icon'=>'dashboard'],
//            ['title'=>'采购管理','path'=>'/purchase','icon'=>'dashboard'],
//            ['title'=>'销售管理','path'=>'/marker','icon'=>'dashboard'],
        ];
        return $this->returnMessage($res);
    }
}
