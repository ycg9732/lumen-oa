<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function upload_img(){
        $img_name = Str::random(10).'.'.$this->request->file('file')->getClientOriginalExtension();
        $img= $this->request->file('file');
        if ($img){
            return $this->returnMessage('',$img_name);
        }else{
            return $this->returnMessage('','上传失败');
        }
    }
}
