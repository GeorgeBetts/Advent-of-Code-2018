<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);

$arrClaims = [];
$fabric = [];
$fabricWidth = 0;
$fabricHeight = 0;
$overlapCount = 0;
$testOverlapCount = 0;

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
//echo $fabricWidth. ' x ' . $fabricHeight;

//Create the fabric array and set every square inch to 0, an array element for every square inch of fabric
for($i = 0; $i < $fabricHeight; $i++) {
    for($x = 0; $x < $fabricWidth; $x++) {
        $fabric[$i][$x] = 0;
    }
}

//Loop every claim
for($i = 0; $i < count($arrClaims); $i++) {
    //loop through the weight and width of this claim
    for($y = 1; $y < $arrClaims[$i]->height; $y++) {
        for($x = 1; $x < $arrClaims[$i]->width; $x++) {            
            //+1 the counter of this square inch of fabric, using the left ad top margin as an offset
            $fabric[$arrClaims[$i]->margin_top + $y][$arrClaims[$i]->margin_left + $x]++;
            //Count the overlap value (more than 1 in this particular square inch means it's overlapped another claim)
            //but only increment the counter when it overlaps the first time (== 2)
            if($fabric[$arrClaims[$i]->margin_top + $y][$arrClaims[$i]->margin_left + $x] == 2) {
                $overlapCount++;
            }
        }
    }
}

echo $overlapCount;

for($i = 0; $i < $fabricHeight; $i++) {
    for($x = 0; $x < $fabricWidth; $x++) {
        if($fabric[$i][$x]>1)$testOverlapCount++;
    }
}

echo '\n' . $testOverlapCount;

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