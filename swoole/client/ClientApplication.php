<?php
namespace client;

use Swoole;

class ClientApplication
{

    public function Create()
    {
        $client = new Swoole\Client(SWOOLE_SOCK_TCP);
    }

    public function Init(){
        $sku_id = 11;

    }
}