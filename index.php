<?php

$result = [];

$views = 0;
$urls = [];
$summaryTraffic = 0;
$statusCodes = [];
$crawlers = [];
$popularCrawlers = ['Google', 'Bing', 'Baidu', 'Yandex'];

$file = fopen('test.txt', 'rt');

while (!feof($file)) {

    $string = fgets($file);
	$pattern = '/(.*?) - - \[(.*?)\] "(.*?)" (\d{1,}) (\d{1,}) "(.*?)" "(.*?)"/is'; 

    preg_match($pattern, $string, $matches);

    if (count($matches) === 8) {

        $ip = $matches[1];
        $date = $matches[2];
        $typeRequest = $matches[3];
        $statusCode = $matches[4];
        $traffic = $matches[5];
        $url = $matches[6];
        $browserData = $matches[7];
    
        $views++;
    
        if (!in_array($url, $urls)) {
            $urls[] = $url;
        }
    
        $summaryTraffic += $traffic;
    
        if (array_key_exists($statusCode, $statusCodes)) {
            $statusCodes[$statusCode] += 1;
        } else {
            $statusCodes[$statusCode] = 1; 
        }
    
        foreach ($popularCrawlers as $crawler) {
            if (!array_key_exists($crawler,$crawlers)) {
                $crawlers[$crawler] = 0;
            }
            if (stripos($browserData, $crawler)) {
                 $crawlers[$crawler] += 1;
            }
        }
    }
}

fclose($file);

$result['views'] = $views;
$result['urls'] = count($urls);
$result['traffic'] = $summaryTraffic;
$result['statusCodes'] = $statusCodes;
$result['$crawlers'] = $crawlers;

$jsonResult = json_encode($result);

echo "<pre>";
echo $jsonResult;
echo "</pre>";

?>