<?php


namespace App\Http\Controllers;


use App\Models\supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function upload_img(){
        $img_name = Str::random(10).'.'.$this->request->file('file')->getClientOriginalExtension();
        $img= $this->request->file('file')->move(env('APP_STORAGE'),$img_name);
        if ($img){
            return $this->returnMessage([$img_name,env('APP_URL') . '/img/img/'.$img_name]);
        }else{
            return $this->returnMessage('','上传失败');
        }
    }

    public function supplier_img(){
        $img_name = Str::random(10).'.'.$this->request->file('file')->getClientOriginalExtension();
        $img= $this->request->file('file')->move(env('APP_STORAGE').'supplier',$img_name);
        if ($img){
            return $this->returnMessage([$img_name,env('APP_URL') . '/img/img/supplier'.$img_name]);
        }else{
            return $this->returnMessage('','上传失败');
        }
    }

    /**
     * @return array
     * 商品图片上传
     */
    public function goods_img(){
        $img_name = Str::random(10).'.'.$this->request->file('file')->getClientOriginalExtension();
        $img= $this->request->file('file')->move(env('APP_STORAGE').'goods',$img_name);
        if ($img){
            return $this->returnMessage([$img_name,env('APP_URL') . '/img/img/goods'.$img_name]);
        }else{
            return $this->returnMessage('','上传失败');
        }
    }
    /**
     * 供应商图片删除
     * @return array
     */
    public function delete_img(){
        $id = $this->request->input('su_id');
        $img_cert = $this->request->input('img_cert');
        $name = $this->request->input('img_name');
        $path = env('APP_STORAGE').'supplier/'.$name;
        try {
            $bool = unlink($path);
        }catch (\Exception $e){
         return $this->returnMessage($e->getMessage());
        }
        if ($bool){
            $img = supplier::where('id',$id)->value($img_cert);
            $leg = strlen($name);
            $count=strpos($img,$name);
            if ($count == 0){
                $str = substr_replace($img,"",$count,$leg+1);
                $re = supplier::find($id);
                $re->$img_cert = $str;
                $re->save();
            }else{
                $str1 = substr_replace($img,"",$count-1,$leg+1);;
                $re1 = supplier::find($id);
                $re1->$img_cert = $str1;
                $re1->save();
            }
            return $this->returnMessage();
        }else{
            return $this->returnMessage('','图片删除失败');
        }
    }

}
