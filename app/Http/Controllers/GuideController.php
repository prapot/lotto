<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Formula;
use App\Models\FormulaValue;
use Phattarachai\LineNotify\Line;
use Carbon\Carbon;

class GuideController extends Controller
{
    public function guide(Request $request,$server = null){

        if($server){
            $message = [
                'status' => 200,
                'success' => 'true',
                'message' => 'server test',
            ];
            return $message;
        }

        if(empty($request->all())){
            $message = [
                'status' => 404,
                'success' => 'false',
                'message' => 'data not found',
            ];
            return $message;
        }

        try {
            $datas = $request->all();
            $soidowNormal5 = $datas['soidown_5'] ?? [];
            $soidowNormal10 = $datas['soidown'] ?? [];
            $soidowNormal15 = $datas['soidown_15'] ?? [];
            $soidowVip5 = $datas['soidown_vip'] ?? [];

            $games = config('game');

            $lotto_games = [];

            foreach($games as $g => $game){
              foreach($datas as $key => $data){
                if($g == $key){
                  $lotto_games[$key] = $data;
                }
              }
            }
            if(empty($soidowNormal5) && empty($soidowNormal10) && empty($soidowNormal15) && empty($soidowVip5) && empty($lotto_games)){
                $message = [
                    'status' => 404,
                    'success' => 'false',
                    'message' => 'data not found',
                ];
                return $message;
            }

            if($soidowNormal5){
                $this->normal($soidowNormal5,5);
            }


            if($soidowNormal10){
                $this->normal($soidowNormal10,10);
            }

            if($soidowNormal15){
                $this->normal($soidowNormal15,15);
            }

            if($soidowVip5){
                $this->vip([],$soidowVip5,5);
            }


            if($lotto_games){
                foreach($lotto_games as $slug => $data_game){
                    $this->lotto($data_game,$slug);
                }
            }

            $message = [
                'status' => 200,
                'success' => 'true',
            ];
            return $message;
        } catch (\Throwable $e) {
            $message = [
                'status' => 500,
                'success' => 'false',
                'message' => $e->getMessage()
            ];
            return $message;
        }


    }


    function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม.","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}

    function findStatus($status,$time){
        if($status == null){
            return false;
        }
        $contains = @array_search($time,$status);
        return $contains;
    }

    public function normal($datas,$time){

        $numbers = array_reverse($datas);
        $message_value = '';
        $new_line = '';

        foreach($numbers as $key => $result){
            if($key != 0){
            $new_line = "\n" ;
            }

            $slug = $result['edition_slug'];
            $round = substr($slug, strrpos($slug, '|' )+1);
            $dateround = $this->DateThai(strtok($slug, '|'));
            $close_at = date('H:i', strtotime($result['close_at']));
            $message_value = $message_value.$new_line.$round.' : '.$close_at.' '."\u{1F449}".' '.$result['3upper'].'-'.$result['2under'];

        }

        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    $arrayStatus = json_decode($host->status);

                    $checked = $this->findStatus($arrayStatus,$time);

                    if($message_value != '' && $checked !== false){
                        try {
                            $handEmoji = "\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}";
                            $textLine = "------------------------------------";
                            $lastHuay = "ผลสอยดาว ARAWAN - ".$time." นาที"."\n"."ล่าสุดรอบที่ ".$round.' '."\u{1F449}".' '.$result['3upper'].'-'.$result['2under'];
                            $messageResult = "\n".$handEmoji."\n".'ผลหวยสอยดาว'."\n".'arawanbet - '.$time.' นาที'."\n".$dateround."\n".$handEmoji."\n\n".$message_value."\n\n".$textLine."\n\n".$lastHuay."\n\n".$textLine."\n\n".$dateround;
                            $token = $host->line_token;
                            $line = new Line($token);
                            $line->send($messageResult);
                            Log::channel('logGuide')->info("\n".'ห้อง'.$host->name.$messageResult);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
            }
        }

        $this->sendGuide($datas,$time);
    }


    public function vip($normalDatas ,$vipDatas,$time){
        $normal_message_value = '';
        $vip_message_value = '';
        $new_line = '';

        if(!empty($normalDatas)){
            $normalNumbers = array_reverse($normalDatas);
            foreach($normalNumbers as $key => $normalResult){
                if($key != 0){
                $new_line = "\n" ;
                }

                $slug = $normalResult['edition_slug'];
                $round = substr($slug, strrpos($slug, '|' )+1);
                $dateround = $this->DateThai(strtok($slug, '|'));
                $close_at = date('H:i', strtotime($normalResult['close_at']));
                $normal_message_value = $normal_message_value.$new_line.$round.' : '.$close_at.' '."\u{1F449}".' '.$normalResult['3upper'].'-'.$normalResult['2under'];
            }
        }

        if(!empty($vipDatas)){
            $vipNumbers = array_reverse($vipDatas);
            foreach($vipNumbers as $key => $vipResult){
                if($key != 0){
                $new_line = "\n" ;
                }

                $slug = $vipResult['edition_slug'];
                $round = substr($slug, strrpos($slug, '|' )+1);
                $dateround = $this->DateThai(strtok($slug, '|'));
                $close_at = date('H:i', strtotime($vipResult['close_at']));
                $vip_message_value = $vip_message_value.$new_line.$round.' : '.$close_at.' '."\u{1F449}".' '.$vipResult['3upper'].'-'.$vipResult['2under'];
            }
        }
        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){

                    $arrayStatus = json_decode($host->status);

                    $checked = $this->findStatus($arrayStatus,$time);

                    if(($normal_message_value != '' || $vip_message_value != '') && $checked !== false){
                        try {
                            $messageNormalResult = '';
                            $messageVipResult = '';
                            $normalLastHuay = '';
                            $vipLastHuay = '';
                            $handEmoji = "\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}";
                            $VIPEmoji = "\u{1F31F}\u{1f64f}\u{1F31F}\u{1f64f}\u{1F31F}";
                            $textLine = "\n"."------------------------------------";
                            $headerFix = "\n".$handEmoji."\n"."ผลหวยสอยดาว"."\n".'Arawanbet - 15 นาที'."\n".$dateround."\n";
                            $normalLine = $vip_message_value == '' ? "\n\n" : '';
                            $normalNewLine = '';
                            if($normal_message_value){
                                $headerGuide = "\n".$handEmoji."\n"."ผลหวยสอยดาว"."\n".'Arawanbet - 15 นาที'."\n".$dateround."\n";
                                $normalLastHuay = "ผลสอยดาว ARAWAN - 15 นาที"."\n"."ล่าสุดรอบที่ ".$round.' '."\u{1F449}".' '.$normalResult['3upper'].'-'.$normalResult['2under'];
                                $messageNormalResult = $handEmoji."\n\n\n".$normal_message_value."\n".$textLine.$normalLine;
                                $normalNewLine = "\n\n";
                            }
                            if($vip_message_value){
                                $headerGuide = "\n".$handEmoji."\n"."ผลหวยสอยดาว"."\n".'Arawanbet - 5 นาที (พิเศษ)'."\n".$dateround."\n";
                                $vipNewLine = $normalNewLine ? "" : "\n\n";
                                $vipEmo = $normalNewLine ? '' : $handEmoji;
                                $vipLastHuay = $normalNewLine.$VIPEmoji."\n"."ผลสอยดาว ARAWAN - 5 นาที (พิเศษ)"."\n"."ล่าสุดรอบที่ ".$round.' '."\u{1F449}".' '.$vipResult['3upper'].'-'.$vipResult['2under']."\n".$VIPEmoji;
                                $messageVipResult = $vipEmo."\n".$vipNewLine.$vip_message_value."\n".$textLine."\n\n";
                            }

                            $header = $normal_message_value && $vip_message_value ? $headerFix : $headerGuide;
                            $footer = $normalLastHuay.$vipLastHuay."\n".$textLine."\n\n".$dateround;

                            $messageResult = $header.$messageNormalResult.$messageVipResult.$footer;
                            $token = $host->line_token;
                            $line = new Line($token);
                            $line->send($messageResult);
                            Log::channel('logGuide')->info("\n".'ห้อง'.$host->name.$messageNormalResult.$messageVipResult);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
            }
        }


        if(!empty($normalDatas)){
            $this->sendGuide($normalDatas,$time);
        }
        if(!empty($vipDatas)){
            $this->sendGuide($vipDatas,$time);
        }
    }


    public function lotto($datas,$game_slug){
        $numbers = array_reverse($datas);
        $message_value = '';
        $new_line = '';
        // $array_game = array_search($game_slug,['stock_china','stock_japan']);
        foreach($numbers as $key => $result){
            $slug = $result['edition_slug'];
            $round = '';

            if($game_slug == 'stock_china' || $game_slug == 'stock_japan'){
              $time_check = substr($result['edition_slug'], -2);
              $now = Carbon::now();
              if($now->format('A') != $time_check){
                continue;
              }
            }else{
              $round = substr($slug, strrpos($slug, '|' )+1).' : ';
            }

            if($key != 0){
            $new_line = "\n" ;
            }

            $dateround = $this->DateThai(strtok($slug, '|'));
            $close_at = date('H:i', strtotime($result['close_at']));
            $message_value = $message_value.$new_line.$round.$close_at.' '."\u{1F449}".' '.$result['3upper'].'-'.$result['2under'];
            $result_lasted['3upper'] = $result['3upper'];
            $result_lasted['2under'] = $result['2under'];
        }

        $game = config('game')[$game_slug];

        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){
                    $arrayStatus = json_decode($host->status);

                    $checked = $this->findStatus($arrayStatus,$game_slug);

                    if($message_value != '' && $checked !== false){
                        try {
                            $handEmoji = "\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}\u{1f64f}";
                            $textLine = "------------------------------------";
                            $lastHuay = $game['title']."\n"."ล่าสุดรอบที่ ".$round.' '."\u{1F449}".' '.$result_lasted['3upper'].'-'.$result_lasted['2under'];
                            $messageResult = "\n".$handEmoji."\n".$game['title']."\n".'arawanbet - '.$game['title']."\n".$dateround."\n".$handEmoji."\n\n".$message_value."\n\n".$textLine."\n\n".$lastHuay."\n\n".$textLine."\n\n".$dateround;
                            // dd($messageResult);
                            $token = $host->line_token;
                            $line = new Line($token);
                            $line->send($messageResult);
                            Log::channel('logGuide')->info("\n".'ห้อง'.$host->name.$messageResult);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
            }
        }
    }

    function sendGuide($datas,$time){

        $timeText = '';
        if($time == '15'){
            $timeText = "\n"."VIP";
        }

        $agents = User::where('role','agent')->get();
        foreach($agents as $agent){
            if(!empty($agent->host)){
                foreach($agent->host as $host){

                    $arrayStatus = json_decode($host->status);
                    $checked = $this->findStatus($arrayStatus,$time);

                    if($checked !== false){

                        $guideHuayMessage2under = [];
                        $guideHuayMessage3upper = [];
                        $lastGuideMessage2under = '';
                        $lastGuideMessage3upper = '';
                        $under2 = $datas[0]['2under'];
                        $upper3 = $datas[0]['3upper'];

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
                            $lastGuideMessage3upper = $timeText."\n".'พบสามตัวบน เลข '.$upper3."\n".'คำแนะนำ : '.$guildRandom3upper;
                        }
                        if(!empty($guideHuayMessage2under)){
                            $guild2under = array_rand($guideHuayMessage2under);
                            $guildRandom2under = $guideHuayMessage2under[$guild2under];
                            $lastGuideMessage2under = $timeText."\n".'พบสองตัวล่าง เลข '.$under2."\n".'คำแนะนำ : '.$guildRandom2under;
                        }

                        if($lastGuideMessage2under != ''){
                            try {
                                $token = $host->line_token;
                                $line = new Line($token);
                                $line->send($lastGuideMessage2under);
                                Log::channel('logGuide')->info("\n".$lastGuideMessage2under);
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }

                        if($lastGuideMessage3upper != ''){
                            try {
                                $token = $host->line_token;
                                $line = new Line($token);
                                $line->send($lastGuideMessage3upper);
                                Log::channel('logGuide')->info("\n".$lastGuideMessage3upper);
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }

                        $formulaUnder2condition = Formula::where('user_id',$host->user_id)->where('condition',1)->with('values')->get();
                        foreach($formulaUnder2condition as $under2condition){
                            $number = [];
                            if($under2condition->values){
                                $guideHuayCheck2UnderConditionsMessages = '';
                                $inrounds = array_slice($datas, 0, $under2condition->last_round);
                                if($under2condition->type == 2){
                                    foreach($inrounds as $inround){
                                        $number[] = $inround['2under'];
                                    }
                                    $luckynumber = $under2;
                                }
                                if($under2condition->type == 3){
                                    foreach($inrounds as $inround){
                                        $number[] = $inround['3upper'];
                                    }
                                    $luckynumber = $upper3;
                                }
                                // $number = array_count_values($number);
                                $checkLastNumber = FormulaValue::where('formula_id',$under2condition->id)->where('value',$luckynumber)->first();
                                if($checkLastNumber){
                                    $guideHuayCheck2UnderConditions = FormulaValue::where('formula_id',$under2condition->id)->whereIn('value',$number)->count();
                                    if($guideHuayCheck2UnderConditions >= $under2condition->round){
                                        $guideHuayCheck2UnderConditionsMessages =  $timeText."\n".'คำแนะนำ : '.$under2condition->result;
                                    }
                                    if($guideHuayCheck2UnderConditionsMessages != ''){
                                        try {
                                            $token = $host->line_token;
                                            $line = new Line($token);
                                            $line->send($guideHuayCheck2UnderConditionsMessages);
                                            Log::channel('logGuide')->info("\n".$guideHuayCheck2UnderConditionsMessages);
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                    }
                                }
                            }
                        }

                    }

                }
            }
        }
    }
}
