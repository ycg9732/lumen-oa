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

            $acc = new account();
            $acc->url = $url;
            $acc->user_name = $name;
            $acc->password = $pas;
            $acc->reg_person = $reg;
            $acc->tel = $tel;
            $acc->remark = $mark;
            $acc->save();
            return $this->returnMessage('','ok');
        }catch (\PDOException $E){
            return $this->returnMessage('',$E->getMessage());
        }
    }
}
