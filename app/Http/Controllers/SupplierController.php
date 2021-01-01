<?php


namespace App\Http\Controllers;


use App\Models\employee;
use App\Models\supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use ZipArchive;

class SupplierController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * 供应商添加
     */
    public function supplier_add(){
        try {
            $name = $this->request->input('su_name');
            $start_time = $this->request->input('start_time');
            $code = $this->request->input('code');
            $exp = $this->request->input('exp');
            $tel = $this->request->input('tel');
            $contact_people = $this->request->input('contact_people');
            $zoon_cert = $this->request->input('zoon_cert');
            $zoon_time = $this->request->input('zoon_time');
            $food_cert = $this->request->input('food_cert');
            $food_time = $this->request->input('food_time');
            $sell_cert = $this->request->input('sell_cert');
            $sell_time = $this->request->input('sell_time');
            $youji_food = $this->request->input('youji_food');
            $youji_time = $this->request->input('youji_time');
            $geo_cert = $this->request->input('geo_cert');
            $geo_time = $this->request->input('geo_time');
            $health_cert = $this->request->input('health_cert');
            $health_time = $this->request->input('health_time');
            $green_cert = $this->request->input('green_cert');
            $green_time = $this->request->input('green_time');
            $business_cert = $this->request->input('business_cert');
            $co_id = $this->request->input('co_id');
            $fangyi = $this->request->input('fangyi_cert');
            $fangyi_timr = $this->request->input('fangyi_time');

            $supplier = new supplier();
            //todo 获取用户的id
            $supplier->user_id = 1;
            $supplier->su_name = $name;
            $supplier->start_time = $start_time;
            $supplier->code = $code;
            $supplier->exp = $exp;
            $supplier->tel = $tel;
            $supplier->contact_people = $contact_people;
            $supplier->zoon_cert = $zoon_cert;
            $supplier->zoon_time = $zoon_time;
            $supplier->food_cert = $food_cert;
            $supplier->food_time = $food_time;
            $supplier->sell_cert = $sell_cert;
            $supplier->sell_time = $sell_time;
            $supplier->youji_food = $youji_food;
            $supplier->youji_time = $youji_time;
            $supplier->geo_cert = $geo_cert;
            $supplier->geo_time = $geo_time;
            $supplier->health_cert = $health_cert;
            $supplier->health_time = $health_time;
            $supplier->green_cert = $green_cert;
            $supplier->green_time = $green_time;
            $supplier->business_cert = $business_cert;
            $supplier->co_id = $co_id;
            $supplier->fangyi_cert = $fangyi;
            $supplier->fangyi_time = $fangyi_timr;

            $supplier->save();
            return $this->returnMessage('','ok');

        }catch (\PDOException $e){
            return $this->returnMessage('',$e->getMessage());
        }
    }

    /**
     *供应商详情
     */
    public function supplier_detil(){
        $id = $this->request->input('sup_id');
        $sup = supplier::find($id)->toArray();
        $sup['created_at'] = substr($sup['created_at'],0,10);
        unset($sup['updated_at']);
        if ($sup['business_cert'] != null){
            $sup['business_cert'] = env('APP_URL') . '/img/img/supplier/' .$sup['business_cert'];
        }
        if ($sup['zoon_cert'] != null){
            $img = explode(',',$sup['zoon_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['zoon_cert'] =  $img_arr;
        }
        if ($sup['food_cert'] != null){
            $img = explode(',',$sup['food_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['food_cert'] =  $img_arr;
        }
        if ($sup['sell_cert'] != null){

            $img = explode(',',$sup['sell_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['sell_cert'] =  $img_arr;

        }
        if ($sup['youji_food'] != null){

            $img = explode(',',$sup['youji_food']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['youji_food'] =  $img_arr;
        }
        if ($sup['geo_cert'] != null){

            $img = explode(',',$sup['geo_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['geo_cert'] =  $img_arr;
        }
        if ($sup['health_cert'] != null){

            $img = explode(',',$sup['health_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['health_cert'] =  $img_arr;
        }
        if ($sup['green_cert'] != null){

            $img = explode(',',$sup['green_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['green_cert'] =  $img_arr;
        }
        if ($sup['fangyi_cert'] != null){

            $img = explode(',',$sup['fangyi_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['fangyi_cert'] =  $img_arr;
        }
        return $this->returnMessage($sup);

    }

    /**
     *供应商编辑
     *
     */
    //todo 待优化
    public function supplier_edit(){

    }

    /**
     * 供应商列表
     */
    public function supplier_list(){
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $supplier_list = supplier::skip($limitprame)->take($perage)->get(['su_name','level','user_id','created_at','id']);
        $su_count = supplier::all()->count();
        $all = ceil($su_count/$perage);
        foreach ($supplier_list as $sup){
            if ($sup['user_id'] != null){
                $sup['user'] = employee::where('user_id',$sup['user_id'])->value('ee_name');
            }else{
                $sup['user'] = null;
            }
            $sup['number'] = supplier::find($sup['id'])->goods()->count();
            $sup['created'] = substr($sup['created_at'],0,10);
            unset($sup['created_at']);
        }
        return $this->returnMessage($supplier_list);
    }

    /**
     * 删除
     */
    public function supplier_delete(){

    }

    /**
     * excel导出
     */
    public function supplier_excel(){
        $sup_arr = [
            ['供应商名称','成立日期','统一社会信用代码','营业执照有效期','测试图片'],
        ];
        $sup = supplier::get(['su_name','start_time','code','exp'])->toArray();
        foreach ($sup as $k => $v){
            $sup_arr[] = array_values($v);
        }
        $img = supplier::get(['zoon_cert'])->toArray();
        $i = 2;
        $drawings = [];
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['zoon_cert']);
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath('D:\project\manageCompanyService\storage\img\\'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('E'.$i);
            $drawings[] = ${$i};
            $i += 1;
        }
        $export = new ExcelController($sup_arr,$drawings);
        $bool = Excel::store($export, 'test8.xlsx');
        if (!$bool){
            $this->returnMessage('','导出失败');
        }
        return $this->returnMessage();
    }
}
