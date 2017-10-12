<?php

namespace Basic;

class Response{

    protected $body;
    protected $statusCode = 200;
    protected $headers = [];

    public function setBody($body){
        $this->body = $body;
        return $this;
    }

    public function withStatus($code){
        $this->statusCode = $code;
        return $this;
    }

    public function withJson($body){
        $this->withHeaders('Content-Type','application/json');
        $this->body = json_encode($body);
        return $this;
    }

    public function withHeaders($name,$value){
        $this->headers[] = [$name,$value];
        return $this;
    }

    public function getHeaders(){
        return $this->headers;
    }

    public function getStatusCode(){
        return $this->statusCode;
    }

    public function getBody(){
        return $this->body;
    }

}
