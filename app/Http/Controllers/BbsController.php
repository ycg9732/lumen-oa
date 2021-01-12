<?php


namespace App\Http\Controllers;

use App\Models\bbs;
use App\Models\bbs_company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BbsController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @return array
     */
    //todo 新建消息类型 添加成功后需要生成相应的用户消息
    public function add_bbs(){
        $name = $this->request->input('name');
        $num = bbs::where('bbs_name',$name)->count();
        if ($num > 0){
            return $this->returnMessage('','名称相同：公告已经存在');
        }
        $content = $this->request->input('content');
        $ower = $this->request->input('bbs_ower');
        $co_id = $this->request->input('co_id');
        return $co_id;

        try {
            DB::transaction(function ()use ($name,$content,$ower,$co_id){
                $bbs = new bbs;
                $bbs->bbs_name = $name;
                $bbs->bbs_content = $content;
                $bbs->bbs_ower = $ower;
                $bbs->save();
                $bbs_id = $bbs->id;
                $new_co_id = str_replace('[','',$co_id);
                $l_co_id = str_replace(']','',$new_co_id);
                $co_ids = explode(',',$l_co_id);
                //插入多对多关联表
                foreach ($co_ids as $v){
                    $bbs_co = new bbs_company();
                    $bbs_co->bbs_id = $bbs_id;
                    $bbs_co->co_id = $v;
                    $bbs_co->save();
                }
            },2);
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
        return $this->returnMessage('添加成功','ok');
    }

    /**
     * @return array
     */
    public function see_bbs(){
        $bbs_id = $this->request->input('id');
        $bbs = bbs::find($bbs_id);
        return $this->returnMessage($bbs);

    }
    public function bbs_list(){
        $co_id = $this->request->input('co_id');
        if ($co_id){
            $bbs_list = bbs::where('co_id',$co_id)->get();
            return $this->returnMessage($bbs_list);
        }else{
            return $this->returnMessage('','公司不能为空');
        }
    }
}
