<?php
$input = file_get_contents('input.txt');
$prevLength = 0;
$subStringStart = 0;
for ($i=0; $i < strlen($input); $i++) { 
    // Substring the first two characters
    $nextTwoUnits = substr($input,$subStringStart,2);
    //If they explode, remove them and start the whole loop again
    if(doUnitsExplode($nextTwoUnits)) {
        //Remove the units and restart the loop, NOTE: This actually replaces all occurences of the same two units
        $input = str_replace($nextTwoUnits, "", $input);
        $i = 0;
        $subStringStart = 0;
    } else {        
        //If they don't explode, get the next two characters
        $subStringStart++;
    }    
}

echo strlen($input);

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