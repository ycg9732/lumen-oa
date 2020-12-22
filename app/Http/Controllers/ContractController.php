<?php


namespace App\Http\Controllers;


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
        $is_have = contract::where('con_name',$this->request->input('con_name'))->count();
        if($is_have > 0){
            return $this->returnMessage('','contract exist');
        }else{
            $date = $this->request->input('con_date');
            $name = $this->request->input('con_name');
            $state = $this->request->input('con_state');
            $img_name = Str::random(10).'.'.$this->request->file('con_img')->getClientOriginalExtension();
            $img= $this->request->file('con_img')->move(env('APP_STORAGE'),$img_name);
            $contract = new contract();
            $contract->con_name = $name;
            $contract->con_date = $date;
            $contract->con_state = $state;
            $contract->con_img = $img_name;
            $contract->co_id = $this->request->input('co_id');

            $contract->save();
            return $this->returnMessage('','success');

        }
    }
    /**
     * edit
     */
    public function con_edit(){
        $id = $this->request->input('id');
//todo 图片删除问题
        $company = contract::find($id);
        $company->con_name = $this->request->input('con_name');
        $company->con_date = $this->request->input('con_date');
        $company->con_state = $this->request->input('con_state');
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
    if ($co_id && $con_state){
        //获取到当前currentpage 和perpage 每页多少条
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->skip($limitprame)->take($perage)->get();
//        $con_list = contract::where('con_state',$con_state)->where('co_id',$co_id)->get();
        $con_count = contract::all()->count();
        $all = ceil($con_count/$perage);

        foreach ($con_list as $con) {
            $con['con_img'] = env('APP_URL') . '/img/img/' . $con['con_img'];
        }
        return $this->returnMessage($con_list);
        }else{
        return $this->returnMessage('','请选择公司和状态');
    }
    }
    /**
     * 删除
     */
    public function con_delete(){

    }
}
