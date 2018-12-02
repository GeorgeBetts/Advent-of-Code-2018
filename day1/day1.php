<?php
$values = file('input.txt', FILE_IGNORE_NEW_LINES);
$result = 0;
$prevResults[] = 0;

runFrequency($result, $values, $prevResults);

function runFrequency($frequencyNum, $values, $prevResults) {
    foreach ($values as $key => $value) {
        $frequencyNum = $frequencyNum + $value;
        echo count($prevResults) . "\n";
        if(in_array($frequencyNum, $prevResults, true)) {
            echo "pooprat: " . $frequencyNum;
            exit();
        } else {
            $prevResults[] = $frequencyNum;            
        }
    }
    runFrequency($frequencyNum, $values, $prevResults);
}