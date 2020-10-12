<?php
namespace udp;
use Swoole\Server;
use swoole_server;

class UdpServer
{
    public function CreateServer()
    {
        $host = "0.0.0.0";
        $port = "9502";
        $server = new Server($host, $port, SWOOLE_PROCESS,SWOOLE_SOCK_UDP);
        $server->on('packet', function ($server, $data, $fd){
            var_dump($server);
            var_dump($data);
            var_dump($fd);
            $server->sendto($fd['address'], $fd['port'], "server: $data");
        });
        $server->start();
    }
}

$udp = new UdpServer();
$udp->CreateServer();