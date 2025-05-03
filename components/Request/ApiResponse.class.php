<?php

class ApiResponse {
    private null|string $response;
    private int $status_code;
    private null|string $error;
    public function __construct() {
        $this->response = null;
        $this->status_code = 0;
        $this->error = null;
    }

    public function SetResponse(string $response):void {
        $this->response = $response;
    }

    public function SetStatusCode(int $status_code):void {
        $this->status_code = $status_code;
    }

    public function SetError(string $error):void {
        $this->error = $error;
    }

    public function StatusCode():int { return $this->status_code; }
    public function RawResponse():string { return $this->response; }
    public function Error():string { return $this->error; }

    public function AsJson():stdClass {
        $response = json_decode($this->response) ?? new stdClass();
        if(!isset($response->data)) return $response;
        return json_decode($response->data);
    }
}