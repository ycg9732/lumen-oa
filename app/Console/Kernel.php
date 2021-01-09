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
        //定时任务生成消息通知
        //todo 生成消息的唯一性
        $schedule->call(function (){
            $user = User::find(3);
//        $date = supplier::get(['id',su_name','exp','zoon_time','food_time','sell_time','youji_time','geo_time','health_time','green_time']);
            $date = supplier::get(['su_name','exp','id','updated_at']);
            foreach ($date as $k => $v){
                $have = $user->Notifications->where('data',$v['data'])->count();
                if (!empty($v['exp']) and strtotime($v['exp']) <= time() and $have < 0){
                    $data = ['id' => $v['id'],'name' => $v['su_name'],'msg' => '营业执照到期','updated_at' => $v['updated_at']];
                    $user->notify(new notification($data));
                }
            }
        })->dailyAt('1:00');
    }
}
