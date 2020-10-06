<?php

require_once(__DIR__.'/../Entities/Team.class.php');
require_once(__DIR__.'/../Exceptions/eqhbConfigurationException.class.php');

class ConfigurationService
{
    private $baseUrl;
    protected $teams = [];
    protected $tables = [];
    protected $post = ['teams' => [], 'weekends' => []];
    
    public function __construct() {
        $fcontent = file_get_contents(__DIR__.'/../../config/config.json');
        $config = json_decode($fcontent, true);
        if ( $config === NULL ) {
            throw new eqhbConfigurationException('Your JSON configuration file is not properly defined, it may be a syntax error.');
        }
        $this
            ->setBaseUrl($config['base_url'])
            ->setTeams($config['teams'])
            ->setTables($config['tables'])
            ->setPostWeekends($_POST['weekends'])
            ->setPostTeams($_POST['teams']);
    }
    
    public static function create() {
        return new self;
    }
    
    public function getTables() {
        return $this->tables;
    }
    public function getTeams() {
        return $this->teams;
    }
    public function getBaseUrl() {
        return $this->baseUrl;
    }
    public function getPostWeekends() {
        return $this->post['weekends'];
    }
    public function getPostTeams() {
        return $this->post['teams'];
    }
    
    protected function setPost($var, $values) {
        if ( !array_key_exists($var, $this->post) ) {
            throw new eqhbConfigurationException("POST variable $var is not expected.");
        }
        $values = explode(',', $values);
        foreach ( $values as $value ) {
            $this->post[$var][] = trim($value);
        }
        return $this;
    }
    public function setPostWeekends($values) {
        $this->setPost('weekends', $values);
        
        // verify values
        foreach ( $this->post['weekends'] as $i => $we ) {
            if ( preg_match('/^\d\d\d\d-\d\d-\d\d/$', $we) !== 1 ) {
                unset($this->post['weekends'][$i]);
            }
        }
        
        return $this;
    }
    public function setPostTeams($values) {
        $this->setPost('teams', $values);
        
        // verify values
        foreach ( $this->post['teams'] as $i => $team ) {
            if ( !in_array($team, $this->getTeamEqhbNames()) ) {
                unset($this->post['teams'][$i]);
            }
        }
        
        return $this;
    }
    public function setTables(array $tables) {
        foreach ( $tables as $i => $table ) {
            if ( !array_key_exists('name', $table) ) {
                throw new eqhbConfigurationException("Table $i must have a name");
            }
            if ( !array_key_exists('teams', $table) ) {
                throw new eqhbConfigurationException("Table $i must have teams defined");
            }
            if ( !is_array($table['teams']) ) {
                throw new eqhbConfigurationException("Table $i must have teams defined as an array");
            }
            if ( !array_key_exists('weekends', $table) ) {
                throw new eqhbConfigurationException("Table $i must have weekends defined");
            }
            if ( !is_array($table['weekends']) ) {
                throw new eqhbConfigurationException("Table $i must have weekends defined as an array");
            }
        }
        
        $this->tables = $tables;
        return $this;
    }
    public function setTeams($teams) {
        foreach ( $teams as $eqhb => $team ) {
            $this->teams[] = Team::create()
                ->setApiURL($this->getBaseUrl().$team['id'])
                ->setFfhbName($team['ffhb'])
                ->setEqhbName($eqhb);
        }
        return $this;
    }
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }
    
    public function getTeamEqhbNames() {
        $arr = [];
        foreach ( $this->getTeams() as $team ) {
            $arr[] = $team->getEqhbName();
        }
        return $arr;
    }
}
