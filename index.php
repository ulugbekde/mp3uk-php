<?php

header('Content-Type: application/json');

if(empty($_GET['text'])){
  echo json_encode(['error'=>'text not found!']);
  exit();
}

function search($c){
$data = array(
    'do' => 'search',
    'subaction' => 'search',
    'search_start' => $c,
    'full_search' => 0,
    'result_from' => 1,
    'story' => $_GET['text']
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://mp3uk.net/index.php?do=search");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);

$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($response);

$xpath = new DOMXPath($dom);
$divs = $xpath->query("//div[contains(@class, 'track-item')]");

$data = [];
foreach ($divs as $div) {

            $title = $div->getElementsByTagName("a")->item(0)->textContent;
$title =  str_replace("\n", " ", $title);

            $time = $div->getElementsByTagName("div")->item(3)->textContent;
            $full = $div->getElementsByTagName('a')->item(1)->getAttribute('href');
          $data[] = array("title"=>$title,"time"=>$time,"url"=>'https:'.$full);
}
return $data;
}
$list = [0,1,2,3,4,5];
$data = [];
foreach($list as $e){
  $l = search($e);
if(!$l){
break;
}else{
$data[] = $l;

}
}
  echo json_encode($data);

?>
