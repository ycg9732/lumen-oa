<?php


namespace App\Http\Controllers;


use App\Models\archives;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
            $user = User::where('name', '=', $request->input('name'))->where('password', '=', sha1($this->salt.$request->input('password')))->first();
            $ee_name = $user->employer->ee_name;
            $img = archives::where('user_id',$user['id'])->value('img');
            if($user){
                $token = md5(uniqid(microtime(true),true));
                $user->api_token = $token;
                $user->save();
                return $this->returnMessage(['token'=>$user->api_token,'name'=>$ee_name,'img'=>env('APP_URL').'/img/img/arch/'.$img],"登录成功");
            }else{
                return $this->returnMessage('','用户名或密码不正确,登录失败');
            }
        }else{
            return $this->returnMessage('','登录信息不完整,请输入用户名和密码');
        }
    }

    /**
     * by you
     * 消息详情
     */
    public function read(){
////        $id = Auth::id();
        $user = User::find(3);
//        $unread_data = $user->unreadNotifications;
//        $all_data = $user->Notifications;
//        $datetime = new \DateTime;
//        $datetime->format('Y-m-d H:i:s');
////标记已读
//        $user->unreadNotifications()->update(['read_at' => $datetime]);
//        return $this->returnMessage(['unread'=>$unread_data,'all'=>$all_data]);

//        $num = $user->Notifications->where('data','[json_encode(['id' => 1,'name' => 'sa','msg' => 'hhhh']]')->count();
        return $this->returnMessage(['id' => 1,'name' => 'sa','msg' => 'hhhh']);
    }

    /**
     * by you
     * 消息列表
     */
    public function massage_list(){
//        $num = $user->Notifications->where('data',['id' => 1,'name' => 'sa','msg' => '营业执照到期'])->count();
        return $this->returnMessage('$num');
    }

    /**
     * by you
     * 用户修改密码
     */
    public function user_info(){
        $user_id = Auth::id();
        $old_password = $this->request->input('old');
        $new_password = $this->request->input('new');
//        if (){
//
//        }else{
//            return $this->returnMessage('','密码错误,请重新填写');
//        }
    }
}
