<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);

$doubles = 0;
$triples = 0;

foreach ($values as $key => $value) {
    $line = str_split($value);
    $lineCount = array_count_values($line);
    $lineHasDouble = false;
    $lineHasTriple = false;
    foreach ($lineCount as $key2 => $letter) {
        if(!$lineHasDouble) {
            if($letter  == 2) {
                $lineHasDouble = true;
                $doubles++;
            }
        }
        if(!$lineHasTriple) {
            if($letter == 3) {
                $lineHasTriple = true;
                $triples++;
            }
        }
    }
}

echo $doubles * $triples;