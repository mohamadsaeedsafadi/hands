<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShamCashService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.shamcash.base_url');
        $this->token = config('services.shamcash.token');
    }

    private function request($endpoint, $params = [])
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . $endpoint, $params)->json();
    }

    public function getAccounts()
    {
        return $this->request('/accounts');
    }

    public function getTransactions($accountId, $filters = [])
    {
        return $this->request('/transactions', array_merge([
            'account_id' => $accountId
        ], $filters));
    }
}