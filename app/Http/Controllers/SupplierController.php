<?php


namespace App\Http\Controllers;


use App\Models\employee;
use App\Models\supplier;
use App\Models\User;
use App\Notifications\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use ZipArchive;
//todo 删除按钮关联删除
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
            $level = $this->request->input('level');
            $contact_people = $this->request->input('contact_people');
            $zoon_cert = $this->request->input('zoon_cert');
            $zoon_time = $this->request->input('zoon_time') == ''?null:$this->request->input('zoon_time');
            $food_cert = $this->request->input('food_cert');
            $food_time = $this->request->input('food_time') == ''?null:$this->request->input('food_time');
            $sell_cert = $this->request->input('sell_cert');
            $sell_time = $this->request->input('sell_time') == ''?null:$this->request->input('sell_time');
            $youji_food = $this->request->input('youji_food');
            $youji_time = $this->request->input('youji_time') == ''?null:$this->request->input('youji_time');
            $geo_cert = $this->request->input('geo_cert');
            $geo_time = $this->request->input('geo_time') == ''?null:$this->request->input('geo_time');
            $health_cert = $this->request->input('health_cert');
            $health_time = $this->request->input('health_time') == ''?null:$this->request->input('health_time');
            $green_cert = $this->request->input('green_cert');
            $green_time = $this->request->input('green_time') == ''?null:$this->request->input('green_time');
            $business_cert = $this->request->input('business_cert');
            $co_id = $this->request->input('co_id');
            $fangyi = $this->request->input('fangyi_cert');
            $fangyi_timr = $this->request->input('fangyi_time') == ''?null:$this->request->input('fangyi_time');

            $supplier = new supplier();
          $supplier->user_id = Auth::id();
//            $supplier->user_id = 1;
            $supplier->su_name = $name;
            $supplier->start_time = $start_time;
            $supplier->code = $code;
            $supplier->exp = $exp;
            $supplier->tel = $tel;
            $supplier->contact_people = $contact_people;
            $supplier->zoon_cert = $zoon_cert;
            $supplier->zoon_time = $zoon_time;
            $supplier->food_cert = $food_cert;
            $supplier->level = $level;
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
            $name = $sup['business_cert'];
            $sup['business_cert'] = [];
            $sup['business_cert']['img'] = env('APP_URL') . '/img/img/supplier/' .$name;
            $sup['business_cert']['name'] = $name;
            $sup['business_cert']['cert'] = 'business_cert';
        }
        if ($sup['zoon_cert'] != null){
            $img = explode(',',$sup['zoon_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['zoon_cert'] = [];
            $sup['zoon_cert']['img'] = $img_arr;
            $sup['zoon_cert']['name'] = 'zoon_cert';
        }
        if ($sup['food_cert'] != null){
            $img = explode(',',$sup['food_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['food_cert'] = [];
            $sup['food_cert']['img'] =  $img_arr;
            $sup['food_cert']['name'] ='food_cert';
        }
        if ($sup['sell_cert'] != null){

            $img = explode(',',$sup['sell_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['sell_cert'] = [];
            $sup['sell_cert']['img'] =  $img_arr;

            $sup['sell_cert']['name'] ='sell_cert';
        }
        if ($sup['youji_food'] != null){

            $img = explode(',',$sup['youji_food']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['youji_food'] = [];
            $sup['youji_food']['img'] =  $img_arr;

            $sup['youji_food']['name'] ='youji_food';
        }
        if ($sup['geo_cert'] != null){

            $img = explode(',',$sup['geo_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['geo_cert'] = [];
            $sup['geo_cert']['img'] =  $img_arr;

            $sup['geo_cert']['name'] ='geo_cert';
        }
        if ($sup['health_cert'] != null){

            $img = explode(',',$sup['health_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['health_cert'] = [];
            $sup['health_cert']['img'] =  $img_arr;

            $sup['health_cert']['name'] ='health_cert';
        }
        if ($sup['green_cert'] != null){

            $img = explode(',',$sup['green_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['green_cert'] = [];
            $sup['green_cert']['img'] =  $img_arr;

            $sup['green_cert']['name'] ='green_cert';
        }
        if ($sup['fangyi_cert'] != null){

            $img = explode(',',$sup['fangyi_cert']);
            $img_arr = [];
            foreach ($img as $ik => $iv){
                $img_arr[] = env('APP_URL') . '/img/img/supplier/' .$iv;
            }
            $sup['fangyi_cert'] = [];
            $sup['fangyi_cert']['img'] =  $img_arr;

            $sup['fangyi_cert']['name'] ='fangyi_cert';
        }
        return $this->returnMessage($sup);

    }
    /**
     *供应商编辑
     */
    public function supplier_edit(){
        try {
            $su_id = $this->request->input('id');
            $name = $this->request->input('su_name');
            $start_time = $this->request->input('start_time');
            $code = $this->request->input('code');
            $exp = $this->request->input('exp');
            $tel = $this->request->input('tel');
            $level = $this->request->input('level');
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

            $supplier = supplier::find($su_id);
            $supplier->user_id = Auth::id();
//            $supplier->user_id = 1;
            $supplier->su_name = $name;
            $supplier->start_time = $start_time;
            $supplier->code = $code;
            $supplier->exp = $exp;
            $supplier->tel = $tel;
            $supplier->level = $level;
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
     * 供应商列表
     */
    public function supplier_list(){
        $currentPage = (int)$this->request->input('current_page','1');
        $perage = (int)$this->request->input('perpage','20');
        $limitprame = ($currentPage -1) * $perage;
        $co_id = $this->request->input('co_id','81');
        $supplier_list = supplier::where('co_id',$co_id)->skip($limitprame)->take($perage)->get(['su_name','level','user_id','created_at','id']);
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
    //todo 图片删除问题
    public function supplier_delete(){
        $id = $this->request->input('id');
        $ids = explode(',',$id);
        try {
            supplier::destroy($ids);
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * laravel-excel3导出
     * 图片文件不能重复
     * 取相关图片类的第一个图片文件展示
     */
    //todo  需要优化存储图片的时候  每个图片类别 第一个图片强制为证书正面照  或者要求只上传一个图片
    public function supplier_excel(){
        $su_id = $this->request->input('su_id');
        $sup_arr = [
            ['供应商名称','成立日期','统一社会信用代码','营业执照有效期','动物检验检疫证明','食品生产经营许可证','销售授权书','有机食品证明','农产品地理标志','无公害农产品认领书','绿色食品认证证书','动物防疫合格证','经营许可证'],
        ];
        $sup = supplier::where('id',$su_id)->get(['su_name','start_time','code','exp'])->toArray();
        foreach ($sup as $k => $v){
            $sup_arr[] = array_values($v);
        }
        $img = supplier::where('id',$su_id)->get(['business_cert','zoon_cert','food_cert','sell_cert','youji_food','geo_cert','health_cert','green_cert','fangyi_cert'])->toArray();
        $i = 2;
        $drawings = [];
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['zoon_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('E'.$i);
            $drawings[] = ${$i};
            $i += 1;
        }
        $i1 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['food_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('F'.$i1);
            $drawings[] = ${$i};
            $i1 += 1;
        }
            $i2 = 2;
            foreach ($img as $k => $v){
                $img_name = explode(',',$v['sell_cert']);
                if (empty($img_name[0])) break;
                $$i = new Drawing();
                $$i->setName('Logo');
                $$i->setDescription('This is my logo');
                $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
                $$i->setHeight(50);
                $$i->setCoordinates('G'.$i2);
                $drawings[] = ${$i};
                $i2 += 1;
            }

        $i3 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['youji_food']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('H'.$i3);
            $drawings[] = ${$i};
            $i3 += 1;
        }

        $i4 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['geo_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('I'.$i4);
            $drawings[] = ${$i};
            $i4 += 1;
        }

        $i5 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['health_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('J'.$i5);
            $drawings[] = ${$i};
            $i5 += 1;
        }

        $i6 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['green_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('K'.$i6);
            $drawings[] = ${$i};
            $i6 += 1;
        }


        $i7 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['fangyi_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('L'.$i7);
            $drawings[] = ${$i};
            $i7 += 1;
        }


        $i8 = 2;
        foreach ($img as $k => $v){
            $img_name = explode(',',$v['business_cert']);
            if (empty($img_name[0])) break;
            $$i = new Drawing();
            $$i->setName('Logo');
            $$i->setDescription('This is my logo');
            $$i->setPath(env('APP_STORAGE').'supplier/'.$img_name[0]);
            $$i->setHeight(50);
            $$i->setCoordinates('M'.$i8);
            $drawings[] = ${$i};
            $i8 += 1;
        }

        $export = new ExcelController($sup_arr,$drawings);
        return Excel::download($export, 'supplier.xlsx');
    }

    public function supplier_img_edit(){
        $id = $this->request->input('su_id');
        $img_name = $this->request->input('img_name');
        $img_cert = $this->request->input('img_cert');

    }
    /**
     * 供应商选择接口
     */
    public function supplier_select(){
        try {
            $su = supplier::get(['id','su_name']);
            return $this->returnMessage($su);
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

}
