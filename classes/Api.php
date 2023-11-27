<?php

error_reporting(1);
ini_set('display_errors', true);
require_once('config/app.php');
require_once('config/database.php');


class Api
{
    protected $endPoint;
    protected $method = 'GET';
    protected $accessToken;
    protected $response = [];
    protected $params;
    protected $apiBaseUrl = 'https://api.webflow.com/v2/';

    public function __construct()
    {
        global $connection;
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function callApi()
    {
        try {
            if ($this->method == 'GET') {
                $result = $this->get();
                $this->response = $result;
            } else if ($this->method == 'PATCH') {
                $result = $this->patch();
                $this->response = $result;
            } else if ($this->method == 'POST') {
                $result = $this->post();
                $this->response = $result;
            }
        } catch (Exception $e) {
            $this->response = json_encode(['message' => $e->getMessage()]);
        }
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function sendResponse()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Content-Type: application/json; charset=utf-8');
        echo $this->response;
    }


    public function get()
    {
        $cachedResponse = $this->getCachedResponse();
        if ($cachedResponse) {
            return $cachedResponse;
        }

        $baseUrl = $this->apiBaseUrl;
        $url = $baseUrl . $this->endPoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $this->cacheResult($result);
        return $result;
    }

    public function patch()
    {

        $this->deleteCache();

        $baseUrl = $this->apiBaseUrl;
        $url = $baseUrl . $this->endPoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function post()
    {
        $cachedResponse = $this->getCachedResponse();
        if ($cachedResponse) {
            return $cachedResponse;
        }


        $baseUrl = $this->apiBaseUrl;
        $url = $baseUrl . $this->endPoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $this->cacheResult($result);
        return $result;
    }


    public function getCollectionID()
    {
        $collectionEndpoint = explode("/", $this->endPoint);
        return !empty($collectionEndpoint) && isset($collectionEndpoint[1]) && $collectionEndpoint[0] == 'collections' ? $collectionEndpoint[1] : '';
    }

    public function getSiteID()
    {
        $siteEndpoint = explode("/", $this->endPoint);
        return !empty($siteEndpoint) && isset($siteEndpoint[1]) && $siteEndpoint[0] == 'sites'  ? $siteEndpoint[1] : '';
    }

    public function getUserID()
    {
        return $_SESSION['LoggedInUser']['id'];
    }

    public function getCacheToken()
    {
        return md5($this->endPoint . $this->params);
    }

    public function getCachedResponse()
    {
        global $connection;

        $cachedData = "SELECT * from cached_results WHERE `cache_token`='" . $this->getCacheToken() . "'";

        $row = mysqli_fetch_assoc(mysqli_query($connection, $cachedData));

        return $row['response'];
    }

    public function cacheResult($result)
    {
        global $connection;
        $userId = $this->getUserID();
        $cacheToken = $this->getCacheToken();
        $siteId = $this->getSiteID();
        $collectionId = $this->getCollectionID();

        $cachData = "INSERT INTO `cached_results`(`user_id`, `site_id`, `collection_id`, `cache_token`, `response`) 
                            VALUES (
                            '" . $userId . "',
                            '" . $siteId . "',
                            '" . $collectionId . "',
                            '" . $cacheToken . "',
                            '" . mysqli_real_escape_string($connection, $result) . "'
                            )";


        return mysqli_query($connection, $cachData);
    }

    public function deleteCache()
    {
        global $connection;
        $userId = $this->getUserID();
        $collectionId = $this->getCollectionID();

        $deleteCache = "DELETE FROM cached_results WHERE 
            user_id= '" . $userId . "' 
            AND
            collection_id= '" . $collectionId . "'
            ";


        return mysqli_query($connection, $deleteCache);
    }
}
