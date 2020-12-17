<?php


namespace App\Http\Controllers;


use App\Models\company;
use Illuminate\Http\Request;

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
            return 'company exist';
        }else{
            $company = new company;
            $company->co_name = $request->input('co_name');
            $company->co_code = $request->input('co_code');
            $company->save();
            return $this->returnMessage('','ok');
        }
    }
    /**
     *修改详情
     */
    public function co_edit(Request $request){
        $co_id = $request->input('co_id');
        $company = company::find($co_id);
        $company->co_name = $request->input('co_name');
        $company->co_code = $request->input('co_code');
        $company->save();
        return $this->returnMessage('','ok');
    }
    /**
     * 树状结构
     */
    public function tree(){
//        $tree = company::with('allchildren')->first();  //单数结构
        $tree = company::get(['co_name','parent']);//双树结构
        return $this->returnMessage($tree);
    }
}
