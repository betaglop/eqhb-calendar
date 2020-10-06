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
        <form id="query" action="" method="post">
            <table>
                <tr><td>Équipes:</td><td><input type="text" name="teams" value="" /></td><td>(ex: "SF1", "SG1", "SG3")</td></tr>
                <tr><td>Week-ends:</td><td><input type="text" name="teams" value="" /></td><td>(ex: "2020-10-03", "2020-10-10")</td></tr>
                <tr><td colspan="3"><input type="submit" name="submit" value="submit" /></td></tr>
            </table>
        </form>
        <div id="result"><?php require('./test.php'); ?></div>
    </body>
</html>
