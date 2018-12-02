<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);

$matchLine1 = "";
$matchLine2 = "";

//Loop each line in the input file
foreach ($values as $key => $line) {
    //Call function that returns any matching line (with 1 character difference)
    $matchingLine = countSimilarChars($line, $values);
    if($matchingLine) {
        //We have our matching line so add to variables and break the loop
        $matchLine1 = $line;
        $matchLine2 = $matchingLine;        
        break;
    }
}

//These are the those matching lines (1 character difference)
echo $matchLine1 . " | " . $matchLine2;

//Function loops each line and counts how many matching characters for each line against the line passed to the function
function countSimilarChars($line, $values) { 
    //Loop each line in the input file
    foreach ($values as $key => $value) {
        //If the matching characters == 25 then return the matching line
        if(similar_text($line, $value) == 25) {
            return $value;
        }
    }
}