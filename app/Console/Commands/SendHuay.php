<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phattarachai\LineNotify\Line;
use App\Models\User;
use App\Services\ApiBase;
use App\Models\Formula;
use App\Models\FormulaValue;

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
        $soidow_default = $this->api_base->soidown();
        $data = [];
        $message_value = '';
        $new_line = '';
        $soidow = array_reverse($soidow_default);
        foreach($soidow as $key => $results){
            if($results->status == 'complete'){
                $result_value = [];
                foreach($results->results as $value){
                    $result_value[$value->result_key] = $value->result_value;
                }
                $data = [
                    'readable_edition' => $results->readable_edition,
                    'result_value' => $result_value
                ];
                if($key != 0){ 
                $new_line = "\n" ;
                } 

                $slug = $results->edition_slug;
                $round = substr($slug, strrpos($slug, '|' )+1);
                $message_value = $message_value.$new_line.'รอบที่ '.$round.' : '.$result_value['3upper'].'-'.$result_value['2under'];
            }
        }
        // $message = $data['readable_edition'] .' สองตัวล่าง :'.$data['result_value']['2under'].' สามตัวบน :'.$data['result_value']['3upper'];
        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    if($message_value != ''){
                        $token = $host->line_token;
                        $line = new Line($token);
                        $line->send("\n".'ผลหวยสอยดาว'."\n".'arawanbet'."\n"."\n".$message_value);
                    }
                }
            }
        }

        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    $guideHuayMessage2under = [];
                    $guideHuayMessage3upper = [];
                    $lastGuideMessage2under = '';
                    $lastGuideMessage3upper = '';
                    $under2 = $soidow_default[0]->results[0]->result_value;
                    $upper3 = $soidow_default[0]->results[1]->result_value;

                    //////  2 Under
                    // $formulaUnder2 = Formula::where('user_id',$host->user_id)->where('type',2)->where('condition',0)->with('values')->get();
                    // foreach($formulaUnder2 as $dataUnder2){
                    //     if($dataUnder2->values){
                    //         $guideHuayCheck2Under = FormulaValue::where('formula_id',$dataUnder2->id)->where('value',$under2)->inRandomOrder()->first();
                    //         if($guideHuayCheck2Under){
                    //             $guideHuayMessage2under[] = $dataUnder2->result;
                    //         }
                    //     }
                    // }


                    // if($lastGuideMessage2under != ''){
                    //     $token = $host->line_token;
                    //     $line = new Line($token);
                    //     $line->send($lastGuideMessage2under);
                    // }

                    $formulas = Formula::where('user_id',$host->user_id)->where('condition',0)->with('values')->get();
                    foreach($formulas as $formula){
                        if($formula->type == 3){
                            if($formula->values){
                                $guideHuayCheck3Upper = FormulaValue::where('formula_id',$formula->id)->where('value',$upper3)->inRandomOrder()->first();
                                if($guideHuayCheck3Upper){
                                    $guideHuayMessage3upper[] = $formula->result;
                                }
                            }
                        }
                        if($formula->type == 2){
                            if($formula->values){
                                $guideHuayCheck2Under = FormulaValue::where('formula_id',$formula->id)->where('value',$under2)->inRandomOrder()->first();
                                if($guideHuayCheck2Under){
                                    $guideHuayMessage2under[] = $formula->result;
                                }
                            }
                        }
                    }

                    if(!empty($guideHuayMessage3upper)){
                        $guild3upper = array_rand($guideHuayMessage3upper);
                        $guildRandom3upper = $guideHuayMessage3upper[$guild3upper];
                        $lastGuideMessage3upper = "\n".'พบสามตัวบน เลข '.$upper3."\n".'คำแนะนำ : '.$guildRandom3upper;
                    }

                    if(!empty($guideHuayMessage2under)){
                        $guild2under = array_rand($guideHuayMessage2under);
                        $guildRandom2under = $guideHuayMessage2under[$guild2under];
                        $lastGuideMessage2under = "\n".'พบสองตัวล่าง เลข '.$under2."\n".'คำแนะนำ : '.$guildRandom2under;
                    }

                    if($lastGuideMessage2under != ''){
                        $token = $host->line_token;
                        $line = new Line($token);
                        $line->send($lastGuideMessage2under);
                    }

                    if($lastGuideMessage3upper != ''){
                        $token = $host->line_token;
                        $line = new Line($token);
                        $line->send($lastGuideMessage3upper);
                    }

                    $formulaUnder2condition = Formula::where('user_id',$host->user_id)->where('condition',1)->with('values')->get();
                    foreach($formulaUnder2condition as $under2condition){
                        $number = [];
                        if($under2condition->values){
                            $guideHuayCheck2UnderConditionsMessages = '';
                            $inrounds = array_slice($soidow_default, 0, $under2condition->last_round);
                            if($under2condition->type == 2){
                                foreach($inrounds as $inround){
                                    $number[] = $inround->results[0]->result_value;
                                }
                                $luckynumber = $under2;
                            }
                            if($under2condition->type == 3){
                                foreach($inrounds as $inround){
                                    $number[] = $inround->results[1]->result_value;
                                }
                                $luckynumber = $upper3;
                            }
                            // $number = array_count_values($number);
                            $checkLastNumber = FormulaValue::where('formula_id',$under2condition->id)->where('value',$luckynumber)->first();
                            if($checkLastNumber){
                                $guideHuayCheck2UnderConditions = FormulaValue::where('formula_id',$under2condition->id)->whereIn('value',$number)->count();
                                if($guideHuayCheck2UnderConditions >= $under2condition->round){
                                    $guideHuayCheck2UnderConditionsMessages =  "\n".'คำแนะนำ : '.$under2condition->result;
                                }
                                if($guideHuayCheck2UnderConditionsMessages != ''){
                                    $token = $host->line_token;
                                    $line = new Line($token);
                                    $line->send($guideHuayCheck2UnderConditionsMessages);
                                }
                            }
                        }
                    }

                }
            }
        }
    }
}
