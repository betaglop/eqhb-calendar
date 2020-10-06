<?php

class FFHBFetcherService
{
    protected $teams = [];
    protected $data = [];
    
    public function __constructor() {
    }
    
    public function addTeam(Team $team) {
        $this->teams[] = $team;
        return $this;
    }
    public function addTeams(array $teams) {
        $this->checkTeamsArray($teams);
        $this->teams = $this->teams + $teams;
        return $this;
    }
    public function setTeams(array $teams) {
        $this->checkTeamsArray($teams);
        $this->teams = $teams;
        return $this;
    }
    public function resetTeams() {
        $this->teams = [];
        return $this;
    }
    
    public function getData($force = false) {
        if ( !$this->data || $force ) {
            $this->data = [];
            foreach ( $this->teams as $team ) {
                $this->addTeamSeason($team);
            }
            ksort($this->data);
        }
        
        return $this->data;
    }
    
    /**
     * @return false if the given $weekend does not exist, and the $weekend data if so
     */
    public function getWeekend($weekend = null, $force = false) {
        if ( !$weekend ) {
            $weekend = date('Y-m-d', strtotime('Next saturday'));
        }
        
        $this->getData($force);
        
        if ( !array_key_exists($weekend, $this->data) ) {
            return false;
        }
        
        return $this->data[$weekend];
    }
    
    protected function addTeamSeason(Team $team) {
        $json = $this->getRemoteJson($team);
        $poule = $this->getDecodedData($json);
        
        foreach ( $poule['dates'] as $d => $date ) {
            foreach ( $date['events'] as $event ) {
                if ( $team->isFfhbName([$event['teams'][0]['name'], $event['teams'][1]['name']]) ) {
                    $this->addDateIfNeeded($date['start']);
                    $home = $this->isMatchHome($event, $team);
                    
                    // add match information
                    $this->data[$date['start']][$team->getEqhbName()] = [
      	                'local' => ['name' => $team->getEqhbName(), 'score' => $event['teams'][$home ? 0 : 1]['score']],
                        'foreign' => ['name' => $event['teams'][$home ? 1 : 0]['name'], 'score' => $event['teams'][$home ? 1 : 0]['score']],
                        'home' => $home,
                        'day' => $event['date']['day'],
                        'date' => [
                            'real' => $event['date']['date'] ? $event['date']['date'] : $date['start'],
                            'start' => $date['start'],
                        ],
                        'hour' => strval($event['date']['hour']).':'.strval($event['date']['minute']),
                        'location' => implode(', ', $event['location']),
                        'referees' => [$event['referees'][0], $event['referees'][1]],
                    ];
                    
                    continue;
                }
            }
        }
        return $this;
    }
    
    protected function isMatchHome(array $event, Team $team) {
        return $team->isFfhbName($event['teams'][0]['name']);
    }
    protected function addDateIfNeeded($date) {
        if ( !array_key_exists($date, $this->data) ) {
            $this->data[$date] = [];
        }
        return $this;
    }
    protected function getRemoteJson(Team $team) {
        return file_get_contents($team->getApiUrl());
    }
    protected function getDecodedData($json) {
        $inflated = gzdecode($json);
        $arr = json_decode($inflated, true);
        return $arr;
    }
    protected function checkTeamsArray(array $teams) {
        foreach ( $teams as $team ) {
            if ( ! $team instanceof Team ) {
                throw new eqbhException('Bad argument, please give an array of Team.');
            }
        }
        return true;
    }
}
