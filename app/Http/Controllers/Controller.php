<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param null $message
     * @param array $data
     * @param bool $status
     * @param int $code
     * @return array
     */
    public function returnMessage($message=null,$data=[],$status=true,$code=400200){
        return ['status'=>$status,'message'=>$message,'data'=>$data,'code'=>$code];
    }
}
