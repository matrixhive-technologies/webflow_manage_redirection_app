<?php

error_reporting(1);
ini_set('display_errors', true);
require_once('config/app.php');


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
            } else if ($this->method == 'PUT') {
                $result = $this->put();
                $this->response = $result;
            } else if ($this->method == 'POST') {
                $result = $this->post();
                $this->response = $result;
            } else if ($this->method == 'DELETE') {
                $result = $this->delete();
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
        return $result;
    }

    public function patch()
    {
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

    public function put()
    {
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

    public function delete()
    {
        $baseUrl = $this->apiBaseUrl;
        $url = $baseUrl . $this->endPoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);


        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $asad = ['message' => 'delete done', 'code' => 200];
        return json_encode($asad);
    }
}
