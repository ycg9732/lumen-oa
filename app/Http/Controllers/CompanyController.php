<?php


namespace App\Http\Controllers;


use App\Models\company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * 搜索接口(名称搜索)
     */
    public function search(Request $request){
        $name = $request->input('co_name');
        $co_list = company::where('co_name','like','%'.$name.'%')->get();
        return $this->returnMessage($co_list);
    }
    /**
     *公司列表接口
     */
    public function co_list(Request $request){
        //获取到当前currentpage 和perpage 每页多少条
        $currentPage = (int)$request->input('current_page','1');
        $perage = (int)$request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $co_list = company::skip($limitprame)->take($perage)->get();
        $co_count = company::all()->count();
        $all = ceil($co_count/$perage);
        return $this->returnMessage($co_list);
    }
    /**
     * 公司详情
     */
    public function co_detil(Request $request){
        $co_id = $request->input('co_id');
        $co_info = company::where('id',$co_id)->get();
        return $this->returnMessage($co_info);
    }
    /**
     * 添加公司
     */
    public function co_add(Request $request){
        $code = company::where('co_code',$request->input('co_code'))->count();
        if ($code > 0){
            return $this->returnMessage('','company exist');
        }else {
            $company = new company;
            $company->co_name = $request->input('co_name');
            $company->co_code = $request->input('co_code');
            $company->corpn = $request->input('corpn');
            $company->state = $request->input('state');
            $company->cap = $request->input('cap');
            $company->lob = $request->input('lob');
            $company->credit_code = $request->input('credit_code');
            $company->jiguan = $request->input('jiguan');
            $company->start = $request->input('start');
            $company->area = $request->input('area');
            $company->address = $request->input('address');
            $company->fanwei = $request->input('fanwei');
            $company->p_id = $request->input('parent');
            $company->distribution = $request->input('distribution');
            $company->is_right = $request->input('is_right','1');
            $company->save();
            return $this->returnMessage('','ok');
        }
    }
    /**
     *修改详情
     */
    public function co_edit(Request $request){
        $user = Auth::user();
        $can = $user->has('查看');
        if ($can == 0){
            return $this->returnMessage('','对不起没有相应的权限');
        }
        $co_id = $request->input('co_id');
        $company = company::find($co_id);
        $company->co_name = $request->input('co_name');
        $company->co_code = $request->input('co_code');
        $company->corpn = $request->input('corpn');
        $company->state = $request->input('state');
        $company->cap = $request->input('cap');
        $company->lob = $request->input('lob');
        $company->credit_code = $request->input('credit_code');
        $company->jiguan = $request->input('jiguan');
        $company->start = $request->input('start');
        $company->area = $request->input('area');
        $company->address = $request->input('address');
        $company->fanwei = $request->input('fanwei');
        $company->distribution = $request->input('distribution');
        $company->save();
        return $this->returnMessage('','ok');
    }
    /**
     * 删除接口
     */
    public function co_delete(Request $request){
        $id = $request->input('id');
        try {
            DB::transaction(function () use ($id){
                company::destroy($id);
                //todo 关联删除
            },2);
            return $this->returnMessage('','ok');
        }catch (\PDOException $e){
            return $this->returnMessage('','no');
        }
    }
    /**
     * 树状结构
     */
    public function tree(){
//        $right_tree = company::where('is_right','1')->get();  //单树结构
        $r = company::with('children')->where('is_right','1')->first();
        $l = company::with('children')->where('is_right','2')->first();
        if ($r or $l){
            if ($r and $l){
                $right_tree = $r->toArray();  //单树结构
                $r_tree = $this->get_tree($right_tree);
                $left_tree = $l->toArray();  //单树结构
                $l_tree = $this->get_tree($left_tree);
                return $this->returnMessage(['r'=>$r_tree,'l'=>$l_tree]);
            }
            if ($r){
                $right_tree = $r->toArray();  //单树结构
                $r_tree = $this->get_tree($right_tree);
                return $this->returnMessage(['r'=>$r_tree,'l'=>'']);
            }
            if ($l){
                $left_tree = $l->toArray();  //单树结构
                $l_tree = $this->get_tree($left_tree);
                return $this->returnMessage(['r'=>'','l'=>$l_tree]);
            }
        }
    }
    /**
     * 单选框接口
     */
    public function co_select(){
        $select = company::get(['id','co_name']);
        return $this->returnMessage($select);
    }

    /**
     * 递归
     * @param $right_tree
     * @return mixed
     */
    public function get_tree(&$right_tree){
        $tree = $right_tree;
        foreach ($tree as $k => $v) {
            if (is_array($tree[$k])) {
                $tree[$k] = $this->get_tree($tree[$k]);
            } else {
                if ($k != 'co_name'){
                    unset($tree[$k]);
                }else{
                    $tree['name'] = !empty(company::where('co_name',$tree[$k])->value('distribution'))?$tree[$k].'('.company::where('co_name',$tree[$k])->value('distribution').')':$tree[$k];
                    unset($tree[$k]);
                }
            }
        }
        return $tree;
    }


    /**
     * 每个公司的所有部门
     */
    public function de_per_co(Request $request){
        $co_id = $request->input('id');
        $de_list = company::find($co_id)->department()->get(['dept_name','id']);
        return $this->returnMessage($de_list);
    }
}
