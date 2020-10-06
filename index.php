<html>
    <head>
        <title>Extraction des tables HTML des championnats FFHB de l'EQHB</title>
        <style type="text/css">
            td.s11 { background-color: #ccffcc; }
            td.s13 { background-color: #ff9900; }
            td { border: solid 1px #ccc; border-width: 0 1px 1px 0; }
            table { border-spacing: 0; border-collapse: separate; table-layout: fixed; }
        </style>
    </head>
    <body>
        <h1>Utilitaire de génération de calendriers de compétition de l'EQHB</h1>
        <form id="query" action="" method="post">
            <?php require_once('./src/Services/ConfigurationService.class.php') ?>
            <?php $config = new ConfigurationService; ?>
            <table>
                <tr><td>Équipes:</td><td><input type="text" name="teams" value="<?php echo implode(', ',$config->getPostTeams()) ?>" /></td><td>(ex: <?php echo implode(', ', $config->getTeamEqhbNames()); ?>)</td></tr>
                <tr><td>Week-ends:</td><td><input type="text" name="weekends" value="<?php echo implode(', ', $config->getPostWeekends()) ?>" /></td><td>(ex: 2020-10-03, 2020-10-10)</td></tr>
                <tr><td colspan="3"><input type="submit" name="submit" value="submit" /></td></tr>
            </table>
        </form>
        <div id="result"><?php require('./engine.php'); ?></div>
        <div id="license">
                <h2>License</h2>
                <p>This program fetches data from the Fédération Française de Handball and generates HTML tables ready to be integrated into a website or an other document.</p>
                <p>Copyright (C) 2020 Baptiste LARVOL-SIMON [beta AT e-glop.net]</p>
                <p>This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License.
                <p>This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more details.</p>
                <p>You should have received a copy of the GNU Affero General Public License along with this program.  If not, see <https://www.gnu.org/licenses/>.</p>
                <p>You should retrieve the source code of this program at <a href="https://github.com/betaglop/eqhb-calendar" target="_blank">https://github.com/betaglop/eqhb-calendar</a></p>
        </div>
    </body>
</html>
