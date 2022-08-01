<?php

namespace App\Library\Services;

use Google\Cloud\Storage\StorageClient;

class GoogleStorage {
    
    /**
     * Instance of Google StorageClient
     * 
     * @var StorageClient
     */
    public $client;

    public function __construct()
    {
        $config = [
            'keyFilePath' => env('GCS_KEY_FILE_PATH'),

        ];

        $this->client = new StorageClient($config);
    }

}