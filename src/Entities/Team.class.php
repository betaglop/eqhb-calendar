<?php

class Team
{
    private $apiUrl; // string
    private $ffhbName; // string
    private $eqhbName; // string
    
    public static function create() {
        return new self;
    }
    
    protected function validateInputString($string) {
        if ( !strval($string) ) {
            throw new Exception('Argument error, it is not a string as required.');
        }
    }
    public function setApiUrl($apiUrl) {
        $this->validateInputString($apiUrl);
        $this->apiUrl = strval($apiUrl);
        return $this;
    }
    public function setFfhbName($ffhbName) {
        $this->validateInputString($ffhbName);
        $this->ffhbName = strval($ffhbName);
        return $this;
    }
    public function setEqhbName($eqhbName) {
        $this->validateInputString($eqhbName);
        $this->eqhbName = strval($eqhbName);
        return $this;
    }
    
    public function getApiUrl() {
        return $this->apiUrl;
    }
    public function getFfhbName() {
        return $this->ffhbName;
    }
    public function getEqhbName() {
        return $this->eqhbName;
    }
    
    public function isFfhbName($mixed) {
        if ( is_array($mixed) ) {
            return in_array($this->ffhbName, $mixed);
        }
        return $mixed === $this->ffhbName;
    }
}
