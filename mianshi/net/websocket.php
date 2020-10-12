<?php
use Swoole\WebSocket\Server;

class WebSocket{
    /**
     * @var \Swoole\WebSocket\Server $server
     */
    public $server;
    public $account;
    public function __construct(){
        $this->server = new Server('0.0.0.0', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $this->server->set([
            'daemonize'=>true,
            'ssl_cert_file' => "/etc/pki/nginx/1_www.footer.ink_bundle.crt",
            'ssl_key_file'  => "/etc/pki/nginx/2_www.footer.ink.key"
        ]);
        $this->server->on("open", function (Server $server, $request){
            var_dump($request);
            echo "server: handshake success with fd{$request->fd}\n";
        });
        $this->server->on("message", function (Server $server, $frame){
            $clintInfo = $server->getClientInfo($frame->fd);
            var_dump($clintInfo);
            $parse = explode("#", $frame->data);
            if (isset($this->account[$parse[0]])){

            }
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });
        $this->server->on("close", function ($ser, $fd){
            echo "client {$fd} closed\n";
        });
        $this->server->on("request", function ($request, $response){
           foreach($this->server->connections as $fd){
               if ($this->server->isEstablished($fd)){
                   $this->server->push($fd, $request->get['message']);
               }
           }
        });
        $this->server->start();
    }
}

new WebSocket();