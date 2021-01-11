<?php

namespace App\Console;

use App\Models\supplier;
use App\Models\User;
use App\Notifications\notification;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //定时任务每天1点30生成消息通知 规则：证书到期时间小于或者等于当前时间代表过期
        $schedule->call(function (){
            $user = User::find(3);
        $date = supplier::get(['id','su_name','exp','zoon_time','updated_at','food_time','sell_time','youji_time','geo_time','health_time','green_time']);
//            $date = supplier::get(['su_name','exp','id','updated_at']);
            foreach ($date as $k => $v){
                //todo 生成消息的唯一性（生成过的消息还需要重新生成么）
//                $have = $user->Notifications->where('data',$v['data'])->count();
                if (!empty($v['exp']) and strtotime($v['exp']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '营业执照到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['zoon_time']) and strtotime($v['zoon_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '动物检验检疫证明到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['food_time']) and strtotime($v['food_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '食品生产经营许可证到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['sell_time']) and strtotime($v['sell_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '销售授权书到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['youji_time']) and strtotime($v['youji_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '有机食物证明到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['geo_time']) and strtotime($v['geo_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '农产品地理标志到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['health_time']) and strtotime($v['health_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '无公害农产品认领证书到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
                if (!empty($v['green_time']) and strtotime($v['green_time']) <= time()){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '绿色食品认证证书到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
            }
        })->everyMinute();
    }
}
