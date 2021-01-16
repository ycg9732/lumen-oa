<?php


namespace App\Http\Controllers;


use App\Models\account;
use App\Models\supplier;
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
        $co_id = $this->request->input('co_id','81');
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $supplier_list = account::skip($limitprame)->take($perage)->where('co_id',$co_id)->get(['url','user_name','password','platform','reg_person','id'])->toArray();
        $su_count = account::all()->count();
        $all = ceil($su_count/$perage);
        //todo 密码是否根据权限加密显示
        $result['all'] = $all;
        $result['num'] = $su_count;
        $result['list'] = $supplier_list;
        return $this->returnMessage($result);
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
//            $acc->platform = $this->request->input('platform');

            $acc->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     *账号库删除
     */
    public function account_delete(){
        $id = $this->request->input('account_id');
        try {
            account::destroy($id);
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }
}
