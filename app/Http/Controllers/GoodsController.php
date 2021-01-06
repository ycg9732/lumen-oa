<?php


namespace App\Http\Controllers;


use App\Models\category;
use App\Models\goods;
use App\Models\goods_cert;
use App\Models\goods_img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{

    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    /**
     * 商品添加
     */
    //todo 接收数据待优化
    public function goods_add(){
        $req = [];
        $req['title'] = $this->request->input('title');
        $req['su_id'] = $this->request->input('su_id');
        $req['good_code'] = $this->request->input('good_code');
        $req['maozhong'] = $this->request->input('maozhong');
        $req['length'] = $this->request->input('length');
        $req['width'] = $this->request->input('width');
        $req['height'] = $this->request->input('height');
        $req['from'] = $this->request->input('from');
        $req['ca_id'] = $this->request->input('ca_id');
        $req['biaozhun'] = $this->request->input('biaozhun');
        $req['net_weight'] = $this->request->input('net_weight');
        $req['price'] = $this->request->input('price');
        $req['prod_code'] = $this->request->input('prod_code');
        $req['good_img'] = $this->request->input('good_img');
        $req['good_cert'] = $this->request->input('good_cert');
        try {
            DB::transaction(function () use ($req){
                $good = new goods();
                $good->title = $req['title'];
                $good->su_id = $req['su_id'];
                $good->good_code = $req['good_code'];
                $good->maozhong = $req['maozhong'];
                $good->length = $req['length'];
                $good->width = $req['width'];
                $good->height = $req['height'];
                $good->from = $req['from'];
                $good->ca_id = $req['ca_id'];
                $good->biaozhun = $req['biaozhun'];
                $good->net_weight = $req['net_weight'];
                $good->price = $req['price'];
                $good->prod_code = $req['prod_code'];
                $good->save();
                $good_id = $good->id;
                $img = explode(',',$req['good_img']);
                if (!empty($img[0])){
                    foreach ($img as $k => $v){
                        $good_img = new goods_img();
                        $good_img->good_id = $good_id;
                        $good_img->good_img = $v;
                        $good_img->save();
                    }
                }
                $cert = explode(',',$req['good_cert']);
                if (!empty($cert[0])){
                    foreach ($cert as $k => $v){
                        $good_cert = new goods_cert();
                        $good_cert->good_id = $good_id;
                        $good_cert->good_cert = $v;
                        $good_cert->save();
                    }
                }
            });
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * 商品列表
     */
    public function goods_list(){

    }

    /**
     * 商品删除
     */
    public function goods_delete(){

    }

    /**
     * 商品更改
     */
    public function goods_edit(){

    }

    /**
     * 商品详情
     */
    public function goods_detail(){

    }
    /**
     * 商品选择分类
     */
    public function cat_select(){
        try {
            $cat = category::get(['id','cat_name']);
            return $this->returnMessage($cat);
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }
}
