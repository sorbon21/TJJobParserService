<?php

namespace App\Console\Commands;


use App\Services\AddService;
use App\Services\Source\SomonTj;
use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class hallo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hallo';

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
        $somon = new SomonTj();
        $list = $somon->getList('nedvizhimost/prodazha-kvartir/?ordering=cheep');
        sleep(5);
        var_dump($list);
        sort($list);
        foreach ($list as &$item) {
            try {
                if (isset($item['images'])) {
                    $count = count($item['images']);
                    $caption = "<b>{$item['title']}</b> {$item['date']}\n"
                        . "💰 Price: {$item['price']}\n"
                        . "🌍 Place: {$item['place']}";

                    $api->senAlboom($item['images']);
                    $api->createAdd($caption);
                }

//                var_dump($item['slug']);
//                $adv = $somon->getAdv($item['slug']);
//                print_r($adv);
//                $item['adv'] = $adv;
//                sleep(5);

            } catch (\Exception $e) {
                echo $e->getMessage();
                continue;
            }
        }

    }
}
