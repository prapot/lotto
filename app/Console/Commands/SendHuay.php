<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phattarachai\LineNotify\Line;
use App\Models\User;
use App\Services\ApiBase;
use App\Models\Formula;

class SendHuay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendHuay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ส่งข้อความทุก ๆ 10 นาที';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->api_base = new ApiBase();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $soidow = $this->api_base->soidown();
        $data = [];
        foreach($soidow as $results){
            if($results->status == 'complete'){
                $result_value = [];
                foreach($results->results as $value){
                    $result_value[$value->result_key] = $value->result_value;
                }
                $data = [
                    'readable_edition' => $results->readable_edition,
                    'result_value' => $result_value
                ];
            }
        }
        // $message = $data['readable_edition'] .' สองตัวล่าง :'.$data['result_value']['2under'].' สามตัวบน :'.$data['result_value']['3upper'];
        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    $message = '';
                    $message1 = '';
                    $message2 = '';
                    $under2 = $data['result_value']['2under'];
                    $upper3 = $data['result_value']['3upper'];
                    $formulaUnder2 = Formula::where('user_id',$host->user_id)->where('value',$under2)->inRandomOrder()->first();
                    $formulaUpper3 = Formula::where('user_id',$host->user_id)->where('value',$upper3)->inRandomOrder()->first();
                    if($formulaUnder2){
                        $message1 = 'สองตัวล่าง : '.$under2.' สูตร : '.$formulaUnder2->result;
                    }
                    if($formulaUpper3){
                        $message2 = 'สามตัวบน : '.$upper3.' สูตร : '.$formulaUpper3->result;
                    }
                    $message = $message1 . $message2;
                    if($message != ''){
                        $token = $host->line_token;
                        $line = new Line($token);
                        $line->send($message);
                    }
                }
            }
        }
    }
}
