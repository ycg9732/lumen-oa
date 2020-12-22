<?php


namespace App\Http\Controllers;

use App\Models\bbs;
use Illuminate\Http\Request;

class BbsController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function add_bbs(){
        $name = $this->request->input('name');
        $content = $this->request->input('content');
        $bbs = new bbs;
        $bbs->bbs_name = $name;
        $bbs->bbs_content = $content;
        $bbs->save();
        return $this->returnMessage('','ok');
    }

    public function see_bbs(){

    }
}
