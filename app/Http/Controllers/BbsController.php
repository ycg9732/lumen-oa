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
        $bbs->co_id = $this->request->input('co_id');
        $bbs->save();
        return $this->returnMessage('','ok');
    }

    /**
     * @return array
     */
    public function see_bbs(){
        $bbs_id = $this->request->input('id');
        $bbs = bbs::find($bbs_id)->get();
        return $this->returnMessage($bbs);

    }
    public function bbs_list(){
        $co_id = $this->request->input('co_id');
        $bbs_list = bbs::where('co_id',$co_id)->get();
        return $this->returnMessage($bbs_list);
    }
}