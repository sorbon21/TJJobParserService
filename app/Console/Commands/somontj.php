<?php

namespace App\Console\Commands;

use App\Services\AddService;
use App\Services\TelegramBotService;
use Illuminate\Console\Command;
use Mockery\Exception;

class somontj extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:somontj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $api = new TelegramBotService('6765647108:AAFC2vst-wrCfxnvUQmgjTMiKCs0v7uvshQ', '-1002072797293');
        $somon = new \App\Services\Source\SomonTj();
        $list = $somon->getList('vakansii/?q=курьер&page=2');
        foreach ($list as $item) {
            try {
                $adv = $somon->getAdv($item);
                sleep(2);
                $adv['phone'] = $somon->getPhone($item);
                $message = AddService::create($adv);
                $api->createAdd($message);
                sleep(5);
            } catch (Exception $exception) {
                echo 'Не смог добавить' . $item . PHP_EOL;
                continue;
            }

        }
    }
}
