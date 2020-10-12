<?php
/**
 * @var swoole_process $workers
 */
$workers = [];
$worker_num = 3;

//for ($i=0; $i<$worker_num; $i++){
//    $process = new swoole_process('doProcess');
//    $process->useQueue();
//    $pid = $process->start();
//    $workers[$pid] = $process;
//}
//
//function doProcess(swoole_process $process){
////    $process->write("pid: $process->pid");
//    $recv = $process->pop();
//    echo "从主进程获取到数据: $recv \n";
//    sleep(5);
//    $process->exit(0);
//}
//
//foreach($workers as $pid=>$process){
////    swoole_event_add($process->pipe, function ($pipe) use($process){
////        $data = $process->read();
////        echo "接受到: $data \n";
////    });
//    $process->push("hell 子进程 $pid \n");
//}
//
//for ($i=0; $i<$worker_num; $i++){
//    $ret = swoole_process::wait();
//    unset($workers[$ret['pid']]);
//    echo "子进程 $ret\['pid'\] 退出";
//}



$lock = new swoole_lock(SWOOLE_MUTEX);
echo "创建互斥锁\n";
$l = $lock->lock();
var_dump($l);
$n=pcntl_fork();
if ($n > 0){
    echo "我进来了 $n\n";
    //sleep(1);
    $lock->unlock();
    echo "wo tuichule\n";
}else{
    echo "子进程 $n\n";
    $l = $lock->lock();
    var_dump($l);
    echo "子进程 获取锁\n";
    $lock->unlock();
    exit("子进程退出\n");
}
echo  "主进程 释放锁\n";
unset($lock);
//sleep(1);
echo "子进程也退了哈哈\n";
