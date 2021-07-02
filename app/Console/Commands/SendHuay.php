<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phattarachai\LineNotify\Line;
use App\Models\User;
use App\Services\ApiBase;

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
        $message = $data['readable_edition'] .' สองตัวล่าง :'.$data['result_value']['2under'].' สามตัวบน :'.$data['result_value']['3upper'];
        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    $token = $host->line_token;
                    $line = new Line($token);
                    $line->send($message);
                }
            }
        }
    }
}
