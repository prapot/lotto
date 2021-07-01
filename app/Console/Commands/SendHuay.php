<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phattarachai\LineNotify\Line;

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
    protected $description = 'ส่งข้อความทุก ๆ 15 นาที';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $array = [ 'ehQRZfFjgY9wjGe5Ho3aV9QdN67RoEeKDOWkNcScdgS','c8ohBpGq2beJRdeZpNGodPzj4bgRkifz7cn631XRQrr' ];
        foreach($array as $key => $arr){
            $line = new Line($arr);
            $line->send('message'.$key);
        }
        echo 'success';
        // return 0;
    }
}
