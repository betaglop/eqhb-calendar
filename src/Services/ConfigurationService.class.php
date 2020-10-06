<?php

require_once(__DIR__.'/../Entities/Team.class.php');

class ConfigurationService
{
    private $baseUrl;
    protected $teams = [];
    protected $tables = [];
    
    public function __construct() {
        $fcontent = file_get_contents(__DIR__.'/../../config/config.json');
        $config = json_decode($fcontent, true);
        if ( $config === NULL ) {
            throw new eqhbConfigurationException('Your JSON configuration file is not properly defined, it may be a syntax error.');
        }
        $this
            ->setBaseUrl($config['base_url'])
            ->setTeams($config['teams'])
            ->setTables($config['tables']);
    }
    
    public static function create() {
        return new self;
    }
    
    public function getTables() {
        return $this->tables;
    }
    public function getTeams() {
        $teams = [];
        foreach ( $this->teams as $eqhb => $team ) {
            $teams[] = Team::create()
                ->setApiURL($this->getBaseUrl().$team['id'])
                ->setFfhbName($team['ffhb'])
                ->setEqhbName($eqhb);
        }
        return $teams;
    }
    public function getBaseUrl() {
        return $this->baseUrl;
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
        $this->teams = $teams;
        return $this;
    }
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
