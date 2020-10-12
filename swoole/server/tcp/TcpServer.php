<?php
namespace tcp;

use swoole_server;

class TcpServer
{
    public function CreateTCPServer()
    {
        $host = "0.0.0.0";
        $port = "9501";
        $fd = null;
        $server = new swoole_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        $server->on('connect', function ($server, $fd){
            var_dump($server);
            var_dump($fd);
            echo "link ok\n";
        });
        $server->on('receive', function($server,$fd,$from_id,$data){
            echo "receive data\n";
            $server->send($fd, "nihao", $from_id);
        });
        $server->on('close', function ($server, $fd){
            echo "close\n";
        });

        $server->start();

    }
}

$tcp = new TcpServer();
$tcp->CreateTCPServer();