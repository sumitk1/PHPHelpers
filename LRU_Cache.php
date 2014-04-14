<?php

define ("MAX_LINES", 1000000);
//$file = fopen('php://stdin', 'r');
$file       = fopen('input00.txt', 'r');
$totalCommands = fgets($file);
$totalCommands = preg_replace('~[\r\n]+~', '', $totalCommands);


if ($totalCommands > MAX_LINES) {
    $totalCommands = MAX_LINES;
}

$cacheSize = 0;
$cache     = array ();

for ($i = 0; $i < $totalCommands; $i++) {
    $key       = $value = 0;
    $line      = fgets($file);
    $line      = preg_replace('~[\r\n]+~', '', $line);
    $lineArray = explode(" ", $line);

    if (empty($lineArray[0])) {
        continue;
    }

    $command = strtoupper($lineArray[0]);

    $key = isset($lineArray[1]) ? trim($lineArray[1]) : "";
    $key = (strlen($key) <= 10) ? $key : mb_substr($key, 0, 10);

    $value = isset($lineArray[2]) ? trim($lineArray[2]) : "";
    $value = (strlen($value) <= 10) ? $value : mb_substr($value, 0, 10);

    if (($command != "BOUND") && ($command != "DUMP") && empty($cacheSize)) {
        continue;
    }

    switch ($command) {

        case "BOUND":
            break;

        case "SET":                
            if (array_key_exists($key, $cache)) {
                unset ($cache[$key]);
                $cache[$key] = $value;
            } else {
                if ($cacheSize > count($cache)) { 
                    $cache[$key] = $value;  
                }
                elseif ($cacheSize == count($cache)) {   
                    array_shift($cache);
                    $cache[$key] = $value;
                }              
            }
            break;

        case "GET":
            if (array_key_exists($key, $cache)) {
                $value = $cache[$key];
                echo "\n" . $value;
                unset ($cache[$key]);
                $cache[$key] = $value;
            } else {
                echo "\nNULL";
            }
            break;

        case "PEEK":
            if (array_key_exists($key, $cache)) {
                $value = $cache[$key];
                echo "\n" . $value;
            } else {
                echo "\nNULL";
            }
            break;

        case "DUMP":
            if (empty($cache) || empty($cacheSize)) {
                //echo "\nNULL";
            } else {
                $tempCache = $cache;
                ksort($tempCache);
                foreach ($tempCache as $key => $value) {
                    echo  "\n$key $value";
                }
            }
            break;

        default:
            break;

    }

}