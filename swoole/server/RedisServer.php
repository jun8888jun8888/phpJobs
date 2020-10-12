<?php
namespace server;

use Co;
use Swoole\Coroutine\Channel;
use Swoole\Coroutine\Http\Server;
use Swoole\Coroutine\MySQL;
use Swoole\Coroutine\Redis;

class RedisServer
{
    /**
     * @var Redis
     */
    private $_redis = null;
    /**
     * @var MySQL
     */
    private $_mysql = null;

    public function __construct()
    {
        Co::set(['hook_flags'=> SWOOLE_HOOK_ALL]);
        Co\run(function (){
            go(function (){
                $this->_redis = new Redis();
                $this->_redis->connect('127.0.0.1', 6379);

                $this->_mysql = new MySQL();
                $this->_mysql->connect([
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => 'root',
                    'database' => 'test',
                    'charset' => 'utf8',
                ]);
            });
        });

//        Co\run(function (){
//            for ($c=10; $c--;){
//                go(function () use ($c){
//                    //此处产生协程调度，cpu切到下一个协程，不会阻塞进程
//                    $key =  $redis->get('key');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
//                    echo "go $c $key \n";
//                });
//            }
//        });
    }

    public function Init()
    {
        Co\run(function (){
            go(function (){
                $sku_id = 11;
                $this->_mysql->query("update mall_test_store set number=1000 where sku_id='{$sku_id}'");
                $this->setkey($sku_id, 1000);
            });
        });
    }

    public function Login()
    {

    }

    public function skill(){
        Co\run(function (){
            $chan = new Channel(1);
            go(function () use ($chan){
                $key = "sku_id";
                $count = $this->_redis->lPop($key);
                $chan->push($count);
            });
            go(function () use ($chan){
                $chanData = $chan->pop();
                if (!$chanData){
                    echo '库存为0,已售罄';
                }else{
                    $sku_id = 11;
                    // 插入订单
                    $order_sn = uniqid();
                    $data = [
                        'order_sn' => $order_sn,
                        'user_id' => 1,
                        'goods_id' => 1,
                        'sku_id' => 11,
                        'price' => 10
                    ];
                    $sql2 = "update mall_test_store set number=number-1 where sku_id='{$sku_id}'";
                    $this->_mysql->query($sql2);
                }
            });
        });
    }

    private function setkey($key, $count)
    {
        for ($i=0; $i<$count; $i++){
            $this->_redis->lPush("sku_id", 1);
        }
    }
}


//$redis->Init();

//Co\run(function (){
//   $server = new Co\Http\Server('127.0.0.1', 9502, false);
//   echo "kaishi";
//   $server->handle('/skill', function ($request, $response){
////       $redis = new RedisServer();
////       $redis->skill();
//       $response->end("ok");
//   });
//   $server->start();
//});

//Co\run(function () {
//    $server = new Co\Http\Server("127.0.0.1", 9502, false);
//    $server->handle('/', function ($request, $response) {
//        $response->end("<h1>Index</h1>");
//    });
//    $server->handle('/test', function ($request, $response) {
//        $response->end("<h1>Test</h1>");
//    });
//    $server->handle('/stop', function ($request, $response) use ($server) {
//        $response->end("<h1>Stop</h1>");
//        $server->shutdown();
//    });
//    $server->start();
//});

$http = new \Swoole\Http\Server("0.0.0.0", 9501);
$http->on('request', function ($request, $response) {
    var_dump($request);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$http->start();