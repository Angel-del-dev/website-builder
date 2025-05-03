<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Request/RequestHeaders.class.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Request/ApiResponse.class.php");

class Request {
    private null|string $endpoint;
    private null|string $domain;
    private array $headers;
    private string|null $user;
    private string|null $password;
    private string $method;
    private array $parameters;
    private ApiResponse $response;
    public function __construct() {
        $this->endpoint = null;
        $this->domain = null;
        $this->headers = [];
        $this->user = null;
        $this->password = null;
        $this->parameters = [];
        $this->response = new ApiResponse();
        $this->Get();
    }

    public function Response():ApiResponse { return $this->response; }

    // Checks
    private function BeforeExecute():void {
        if(is_null($this->domain) || trim($this->domain) === '') throw new Exception('Domain must be specified');
        if(is_null($this->headers) || count($this->headers) === 0) throw new Exception('Headers must be added');
        if(is_null($this->endpoint) || trim($this->endpoint) === '') throw new Exception('Endpoint must be specified');
    }

    private function AfterExecute():void {
        // Removing parameters in case the object needs to be reused for another call
        $this->headers = [];
        $this->parameters = [];
    }

    private function GetMethodHeader():array {
        $result = [];

        switch(strtoupper($this->method)) {
            case 'GET':
                $result = [];
            break;
            case 'POST':
                $result = CURLOPT_POST;
            break;
            case 'PUT':
                $result = CURLOPT_PUT;
            break;
            case 'DELETE':
                $result = [CURLOPT_CUSTOMREQUEST, 'DELETE'];
            break;
            default:
                throw new Exception("Method '{$this->method}' not allowed");
            break;
        }

        return $result;
    }
    // Main function
    public function Execute():void {
        $this->BeforeExecute();

        $canContainParmeters = in_array($this->method, ['POST', 'PUT']);

        $ch = curl_init(sprintf("%s%s", $this->domain, $this->endpoint));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($canContainParmeters) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->parameters));
        }

        $MethodHeader = $this->GetMethodHeader();

        if(count($MethodHeader) === 2) curl_setopt($ch, $MethodHeader[0], $MethodHeader[1]);

        $server_response = curl_exec($ch);
        $this->response->SetStatusCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $this->response->SetResponse($server_response);
        $this->response->SetError(curl_error($ch));

        $this->AfterExecute();
    }

    // Method types

    public function Get():void {
        $this->method = 'GET';
    }
    
    public function Post():void {
        $this->method = 'POST';
    }

    public function Put():void {
        $this->method = 'PUT';
    }

    public function Delete():void {
        $this->method = 'DELETE';
    }
    // Configuration
    public function SetUser(string $user):void {
        $this->user = $user;
    }

    public function SetPassword(string $password):void {
        $this->password = $password;
    }

    public function EndPoint(string $endpoint):void {
        if($endpoint[0] !== '/') $endpoint = sprintf('/%s', $endpoint);
        $this->endpoint = $endpoint;
    }

    public function GetEndPoint():string { return $this->endpoint; }

    public function SetDomain(string $domain):void {
        $this->domain = $domain;
    }

    public function GetDomain():string|null { return $this->domain; }

    // Params
    public function AddParameter(string $name, string $value):void {
        $this->parameters[$name] = $value;
    }

    // Header functions
    public function AddHeader(string $HeaderName, string $HeaderValue):void {
        $this->headers[] = new RequestHeader($HeaderName, $HeaderValue);
    }

    public function ContentType(string $contentType):void {
        $this->AddHeader('Content-Type', $contentType);
    }

    public function Accept(string $acceptType):void {
        $this->AddHeader('Accept', $acceptType);
    }
}