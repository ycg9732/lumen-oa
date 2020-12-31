<?php


namespace App\Http\Controllers;


use App\Models\account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     *
     */
    public function account_add(){
        try {
            $url = $this->request->input('url');
            $name = $this->request->input('user_name');
            $pas = $this->request->input('password');
            $reg = $this->request->input('reg_person');
            $tel = $this->request->input('tel');
            $mark = $this->request->input('remark');
            $platform = $this->request->input('platform');

            $acc = new account();
            $acc->url = $url;
            $acc->user_name = $name;
            $acc->password = $pas;
            $acc->reg_person = $reg;
            $acc->tel = $tel;
            $acc->remark = $mark;
            $acc->platform = $platform;
            $acc->save();
            return $this->returnMessage('','ok');
        }catch (\PDOException $E){
            return $this->returnMessage('',$E->getMessage());
        }
    }
    public function account_list(){

        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $supplier_list = account::skip($limitprame)->take($perage)->get(['url','user_name','password','platform','reg_person']);
        $su_count = account::all()->count();
        $all = ceil($su_count/$perage);
        //todo 密码是否根据权限加密显示
        return $this->returnMessage($supplier_list);
    }

    /**
     * 账号库编辑
     */
    public function account_edit(){
        $id = $this->request->input('account_id');
        try {

            $acc = account::find($id);
            $acc->url = $this->request->input('url');
            $acc->user_name = $this->request->input('user_name');
            $acc->password = $this->request->input('password');
            $acc->reg_person = $this->request->input('reg_person');
            $acc->tel = $this->request->input('tel');
            $acc->remark = $this->request->input('remark');
            $acc->platform = $this->request->input('platform');

            $acc->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }
}
