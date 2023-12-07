<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE PATCH');
session_start();
$_SESSION['last'] = time();

echo json_encode(['message' => $_SESSION]);
