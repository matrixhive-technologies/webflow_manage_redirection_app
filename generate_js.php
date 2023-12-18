<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE PATCH');
header('Content-Type: application/json; charset=utf-8');
require_once('classes/Api.php');
require_once('config/session.php');

if ($_SESSION['access_token']) {
    $api = new Api();
    $cid = $_REQUEST['cid'];
    $sid = $_REQUEST['sid'];
    $response = $api->setEndPoint("collections/$cid/items")->setMethod($_REQUEST['method'] ?? 'GET')
        ->setAccessToken($_SESSION['access_token'])
        ->setParams($_REQUEST['params'])
        ->callApi()->getResponse();
    $response = json_decode($response,true);
    if(isset($response['items'])){
        $redirectData = [];
        foreach( $response['items'] as $item ) {
            $redirectData[] = ['from' => $item['fieldData']['from'] , 'to' => $item['fieldData']['to']];
        }
        $jsonPath = BASE_PATH.'assets/sites/'.$sid.'.json';
        $jsPath = BASE_PATH.'assets/sites/'.$sid.'.js';
        if(file_exists($jsPath)){
            unlink($jsPath);
        }
        if(file_exists($jsonPath)){
            unlink($jsonPath);
        }
       
        $jsonfile = fopen($jsonPath, "w");
        $write = fwrite($jsonfile,json_encode($redirectData));
        fclose($jsonfile);
        $jsonUrl = APP_URL.'/assets/sites/'.$sid.'.json';
        
        $javaScript = file_get_contents(BASE_PATH.'redirection_template.js');
        $javaScript = str_replace('#json_url#',$jsonUrl,$javaScript);
        $jsfile = fopen($jsPath, "w");
        $write = fwrite($jsfile,$javaScript);
        $jsUrl = APP_URL.'/assets/sites/'.$sid.'.js';
        fclose($jsfile);
        
        echo json_encode([
            "generated_js" => $jsUrl,
            "sid" => $sid,
            "cid" => $cid 
        ]);


    } else {
        echo json_encode(['code' => 500, 'message' => 'Something went wrong.']);
    }
    

} else {
    echo json_encode(['code' => 403, 'message' => 'Unauthorized Request']);
}


