<?php


namespace App\Http\Controllers;


use App\Models\archives;
use App\Models\employee;
use App\Models\User;
use Illuminate\Http\Request;

class ArchivesController extends Controller
{
    protected $request;

    /**
     * ArchivesController constructor.
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * by you
     * 员工档案添加
     */
    public function arch_add(){
        try {
            $name = $this->request->input('name');
            $has = archives::where('name',$name)->first();
            if ($has){
                return $this->returnMessage('','员工档案已经存在');
            }
            $has_ee = employee::where('ee_name',$name)->value('user_id');
            if (!$has_ee){
                return $this->returnMessage('','，没有匹配到改员工的信息');
            }
            $sex = $this->request->input('sex');
            $edu = $this->request->input('edu');
            $school = $this->request->input('school');
            $job = $this->request->input('job');
            $tel = $this->request->input('tel');
            $join_date = $this->request->input('join_date');
            $img = $this->request->input('img');
            $arch = new archives();
            $arch->name = $name;
            $arch->sex = $sex;
            $arch->edu = $edu;
            $arch->school = $school;
            $arch->job = $job;
            $arch->tel = $tel;
            $arch->img = $img;
            $arch->join_date = $join_date;
            $arch->user_id = employee::where('ee_name',$name)->value('user_id');
            $arch->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }

    }

    /**
     * by you
     * 员工档案列表
     */
    public function arch_list(){
        $co_id = $this->request->input('id');
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $user_id = employee::where('co_id',$co_id)->pluck('user_id');
        $arch_list = archives::skip($limitprame)->take($perage)->whereIn('user_id',$user_id)->get()->toArray();
        $su_count = archives::all()->count();
        $all = ceil($su_count/$perage);
        return $this->returnMessage($arch_list);
    }

    /**
     * by you
     * 员工档案删除
     */
    public function arch_delete(){
        $id = $this->request->input('id');
        try {
            archives::destroy($id);
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * by you
     * 员工档案修改
     */
    public function arch_edit(){
        try {
            $id = $this->request->input('id');
            $name = $this->request->input('name');
            $sex = $this->request->input('sex');
            $edu = $this->request->input('edu');
            $school = $this->request->input('school');
            $job = $this->request->input('job');
            $tel = $this->request->input('tel');
            $join_date = $this->request->input('join_date');
            $img = $this->request->input('img');
            $arch = archives::find($id);
            $arch->name = $name;
            $arch->sex = $sex;
            $arch->edu = $edu;
            $arch->school = $school;
            $arch->job = $job;
            $arch->tel = $tel;
            $arch->img = $img;
            $arch->join_date = $join_date;
            $arch->user_id = employee::where('ee_name',$name)->value('user_id');
            $arch->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }

    }

    /**
     * by you
     * 员工档案详情
     */
    public function arch_detail(){
        $id = $this->request->input('id');
        $arch = archives::where('id',$id)->get(['name','sex','edu','school','job','tel','id','user_id','join_date'])->first()->toArray();
        $user = User::where('id',($arch['user_id']));
        $arch['user_name'] = $user->value('name');
        $arch['password'] = $user->value('password');
        return $this->returnMessage($arch);
    }

    /**
     * by you
     * 供前台模糊查询
     */
    public function all_name(){
        $name = employee::all()->pluck('ee_name')->toArray();
        $re = [];
        foreach ($name as $k => $v){
            $re[] = ['value'=> $v];
        }
        return $this->returnMessage($re);
    }

}
