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

// on demand
echo $generator->getTable('Sur mesure', $config->getPostTeams(), $config->getPostWeekends());

// preconfigured
foreach ( $config->getTables() as $table ) {
    echo $generator->getTable($table['name'], $table['teams'], $table['weekends']);
}

