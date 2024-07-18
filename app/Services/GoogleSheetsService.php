<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    private $valueRange;
    private $clear;
    private $config;

    protected const SCOPES = ['https://www.googleapis.com/auth/drive',
                            'https://www.googleapis.com/auth/spreadsheets'];
    protected const FIRST_CELL = 'A1';
    protected const LAST_CELL = 'ZZ1000000';
    protected const USER_ENTERED = 'USER_ENTERED';

    public function __construct(Google_Client $client, Google_Service_Sheets_ValueRange $valueRange, Google_Service_Sheets_ClearValuesRequest $clear)
    {
        $this->client = $client;
        $this->valueRange = $valueRange;
        $this->clear = $clear;
    }

    public function init(array $config): self
    {
        $this->config = $config;

        return $this->setClientOptions()->setService();
    }

    private function setClientOptions(): self
    {
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(self::SCOPES);
        $this->client->setAuthConfig($this->config);

        return $this;
    }

    private function setService(): self
    {
        $this->service = new Google_Service_Sheets($this->client);

        return $this;
    }

    public function clearPages(): self
    {
        $sheetId = $this->config['sheetId'];
        foreach ($this->config['pageName'] as $pageName) {
            $pageArea = $pageName . '!' . self::FIRST_CELL . ':' . self::LAST_CELL;
            $this->service->spreadsheets_values->clear($sheetId, $pageArea, $this->clear);
        }

        return $this;
    }

    public function writePages(array $pageTable): self
    {
        $options = ['valueInputOption' => self::USER_ENTERED];
        $sheetId = $this->config['sheetId'];
        foreach ($this->config['pageName'] as $key => $pageName) {
            $this->valueRange->setValues($pageTable[$key]);
            $startCell = $pageName . '!' . self::FIRST_CELL;
            $this->service->spreadsheets_values->update($sheetId, $startCell, $this->valueRange, $options);
        }

        return $this;
    }
}