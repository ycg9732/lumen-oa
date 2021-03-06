<?php


namespace App\Http\Controllers;


use App\Models\archives;
use App\Models\company;
use App\Models\department;
use App\Models\employee;
use App\Models\User;
use Carbon\Carbon;
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
            if($user){
                $ee_name = $user->employer->ee_name;
                $co_id = $user->employer->co_id;
                $co_name = company::where('id',$co_id)->value('co_name');
                $img = archives::where('user_id',$user['id'])->value('img');
                $token = md5(uniqid(microtime(true),true));
                $user->api_token = $token;
                $user->save();
                return $this->returnMessage(['token'=>$user->api_token,'name'=>$ee_name,'img'=>env('APP_URL').'/img/img/arch/'.$img,'company' => $co_name],"登录成功");
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
        $read = $this->request->input('if_read');
        $user = Auth::user();
        if($read == 0){
            $msg = $user->unreadNotifications;
            $re = [];
            foreach ($msg as $k => $v){
                $re[] = $v['data'];
            }
            return $this->returnMessage($re);
        }elseif($read == 1){
            $msg = $user->readNotifications;
            $re = [];
            foreach ($msg as $k => $v){
                $re[$k] = $v['data'];
            }
            return $this->returnMessage($re);
        }else{
            $msg = $user->notifications;
            $re = [];
            foreach ($msg as $k => $v){
                $re[] = $v['data'];
            }
            return $this->returnMessage($re);
        }
    }

    /**
     * by you
     * 用户未读消息总数
     */
    public function message_num(){
        $user = Auth::user();
        $num = $user->unreadNotifications->count();
        return $this->returnMessage($num);
    }

    /**
     * by you
     * 标记消息为已读
     */
    public function mark_message(){
        try {
            $user = Auth::user();
//   标记为已读
            $user->unreadNotifications()->update(['read_at' => Carbon::now()]);
            return $this->returnMessage();
        }catch (\Exception $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * by you
     * 用户修改密码
     */
    //  todo 修改密码后需要重新登录
    public function change_password(){
        $user_id = Auth::id();
        $old_password = sha1($this->salt.$this->request->input('old'));
        $new_password = sha1($this->salt.$this->request->input('new'));
        $password = User::where('id',$user_id)->value('password');
        if ($old_password == $password){
            try {
                $user = User::find($user_id);
                $user->password = $new_password;
                $user->save();
                return $this->returnMessage();
            }catch (\PDOException $e){
                return $this->returnMessage($e->getMessage());
            }
        }else{
            return $this->returnMessage('','密码确认错误,请重新填写');
        }
    }

    /**
     * by you
     * 用户信息
     */
    public function user_info(){
        $user_id = Auth::id();
        $employer = employee::where('user_id',$user_id)->get()->toArray();
        foreach ($employer as $k => $v){
            $employer[$k]['company'] = company::where('id',$v['co_id'])->value('co_name');
            $employer[$k]['department'] = department::where('id',$v['dept_id'])->value('dept_name');
        }
        return $this->returnMessage($employer);
    }
}
