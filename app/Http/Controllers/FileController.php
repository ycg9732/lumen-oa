<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
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

}
