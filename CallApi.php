<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE PATCH');
require_once('classes/Api.php');
session_start();
if ($_SESSION['access_token']) {
    $api = new Api();
    $response = $api->setEndPoint($_REQUEST['endPoint'])->setMethod($_REQUEST['method'] ?? 'GET')
        ->setAccessToken($_SESSION['access_token'])
        ->setParams($_REQUEST['params'])
        ->callApi()
        ->sendResponse();
} else {
    echo json_encode(['code' => 403, 'message' => 'Unauthorized Request']);
}
