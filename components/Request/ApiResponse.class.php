<?php

class ApiResponse {
    private null|string $response;
    private int $status_code;
    public function __construct() {
        $this->response = null;
        $this->status_code = 0;
    }

    public function SetResponse(string $response):void {
        $this->response = $response;
    }

    public function SetStatusCode(int $status_code):void {
        $this->status_code = $status_code;
    }

    public function StatusCode():int { return $this->status_code; }
    public function RawResponse():string { return $this->response; }
}