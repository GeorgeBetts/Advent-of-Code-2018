<?php
$input = file_get_contents('input.txt');
$alphabet = range('A', 'Z');
//$alphabet = ['A'];
$polymerVariants = [];

for ($i=0; $i < count($alphabet); $i++) {
    echo 'Starting ' . $alphabet[$i] . "\r\n";
    $objpolymer = (object)[
        'unit' => $alphabet[$i],
        'length' => evalPolymer(str_ireplace($alphabet[$i], "", $input))
    ];
    echo $alphabet[$i] . " " . $objpolymer->length . "\r\n";
    $polymerVariants[] = $objpolymer;
}

echo min_attribute_in_array($polymerVariants, 'length');

function evalPolymer($polymer) {
    $subStringStart = 0;
    for ($i=0; $i < strlen($polymer); $i++) { 
        // Substring the first two characters
        $nextTwoUnits = substr($polymer,$subStringStart,2);
        //If they explode, remove them and start the whole loop again
        if(doUnitsExplode($nextTwoUnits)) {
            //Remove the units and restart the loop, NOTE: This actually replaces all occurences of the same two units
            $polymer = str_replace($nextTwoUnits, "", $polymer);
            $i = 0;
            $subStringStart = 0;
        } else {        
            //If they don't explode, get the next two characters
            $subStringStart++;
        }    
    }
    return strlen($polymer);
}

function doUnitsExplode($units) {
    $react = false;
    $unitOne = substr($units,0,1);
    $unitTwo = substr($units,1,1);
    //Are the two units the same type? (0 == true in this function - why PHP?)
    if(strcasecmp($unitOne, $unitTwo) == 0) {
        //Units are the same type so check if the polarity is the same
        if($unitOne !== $unitTwo) {
            //Polarity is different explode the units
            $react = true;
        }
    }
    return $react;
}

function min_attribute_in_array($array, $prop) {
    return min(array_column($array, $prop));
}