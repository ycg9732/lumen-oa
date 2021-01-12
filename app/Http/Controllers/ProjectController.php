<?php


namespace App\Http\Controllers;


use App\Models\project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function proj_add(){
        //todo 检测是否重复 待做
        if (empty($this->request->input('co_id'))){
            return $this->returnMessage('','请选择项目所属公司');
        }
        $name = $this->request->input('proj_name');
        $state = $this->request->input('proj_state');
        $sum = $this->request->input('proj_sum');
        $order = $this->request->input('proj_order');
        $co_id = $this->request->input('co_id');
        $member = $this->request->input('proj_member');
//        $has_file = $this->request->hasFile('proj_img');
        $img_name = $this->request->input('proj_img');
//        if ($has_file){
//            $img_name = Str::random(10).'.'.$this->request->file('proj_img')->getClientOriginalExtension();
//            $img= $this->request->file('proj_img')->move(env('APP_STORAGE'),$img_name);
//        }
        $project = new project();
        $project->proj_name = $name;
        $project->proj_state = $state;
        $project->proj_sum = $sum;
        $project->proj_order = $order;
        $project->proj_img = $img_name;
        $project->co_id = $co_id;
        $project->proj_member = $member;
        $project->save();
        return $this->returnMessage('','ok');

    }

    /**
     * @return array
     */
    public function proj_list(){
        $co_id = $this->request->input('co_id');
        if ($co_id){
            $proj_list = project::where('co_id',$co_id)->get();
            return $this->returnMessage($proj_list);
        }
        $proj_list = project::all();
        return $this->returnMessage($proj_list);
    }

    /**
     * 详情
     */
    public function proj_detil(){
        $proj_id = $this->request->input('proj_id');
        $proj = project::find($proj_id)->where('id',$proj_id)->first()->toArray();
            if (!empty($proj['proj_img'])){
                $img = explode(',',$proj['proj_img']);
                foreach ($img as $k1){
                    $proj['img'][] = env('APP_URL').'/img/img/'.$k1;
                }
            }
        return $this->returnMessage($proj);
    }

    /**
     * 编辑
     */
    public function proj_edit(){
        $proj_id = $this->request->input('proj_id');

        $name = $this->request->input('proj_name');
        $state = $this->request->input('proj_state');
        $sum = $this->request->input('proj_sum');
        $order = $this->request->input('proj_order');
//        $co_id = $this->request->input('co_id');
        $img_name = $this->request->input('proj_img');
        $member = $this->request->input('proj_member');
//        $has_file = $this->request->hasFile('proj_img');
//        if ($has_file){
//            $img_name = Str::random(10).'.'.$this->request->file('proj_img')->getClientOriginalExtension();
//            $img= $this->request->file('proj_img')->move(env('APP_STORAGE'),$img_name);
//        }
        $project = project::find($proj_id);
        $project->proj_name = $name;
        $project->proj_state = $state;
        $project->proj_sum = $sum;
        $project->proj_order = $order;
        if ($img_name){
            $project->proj_img = $img_name;
        }
        $project->co_id = $co_id;
        $project->proj_member = $member;
        $project->save();
        return $this->returnMessage('','ok');
    }

    /**
     * 删除
     */
    public function proj_delete(){
        $id = $this->request->input('proj_id');
        $e = project::destroy($id);
        if ($e){
        return $this->returnMessage('','ok');
        }
        return $this->returnMessage('','失败');
    }
}
