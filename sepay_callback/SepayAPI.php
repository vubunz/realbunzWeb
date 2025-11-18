<?php
class SepayAPI
{
    private $apiToken;
    private $baseUrl = 'https://my.sepay.vn/userapi';

    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    private function makeRequest($endpoint, $method = 'GET', $params = [])
    {
        $url = $this->baseUrl . $endpoint;
        if ($method === 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('API request failed: ' . $response);
        }

        file_put_contents('debug_sepay.txt', print_r($response, true));
        return json_decode($response, true);
    }

    public function getTransactionDetails($transactionId)
    {
        return $this->makeRequest("/transactions/details/{$transactionId}");
    }

    public function getTransactions($params = [])
    {
        return $this->makeRequest('/transactions/list', 'GET', $params);
    }

    public function getTransactionCount($params = [])
    {
        return $this->makeRequest('/transactions/count', 'GET', $params);
    }

    public function verifyTransaction($amount, $referenceNumber)
    {
        $params = [
            'amount_in' => $amount,
            'reference_number' => $referenceNumber
        ];

        $result = $this->getTransactions($params);
        return !empty($result['transactions']);
    }
}
