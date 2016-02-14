<?php
$passkey = 'ah87sdgo8agsdaisgdoaygs9dgASDAIHUasdgasdoJsahKJHiygaoriyg3';
if ($_GET['passkey'] != $passkey) exit('wrong');
/****************/

if (false === ($xls_content = @file_get_contents('http://admnyagan.ru/cz/vac.xls'))) exit('Cant get the file'); //Получаем файл

require_once __DIR__ . '/PHPExcel/PHPExcel.php';
$config = require_once __DIR__ . '/config.php';

$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objReader->setReadDataOnly(true);

$file_path = $config['file']['path'] . $config['file']['name'];
file_put_contents($file_path, $xls_content); //Сохраняем

if (!file_exists($file_path)) exit('File does not exist');

$objPHPExcel = $objReader->load($file_path);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, false, true);

$datearr = array_shift($sheetData);//slice the heads
$datestring = $datearr['A'];
//print_r($sheetData);exit;

if ($datestring) {
    require_once __DIR__ . '/db/sqlite.php';
    $db = new Model();

    $date = date('Ymd');
    $existing = $db->checkPrevious($datestring, $date);
} else {
    $existing = true;
}

if ($sheetData && !$existing) {
    /*
     * Профессия
     * Организация
     * Дополнительные пожелания
     * З/П руб.
     * Адрес организации
     * Контактные данные
     */
    $try_count = 5;//max num of rows, which script will skip
    do {
        //this row can be empty, so, check it
        $names_row = array_shift($sheetData);//slice the columns names
        $try_count--;
    } while (!$names_row['A'] && $try_count > 0);


    if (!$names_row['A']) {
        mail('corpny86@yandex.ru', 'NY86.ru empty vac file', print_r($sheetData, true));
        $db->deleteDateRec($datestring);//if it still empty, rollback to prev
        exit();
    }

    $column_name = [];
    foreach ($names_row as $col => $name) {
        $column_name[$name] = $col;
    }

    //$st = microtime(true);
    $date = date('Ymd');
    foreach ($sheetData as $row => $data) {
        $values['pro'] = $data[$column_name['Профессия']];
        $values['org'] = $data[$column_name['Организация']];
        $values['add'] = $data[$column_name['Дополнительные пожелания']];
        $values['sal'] = $data[$column_name['З/П руб.']];
        $values['adr'] = $data[$column_name['Адрес организации']];
        $values['con'] = $data[$column_name['Контактные данные']];
        $values['dat'] = $date;

        $db->insertValues($values);
    }
    //echo microtime(true) - $st;
    echo 'Writed';
} else {
    echo 'Exist';
}
unset($db);