<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);

$arrClaims = [];
$fabricWidth = 0;
$fabricHeight = 0;

//First step is to loop each line in the input file and get a readable format
foreach ($values as $key => $line) {
    //Create a claim object with the appropriate values
    $claim = (object)[
        'id' => intval(trim(get_string_between($line, '#', '@'))),
        'margin_left' => intval(trim(get_string_between($line, '@', ','))),
        'margin_top' => intval(trim(get_string_between($line, ',', ':'))),
        'width' => intval(trim(get_string_between($line, ':', 'x'))),
        'height' => intval(substr(trim(strstr($line, 'x')),1)),
    ];
    //If this is the largest calculated width or height set this as the max fabric size
    if($claim->margin_left + $claim->width > $fabricWidth) $fabricWidth = $claim->margin_left + $claim->width;
    if($claim->margin_top + $claim->height > $fabricHeight) $fabricHeight = $claim->margin_top + $claim->height;
    //Add the object to the array of claims so we have a readable list
    $arrClaims[] = $claim;
}

//The maximum possible surface area of the fabric
echo $fabricWidth. ' x ' . $fabricHeight;


//Dumb function needed because apparently PHP can't do this built in
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