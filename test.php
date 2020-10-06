<?php

require_once('./src/Entities/Team.class.php');
require_once('./src/Services/ConfigurationService.class.php');
require_once('./src/Services/FFHBFetcherService.class.php');
require_once('./src/Services/TableGeneratorService.class.php');
require_once('./src/Exceptions/eqhbException.class.php');
require_once('./src/Exceptions/eqhbConfigurationException.class.php');

$config = new ConfigurationService;
$fetcher = new FFHBFetcherService;

$teams = $config->getTeams();
$fetcher->setTeams($teams);

$data = $fetcher->getData();
$generator = new TableGeneratorService($data);
//echo $generator->getTable(['SG3']);
//echo $generator->getTable(['SG1', 'SG2', 'SG3', 'SF1', 'SF2']);
//echo $generator->getTable(null, ['2020-10-03']);
foreach ( $config->getTables() as $table ) {
    echo $generator->getTable($table['name'], $table['teams'], $table['weekends']);
}

