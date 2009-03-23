<?php
    error_reporting(E_ALL);
    header("Content-type: text/xml");
    header("Cache-Control: no-cache");

    $db_host = "psslab441";
    $db_user = "root";
    $db_pwd = "root";
    $database = "silkroad";
    $table_tpnames = "testplan_names";
    $table_testchains = "testchains";
    $table_machines = "machines";
    $table_configurations = "configurations";

    $link = mysql_connect($db_host, $db_user, $db_pwd)
        or die("Can't connect to DB: " . mysql_error());
    foreach ($_POST as $key => $value)
        $$key = mysql_real_escape_string($value, $link);
    mysql_select_db($database, $link) or die("Can't select database: " . mysql_error());
    if (@$action == "get_chains") {
        /*$result = mysql_query("select tc.id, tpn.name, tc.is_completed 
            from {$table_testchains} tc inner join {$table_tpnames} tpn 
            on tc.testplanid = tpn.id 
            order by tc.id asc;", $link);*/
        $result = mysql_query("select tc.id, tpn.name, m.name, c.name, tc.is_completed
            from {$table_testchains} tc, {$table_machines} m, {$table_tpnames} tpn, {$table_configurations} c
            where tc.machineid = m.id and tc.testplanid = tpn.id and tc.configid = c.id
            order by tc.id asc;", $link);
        if (!$result)
            die("Query to show fields from table failed: " . mysql_error());
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        while ($chain = mysql_fetch_array($result)) {
            echo "\t<Chain>\n";
            echo "\t\t<ID>$chain[0]</ID>\n";
            echo "\t\t<Name>$chain[1]</Name>\n";
            echo "\t\t<Machine>$chain[2]</Machine>\n";
            echo "\t\t<Config>$chain[3]</Config>\n";
            echo "\t\t<IsCompleted>$chain[4]</IsCompleted>\n";
            echo "\t</Chain>\n";
        }
        echo "</RootElement>\n";
        mysql_free_result($result);
    }
    if (@$action == "get_data_for_add") {
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        $arr = array("Machines", "TestPlans", "Configurations");
        foreach ($arr as $i => $value) {
            switch ($value) {
                case "Machines": {
                    $result = mysql_query("select id, name from {$table_machines};", $link);
                    break;
                }
                case "TestPlans": {
                    $result = mysql_query("select id, name from {$table_tpnames};", $link);
                    break;
                }
                case "Configurations": {
                    $result = mysql_query("select id, name from {$table_configurations};", $link);
                    break;
                }
            }
            if (!$result)
                die("Query to show fields from table failed: " . mysql_error());
            echo "\t<{$value}>\n";
            while ($chain = mysql_fetch_array($result)) {
                echo "\t\t<Element>\n";
                echo "\t\t\t<ID>$chain[0]</ID>\n";
                echo "\t\t\t<Name>$chain[1]</Name>\n";
                echo "\t\t</Element>\n";
            }
            echo "\t</{$value}>\n";
        }
        echo "</RootElement>\n";
        mysql_free_result($result);
    }
    if (@$action == "add_chain") {
        $result = mysql_query("insert into {$table_testchains} 
            (machineID, testplanID, configID, is_completed) 
            values ({$machine}, {$testplan}, {$config}, {$iscompleted});", $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (!$result) {
            echo "\t<Status>Fail</Status>\n";
            echo "</RootElement>\n";
            die("Query to insert fields into table failed: " . mysql_error());
        }
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    mysql_close($link);
?>

