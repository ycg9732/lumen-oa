<?php


namespace App\Http\Controllers;


use App\Models\employee;
use App\Models\role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\contract;
use Illuminate\Http\Request;

/**
 * Class ContractController
 * @package App\Http\Controllers
 */
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
        $con_detil = contract::where('id',$id)->get();
        foreach ($con_detil as $con){
            $con['con_img'] = env('APP_URL') . '/img/img/' . $con['con_img'];
        }
        return $this->returnMessage($con_detil);
    }
    /**
     *list
     */
    public function con_list(){
        $co_id = $this->request->input('co_id');
        $con_state = $this->request->input('con_state');
    if ($co_id != null and $con_state != null){
        //获取到当前currentpage 和perpage 每页多少条
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->skip($limitprame)->take($perage)->get();
//        $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->get();
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
        return $this->returnMessage($con_list);
        }else{
        return $this->returnMessage([$co_id,$con_state],'请选择公司和状态');
    }
    }
    /**
     * 删除
     */
    public function con_delete(){

    }

    /**
     * 合同审核
     */
    public function con_access(){
        $con_id = $this->request->input('con_id');
        $state = $this->request->input('con_state');
        $con = contract::find($con_id);
        $con->con_state = $state;
        $con->save();
        return $this->returnMessage('','ok');
    }
    /**
     *领导列表
     * 为管理员的员工
     */
    public function lead_list(){
        $co_id = $this->request->input('co_id');
        $admin_user = role::find(1)->user->toArray();
        $id = [];
        foreach ($admin_user as $k){
            $id[] = $k['id'];
        }
        $admin_list = employee::whereIn('user_id',$id)->where('co_id',$co_id)->get(['id','ee_name']);
        return $this->returnMessage($admin_list);
    }
}
