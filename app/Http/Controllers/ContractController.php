<?php


namespace App\Http\Controllers;


use App\Models\employee;
use App\Models\permission;
use App\Models\role;
use App\Models\role_permission;
use App\Models\User;
use App\Models\user_role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\contract;
use Illuminate\Http\Request;

/**
 * Class ContractController
 * @package App\Http\Controllers
 */
//todo 选中合同审批人的同时需要生成对应员工的消息?
class ContractController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    /**
     * 搜索
     */
    public function con_search(){
        $name = $this->request->input('con_name');
        $co_id = $this->request->input('co_id');
        $con_state = $this->request->input('con_state',1);
        if ($con_state = 2){
            $co_list = contract::where('con_name','like','%'.$name.'%')->get();
            foreach ($co_list as $con){
                $con['con_img'] = env('APP_URL').'/img/img/'.$con['con_img'];
            }
            return $this->returnMessage($co_list);
        }
        $co_list = contract::where('con_name','like','%'.$name.'%')
            ->where('co_id',$co_id)
            ->where('con_state',$con_state)
            ->get();
        foreach ($co_list as $con){
            $con['con_img'] = env('APP_URL').'/img/img/'.$con['con_img'];
        }
        return $this->returnMessage($co_list);

    }
    /**
     * add
     */
    public function con_add(){
        $d = $this->request->getContent();
        $d = json_decode($d,true);
        $img = implode(',',$d['con_img']);
            $contract = new contract();
            $contract->con_name = $d['con_name'];
            $contract->con_content = $d['con_content'];
            $contract->con_state = $d['con_state'];
            $contract->con_ower = $d['bbs_ower'];
            $contract->con_lead = $d['con_lead'];
            $contract->co_id = $d['co_id'];
            $contract->con_img = $img;
            $contract->save();
            return $this->returnMessage('','ok');

        }
    /**
     * edit
     */
    public function con_edit(){
        $id = $this->request->input('id');
//todo 图片删除问题
        $company = contract::find($id);
        $company->con_name = $this->request->input('con_name');
        $company->con_img = $this->request->input('con_img');
        $company->con_state = $this->request->input('con_state');
        $company->con_content = $this->request->input('con_content');
        $company->con_lead = $this->request->input('con_lead');
        $company->con_ower = $this->request->input('con_ower');

        $company->save();
        return $this->returnMessage('','ok');
    }
    /**
     * detil
     */
    public function con_detil(){
        $id = $this->request->input('id');
        $con_detil = contract::where('id',$id)->get()->toArray();
        if ($con_detil[0]['con_img'] != null){
            $img = explode(',',$con_detil[0]['con_img']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/' .$iv;
            }
            $con_detil[0]['con_img'] =  $img_arr;
            return $this->returnMessage($con_detil);
        }else{
            return $this->returnMessage($con_detil);

        }
    }
    /**
     *list
     */
    public function con_list(){
        //获取到当前currentpage 和perpage 每页多少条
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $co_id = $this->request->input('co_id');
        $date = $this->request->input('date');
        $con_state = $this->request->input('con_state');
        if ($co_id != null and $con_state != null){
            switch ($date){
                case 0:
                    $start = Carbon::now()->startOfDay();
                    $end = Carbon::now()->endOfDay();
                    $list = $this->con_date_list($co_id,$con_state,$currentPage,$perage,$start,$end);
                    return $this->returnMessage($list);
                case 1:
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $list = $this->con_date_list($co_id,$con_state,$currentPage,$perage,$start,$end);
                    return $this->returnMessage($list);
                case 2:
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    $list = $this->con_date_list($co_id,$con_state,$currentPage,$perage,$start,$end);
                    return $this->returnMessage($list);
                default:
                    $list = $this->con_date_list($co_id,$con_state,$currentPage,$perage);
                    return $this->returnMessage($list);
            }
        }else{
        return $this->returnMessage('','请选择公司和状态');
    }
    }
    private function con_date_list($co_id,$con_state,$currentPage,$perage,$start = null,$end = null){
        $limitprame = ($currentPage -1) * $perage;
        if ($start == null){
            $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->skip($limitprame)->take($perage)->get();
        }else{
            $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->whereBetween('created_at',[$start,$end])->skip($limitprame)->take($perage)->get();
        }
        $con_count = contract::all()->count();
        $all = ceil($con_count/$perage);
        foreach ($con_list as $con) {
            $img = explode(',',$con['con_img']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/' .$iv;
            }
            $con['con_img'] =  $img_arr;
        }
        return $con_list;
    }
    /**
     * 删除
     */
    public function con_delete(){
        $id = $this->request->input('con_id');
        try {
            contract::destroy($id);
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * 合同审核
     * 只有改合同的审批领导可以操作
     */
    public function con_access(){
        $con_id = $this->request->input('con_id');
        $state = $this->request->input('con_state');
        $con = contract::find($con_id);
        $con_lead_id = $con->con_lead;
        $user_id = Auth::id();
        if ($user_id != $con_lead_id){
            return $this->returnMessage('','对不起，没有权限');
        }
        $con->con_state = $state;
        $con->save();
        return $this->returnMessage('','ok');
    }
    /**
     *领导列表
     * 拥有审批合同权限的员工
     */
    public function lead_list(){
        $permission_id = permission::where('p_name','合同审批')->value('id');
        if(empty($permission_id)){
            return $this->returnMessage('','系统没有添加“合同审批”权限');
        }
        $role_id = role_permission::where('p_id',$permission_id)->pluck('role_id');
        $user_id = user_role::whereIn('role_id',$role_id)->pluck('user_id');
        $co_id = $this->request->input('co_id');
        $admin_user = User::findMany($user_id)->toArray();
        $id = [];
        foreach ($admin_user as $k){
            $id[] = $k['id'];
        }
        $admin_list = employee::whereIn('user_id',$id)->where('co_id',$co_id)->get(['id','ee_name']);
        return $this->returnMessage($admin_list);
    }
    //合同所属的公司
    public function con_company(){
        $con_id = $this->request->input('con_id');
        $company = contract::find($con_id)->company()->get(['id','co_name']);
        return $this->returnMessage($company);
    }
}
