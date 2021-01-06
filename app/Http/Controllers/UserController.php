<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $salt;
    private $request;
    public function __construct(Request $request)
    {
        $this->salt = "userloginregister";
        $this->request = $request;
    }
    /**
     * 登录接口
     */
    public function login(Request $request){
        if($request->has('name') && $request->has('password')){
            $user = User::where('name', '=', $request->input('name'))->where('password', '=', $request->input('password'))->first();
            if($user){
                $token = md5(uniqid(microtime(true),true));
                $user->api_token = $token;
                $user->save();
                return $this->returnMessage(['token'=>$user->api_token],"登录成功");
            }else{
                return $this->returnMessage('','用户名或密码不正确,登录失败');
            }
        }else{
            return $this->returnMessage('','登录信息不完整,请输入用户名和密码');
        }
    }
}
