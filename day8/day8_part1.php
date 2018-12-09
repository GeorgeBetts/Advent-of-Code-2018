<?php
$input = preg_replace('/\s+/', '', file_get_contents('debug_input.txt'));

$firstHeader = substr($input, 0, 2);
$output = 0;
$nextHeader = "";
$headers = [];
$headerIndex = 0;
$charArray = str_split($input);
foreach ($charArray as $char) {
    $charIsMetadata = false;
    switch (strlen($nextHeader)) {
        case 0:
            //Brand new header - add the child node quantity
            $headers[$headerIndex][0] = intval($char);
            $nextHeader .= $char;
            break;
        case 1:
            //Header contains the quantity of child nodes - add the quantity of metadata
            $headers[$headerIndex][1] = intval($char);
            $nextHeader .= $char;
            break;
        case 2:
            //Header contains the quantity of metadata entries - save this off, this header is complete
            if ($headers[$headerIndex][0] > 0) {
                $headers[$headerIndex][0]--;
                $headerIndex++;
                //The next node is a child node, so begin the next header
                $nextHeader = "";
                $headers[$headerIndex][0] = intval($char);
                $nextHeader .= $char;
            } elseif ($headers[$headerIndex][1] > 0) {
                //No Child nodes so get the metadata for this node
                $output += intval($char);
                $headers[$headerIndex][1]--;
            } else {
                //Node is done
                $nextHeader = "";
                $headerIndex--;
            }
            break;
        default:
            break;
    }
}

echo $output;
