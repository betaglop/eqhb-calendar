<?php

class TableGeneratorService
{
    protected $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function getTable($name = '', $teams = [], $dates = []) {
        $table = $this->buildTable($teams, $dates);
        
        $r = "<table>\n";
        foreach ( $table as $i => $row ) {
            if ( $i == 0 ) {
                $r .= "  <thead>\n";
                $r .= '    <tr><th colspan="'.count($row).'">'.$name.'</th></tr>'."\n";
                $teams = $table[0]; // get the first row containing team names
            }
            
            $r .= "    <tr>\n";
            foreach ( $teams as $team => $idcell ) {
                if ( !isset($row[$idcell]) ) {
                    $r .= "      <td></td>\n";
                    continue;
                }
                
                $cell = $row[$idcell];
                $class = $team.' ';
                if ( is_array($cell) ) {
                    $class .= $cell[0];
                    $cell  = $cell[1];
                }
                
                $r .= '      ';
                $r .= '<td class="'.$class.'">';
                $r .= nl2br($cell);
                $r .= "</td>\n";
            }
            $r .= "    </tr>\n";
            
            if ( $i == 0 ) {
                $r .= "  </thead>\n";
                $r .= "  <tbody>\n";
            }
        }
        $r .= "</tbody>\n";
        $r .= "</table>";
        
        return $r;
    }
    
    protected function buildTable($teams = [], $dates = []) {
        $body = [];
        $head = [''];
        foreach ( $this->data as $d => $date ) {
            if ( $dates && !in_array($d, $dates) ) {
                continue;
            }
            
            $body[$d] = ['' => $d];
            
            foreach ( $date as $team => $match ) {
                if ( $teams && !in_array($team, $teams) ) {
                    continue;
                }
                
                $head[$team] = $team;
                $body[$d][$team] = [$this->getCellClass($match), $this->getContent($match)];
            }
        }
        return [$head] + $body;
    }
    
    protected function getCellClass($match) {
        return $match['home'] ? 's11' : 's13';
    }
    protected function getContent($match) {
        // where
        $location = $match['location'] == ', , ' ? '' : "\n".$match['location'];
        
        // when
        $hour = $match['hour'] == ':' ? '' : $match['hour'];
        $date = $match['date']['real'] == $match['date']['start'] ? $hour : $match['date']['real'].' - '.$hour;
        $date = $date == '' ? '' : "\n$date";
        
        // result
        if ( $match['home'] ) {
            $match['local']['score'] = '<strong>'.$match['local']['score'].'</strong>';
        }
        else {
            $match['foreign']['score'] = '<strong>'.$match['foreign']['score'].'</strong>';
        }
        $result = "\n".$match['local']['score'].' - '.$match['foreign']['score'];
        
        // compilation
        return $match['foreign']['name'].$location.$date.$result;
    }
}
