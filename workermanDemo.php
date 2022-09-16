<?php
  
  
require_once '/data/wwwroot/g_vendor/autoload.php';
require_once './Workerman/Autoloader.php';
use \Workerman\Worker;
use \Workerman\Lib\Timer;
ini_set('display_errors', "On");
ini_set('memory_limit', '1024M');
error_reporting(E_ALL^E_NOTICE^E_WARNING);
use \Doraemon\model\AdmUserType;

$task = new Worker();
$task->count = 10;
$task->onWorkerStart = function($task)
{
  $iUidCountPerProcess = 1400000;
  $iTaskId   = $task->id;
  $iStartUid = $iTaskId * $iUidCountPerProcess;
  $iEndUid   = ($iTaskId + 1) * $iUidCountPerProcess;
  //echo "{$iStartUid} - {$iEndUid}".PHP_EOL;
  //file_put_contents("common.log","{$iStartUid} - {$iEndUid}".PHP_EOL,FILE_APPEND);
  $iPage    = 1;
  $iPageNum = 50000;
  $iTotalPage = ceil($iUidCountPerProcess/50000);
  //echo $iTotalPage.PHP_EOL;
  while ($iPage <= $iTotalPage) {
    $iTemp = ($iPage - 1) * $iPageNum;
    $sSql = "select uid,identity_mark from hhz_adm_user_type where uid>={$iStartUid} and uid<={$iEndUid} limit {$iTemp},{$iPageNum}";
    //$aRet = AdmUserType::query()->fetchAll();
    echo $sSql.PHP_EOL;
    $iPage++;
    sleep(2);
  }
};

// 运行worker
Worker::runAll();
