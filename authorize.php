<?php
error_reporting(1);
ini_set('display_errors', true);
require_once('config/app.php');
require_once('config/session.php');
// session_start();
try {

    # Get the Authorization Code.
    if (isset($_GET['code'])) {
        $code = $_GET['code'];

        $params = [
            'client_id'     => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'code'          => $code,
            'grant_type'    => GRANT_TYPE,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.webflow.com/oauth/access_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if ($result) {
            $responseArr = json_decode($result, true);

            if (isset($responseArr['access_token'])) {
                $_SESSION['access_token'] = $responseArr['access_token'];
                header('Location: ' . HOME_PAGE_URL);
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage() . ' at line number ' . $e->getLine();
}
