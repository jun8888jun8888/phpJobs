<?php
//僵尸进程
echo  '下面开始创建进程了'.PHP_EOL;
$child = [];
for ($i=0; $i<5; $i++){
    $child[] = $pid = pcntl_fork();
}

if ($pid == -1){
    die('fork失败');
}else if($pid == 0){
    //子进程
    $pName = "子进程";
//    sleep(20);
}else if($pid > 0){
    //父进程
    $pName = "父进程";
    pcntl_wait($status, WNOHANG);
}

$curPid = posix_getpid();
echo date('H:i:s') . $pName .':(PID:' . $curPid . ')'  . PHP_EOL;
while(count($child) > 0){
    foreach($child as $key =>$cPid){
        $res = pcntl_waitpid($cPid, $status, WNOHANG);
        if($res == -1 || $res > 0){
            unset($child[$key]);
        }
    }
}
if ($pid>0) {
    sleep(20);
}
echo date('H:i:s') . $pName .'(PID:'.$curPid.')结束' . PHP_EOL;
exit();