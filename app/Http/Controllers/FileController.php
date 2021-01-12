<?php


namespace App\Http\Controllers;


use App\Models\archives;
use App\Models\goods_cert;
use App\Models\goods_img;
use App\Models\project;
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

    /**
     * @return array
     * 供应商图片上传
     */
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

    /**
     * 商品图片删除
     */
    public function goods_img_delete(){
        $good_id = $this->request->input('good_id');
        if ($this->request->has('img_name')){
            try {
                unlink(env('APP_STORAGE').'goods/'.$this->request->input('img_name'));
            }catch (\Exception $e){
                return $this->returnMessage($e->getMessage());
            }
            try {
                $img = goods_img::where('good_id',$good_id)->where('good_img',$this->request->input('img_name'));
                $img->delete();
            }catch (\PDOException $e){
                return $this->returnMessage($e->getMessage());
            }
        }
        if ($this->request->has('cert_name')){
            try {
                unlink(env('APP_STORAGE').'goods/'.$this->request->input('cert_name'));
            }catch (\Exception $e){
                return $this->returnMessage($e->getMessage());
            }
            try {
                $img1 = goods_cert::where('good_id',$good_id)->where('good_cert',$this->request->input('cert_name'));
                $img1->delete();
            }catch (\PDOException $e){
                return $this->returnMessage($e->getMessage());
            }

        }
        return $this->returnMessage();
    }

    /**
     * by you
     * 员工档案图片上传
     */
    public function arch_img_add(){
        $img_name = Str::random(10).'.'.$this->request->file('file')->getClientOriginalExtension();
        $img= $this->request->file('file')->move(env('APP_STORAGE').'arch',$img_name);
        if ($img){
            return $this->returnMessage([$img_name,env('APP_URL') . '/img/img/arch'.$img_name]);
        }else{
            return $this->returnMessage('','上传失败');
        }

    }

    /**
     * by you
     * 员工档案图片删除
     */
    public function arch_img_delete(){
        $id = $this->request->input('id');
        try {
            $arch = archives::find($id);
            $arch->img = null;
            $arch->save();
            return $this->returnMessage();
        }catch (\PDOException $e){
            return $this->returnMessage($e->getMessage());
        }
    }

    /**
     * by you
     * 项目管理图片删除
     */
    public function proj_img_delete(){
        $img_name = $this->request->input('img_name');
        $id = $this->request->input('id');
        $img = project::where('id',$id)->value('proj_img');
        $leg = strlen($img_name);
        $count=strpos($img,$img_name);
        if ($count == 0){
            $str = substr_replace($img,"",$count,$leg+1);
            $re = project::find($id);
            $re->proj_img = $str;
            $re->save();
        }else{
            $str1 = substr_replace($img,"",$count-1,$leg+1);;
            $re1 = project::find($id);
            $re1->proj_img = $str1;
            $re1->save();
        }
        return $this->returnMessage();

    }

}
