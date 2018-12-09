<?php
$input = file_get_contents('input.txt');

$firstHeader = substr($input, 0, 2);
$output = 0;
$nextHeader = "";
$headers = [];
$headerIndex = 0;
$nextHeaderLen = 0;
$charArray = explode(" ", $input);
foreach ($charArray as $char) {
    $charIsMetadata = false;
    switch ($nextHeaderLen) {
        case 0:
            //Brand new header - add the child node quantity
            $headerIndex++;
            $headers[$headerIndex][0] = intval($char);
            $nextHeaderLen++;
            break;
        case 1:
            //Header contains the quantity of child nodes - add the quantity of metadata
            $headers[$headerIndex][1] = intval($char);
            $nextHeaderLen++;
            break;
        case 2:
            //Header contains the quantity of metadata entries - save this off, this header is complete
            if ($headers[$headerIndex][0] > 0) {
                $headers[$headerIndex][0]--;
                $headerIndex++;
                //The next node is a child node, so begin the next header
                $nextHeaderLen = 0;
                $headers[$headerIndex][0] = intval($char);
                $nextHeaderLen++;
            } elseif ($headers[$headerIndex][1] > 0) {
                //No Child nodes so get the metadata for this node
                $output += intval($char);
                $headers[$headerIndex][1]--;
                if ($headers[$headerIndex][1] == 0) {
                    //The following while loop checks back through previous headers to see if there are any remaining child nodes or metadata
                    $remainingChildNodesOrMetaData = false;
                    while (!$remainingChildNodesOrMetaData) {
                        if ($headers[$headerIndex][0] == 0) {
                            if ($headers[$headerIndex][1] != 0) {
                                //Still metadata left for this header
                                $nextHeaderLen = 2;
                                $remainingChildNodesOrMetaData = true;
                            } else {
                                if ($headerIndex != 1) {
                                    //No child nodes or metadata left, check the previous header
                                    $headerIndex--;
                                } else {
                                    //The very last node
                                    $remainingChildNodesOrMetaData = true;
                                }
                            }
                        } else {
                            //Still child nodes left, don't go to the previous header yet
                            $nextHeaderLen = 2;
                            $remainingChildNodesOrMetaData = true;
                        }
                    }
                }
            } else {
                //Node is done
                $nextHeaderLen = 0;
                $headerIndex--;
            }
            break;
        default:
            break;
    }
}

echo $output;
