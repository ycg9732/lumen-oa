<?php


namespace App\Http\Controllers;


use App\Models\supplier;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

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

            $supplier = new supplier();
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
            $supplier->save();
            return $this->returnMessage('','ok');

        }catch (\PDOException $e){
            return $this->returnMessage('',$e->getMessage());
        }
    }

    /**
     *供应商详情
     */
    public function supplier_detile(){

    }

    /**
     *供应商编辑
     *
     */
    //todo 待优化
    public function supplier_edit(){

    }
}
