<?php

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
    private bool $debug;
    private bool $requestHasFiles;
    public function __construct() {
        $this->endpoint = null;
        $this->domain = null;
        $this->headers = [];
        $this->user = null;
        $this->password = null;
        $this->parameters = [];
        $this->response = new ApiResponse();
        $this->debug = false;
        $this->requestHasFiles = false;
        $this->Get();
    }

    public function Debug():void { $this->debug = true; }
    
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
                $result = [CURLOPT_POST, true];
            break;
            case 'PUT':
                $result = [CURLOPT_PUT, true];
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
            $params = $this->requestHasFiles ? $this->parameters : json_encode($this->parameters);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $MethodHeader = $this->GetMethodHeader();

        if(count($MethodHeader) === 2) curl_setopt($ch, $MethodHeader[0], $MethodHeader[1]);
        if(count($this->headers) > 0) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        
        if($this->debug) {
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        $this->response->SetResponse(curl_exec($ch));
        $this->response->SetStatusCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $this->response->SetError(curl_error($ch));
        $this->AfterExecute();
        $this->CheckResponseForToken();
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

    public function AddFile(string $name, string $file_route, string $type='application/octet-stream'):void {
        $this->parameters[$name] = new CURLFile($file_route, $type, uniqid());
        $this->requestHasFiles = true;
    }

    // Header functions
    public function AddHeader(string $HeaderName, string $HeaderValue):void {
        $this->headers[] = sprintf('%s: %s', $HeaderName, $HeaderValue);
    }

    public function ContentType(string $contentType):void {
        $this->AddHeader('Content-Type', $contentType);
    }

    public function Accept(string $acceptType):void {
        $this->AddHeader('Accept', $acceptType);
    }

    // Auth
    public function Authenticate(string $user_key, string $password_key):void {
        $this->Post();
        $this->Accept('application/json');
        $this->ContentType('application/json');
        $this->EndPoint('/api/login');
        $this->AddParameter($user_key, $this->user);
        $this->AddParameter($password_key, $this->password);
        $this->Execute();
        $res = $this->Response();
        
        if($res->StatusCode() !== 200) throw new Exception($res->RawResponse());
    }

    private function CheckResponseForToken():void {
        $response = $this->response->AsJson();
        if(!isset($response->token)) return;
        $this->AddHeader('Authorization', sprintf('Bearer %s', $response->token));
    }
}