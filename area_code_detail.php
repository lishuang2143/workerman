<?php


//use Doraemon\model\Area\AreaDetails;
//use Hhz\AdministrativeRegion\Region;
//
//ini_set('display_errors', 1);
//error_reporting(-1);
//require_once '/data/wwwroot/g_vendor/autoload.php';



function a($n){
    if($n <=2)
        return 2;

    $pre1 = 2;
    $pre2 = 1;
    for ($i = 2;$i< $n; $i++){
        $res = $pre1+$pre2;
        $pre2 = $pre1;
        $pre1 = $res;
    }
    return $res;
}


function b($n){
    if($n <=2){
        return 2;
    }

    return ($n-1)+($n-2);

}



echo  a(5).PHP_EOL;
echo b(5).PHP_EOL;exit;



$aAllData = AreaDetails::selectByWhere(['id[<]' =>5000],['pinyin_prefix','pcode','deep','code','name']);
//var_dump($aAllData);die();
$LeveOne = $LeveZero =$LeveTwo = [];
foreach ($aAllData as $aAllInfo){
    $aAllInfo['first_charter'] = strtoupper($aAllInfo['pinyin_prefix']);
    unset($aAllInfo['pinyin_prefix']);
    if((int)$aAllInfo['deep'] === 0){
        unset($aAllInfo['deep']);
        $LeveZero[] = $aAllInfo;
    }elseif((int)$aAllInfo['deep'] === 1){
        unset($aAllInfo['deep']);
        $LeveOne[]  = $aAllInfo;
    }else{
        unset($aAllInfo['deep']);
        $LeveTwo[] =  $aAllInfo;
    }
}

array_multisort(array_column($LeveZero, "first_charter"), SORT_ASC, $LeveZero);
array_multisort(array_column($LeveOne, "first_charter"), SORT_ASC, $LeveOne);
array_multisort(array_column($LeveTwo, "first_charter"), SORT_ASC, $LeveTwo);


foreach ($LeveOne as &$aOne){
    foreach ($LeveTwo as $aTwo){

        if($aOne['code'] == $aTwo['pcode'] ){
            $aOne['sublist'][] = $aTwo;
        }
    }
}
unset($aOne);

foreach ($LeveZero as &$aZero) {
    foreach ($LeveOne as $aOne) {
        if ($aZero['code'] == $aOne['pcode']) {

            $aZero['sublist'][] = $aOne;
        }
    }
}
unset($aZero);


file_put_contents('lis.json', json_encode($LeveZero, JSON_THROW_ON_ERROR));

//file_put_contents('new.json', json_encode($LeveZero, JSON_UNESCAPED_UNICODE),LOCK_EX);


//print_r($LeveZero);d
