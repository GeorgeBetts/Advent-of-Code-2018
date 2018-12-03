<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);

$arrClaims = [];

//First step is to loop each line in the input file and get a readable format
foreach ($values as $key => $line) {
    //Create a claim object with the appropriate values
    $claim = (object)[
        'id' => trim(get_string_between($line, '#', '@')),
        'margin_left' => trim(get_string_between($line, '@', ',')),
        'margin_top' => trim(get_string_between($line, ',', ':')),
        'width' => trim(get_string_between($line, ':', 'x')),
        'height' => substr(trim(strstr($line, 'x')),1),
    ];
    //Add the object to the array of claims so we have a readable list
    $arrClaims[] = $claim;
}


function get_string_between($string, $start, $end){
    $value = '';
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini != 0) {
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $value = substr($string, $ini, $len);
    }
    return $value;
}