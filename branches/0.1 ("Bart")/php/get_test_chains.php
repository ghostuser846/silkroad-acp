<?php
    require_once("./choose_db.php");
    //error_reporting(E_ALL);
    header("Content-type: text/xml");
    header("Cache-Control: no-cache");
    $db_host = $_SESSION["silkroad_host"];
    $db_user = $_SESSION["silkroad_login"];
    $db_pwd = $_SESSION["silkroad_pass"];
    $database = "silkroad";
    $table_tpnames = "testplan_names";
    $table_testchains = "testchains";
    $table_machines = "machines";
    $table_configurations = "configurations";
    $table_testplans = "testplans";
    $table_testcases = "testcases";
    $table_tcchains = "tcchains";

    $link = mysql_connect($db_host, $db_user, $db_pwd)
        or die("Can't connect to DB: " . mysql_error());
    foreach ($_POST as $key => $value)
        $$key = mysql_real_escape_string($value, $link);
    mysql_select_db($database, $link) or die("Can't select database: " . mysql_error());
    if ($action == "get_chains") {
        $query_chains_not_running = "select tc.id, tpn.name, m.name, c.name, tc.is_completed
                                    from {$table_testchains} tc, {$table_machines} m, {$table_tpnames} tpn,  {$table_configurations} c
                                        where tc.machineid = m.id
                                        and tc.testplanid = tpn.id
                                        and tc.configid = c.id
                                        and (tc.machineid, tc.testplanid) not in
                                            (select distinct first_join.machineid, {$table_testplans}.testplanid
                                            from {$table_testplans} join
                                                    (select {$table_testcases}.id, {$table_tcchains}.machineid
                                                    from {$table_tcchains} join {$table_testcases}
                                                    on {$table_tcchains}.testcase = {$table_testcases}.testcase) first_join
                                                on {$table_testplans}.testcaseid = first_join.id)
                                                order by tc.id asc;";
        $query_chains_running = "select tc.id, tpn.name, m.name, c.name, tc.is_completed
                                    from {$table_testchains} tc, {$table_machines} m, {$table_tpnames} tpn,  {$table_configurations} c
                                        where tc.machineid = m.id
                                        and tc.testplanid = tpn.id
                                        and tc.configid = c.id
                                        and (tc.machineid, tc.testplanid) in
                                            (select distinct first_join.machineid, {$table_testplans}.testplanid
                                            from {$table_testplans} join
                                                    (select {$table_testcases}.id, {$table_tcchains}.machineid
                                                    from {$table_tcchains} join {$table_testcases}
                                                    on {$table_tcchains}.testcase = {$table_testcases}.testcase) first_join
                                                on {$table_testplans}.testcaseid = first_join.id)
                                    order by tc.id asc;";
        $result = mysql_query($query_chains_not_running, $link);
        $result_running = mysql_query($query_chains_running, $link);
        if (!$result || !$result_running)
            die("Query to show fields from table failed: " . mysql_error());
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        echo "\t<NotRunning>\n";
        while ($chain = mysql_fetch_array($result)) {
            echo "\t\t<Chain>\n";
            echo "\t\t\t<ID>$chain[0]</ID>\n";
            echo "\t\t\t<Name>$chain[1]</Name>\n";
            echo "\t\t\t<Machine>$chain[2]</Machine>\n";
            echo "\t\t\t<Config>$chain[3]</Config>\n";
            echo "\t\t\t<IsCompleted>$chain[4]</IsCompleted>\n";
            echo "\t\t</Chain>\n";
        }
        echo "\t</NotRunning>\n";
        echo "\t<Running>\n";
        while ($chain = mysql_fetch_array($result_running)) {
            echo "\t\t<Chain>\n";
            echo "\t\t\t<ID>$chain[0]</ID>\n";
            echo "\t\t\t<Name>$chain[1]</Name>\n";
            echo "\t\t\t<Machine>$chain[2]</Machine>\n";
            echo "\t\t\t<Config>$chain[3]</Config>\n";
            echo "\t\t\t<IsCompleted>$chain[4]</IsCompleted>\n";
            echo "\t\t</Chain>\n";
        }
        echo "\t</Running>\n";
        echo "</RootElement>\n";
        mysql_free_result($result);
    }
    if ($action == "get_data_for_add") {
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
    if ($action == "add_chain") {
        $result = mysql_query("insert into {$table_testchains} 
            (machineID, testplanID, configID, is_completed) 
            values ({$machine}, {$testplan}, {$config}, {$iscompleted});", $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (!$result) {
            echo "\t<Status>Fail</Status>\n";
            echo "</RootElement>\n";
            //die("Query to insert fields into table failed: " . mysql_error());
            exit;
        }
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    if ($action == "delete_all") {
        $result = mysql_query("truncate {$table_testchains};", $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (!$result) {
            echo "\t<Status>Fail</Status>\n";
            echo "</RootElement>\n";
            //die("Can't truncate {$table_testchains}: " . mysql_error());
            exit;
        }
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    if ($action == "delete_not_running") {
        $query = "delete from {$table_testchains}
                    where ({$table_testchains}.machineid, {$table_testchains}.testplanid) not in
                        (select distinct first_join.machineid, {$table_testplans}.testplanid
                        from {$table_testplans} join
                                (select {$table_testcases}.id, {$table_tcchains}.machineid
                                from {$table_tcchains} join {$table_testcases}
                                    on {$table_tcchains}.testcase = {$table_testcases}.testcase) first_join
                            on {$table_testplans}.testcaseid = first_join.id);";
        $result = mysql_query($query, $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (!$result) {
            echo "\t<Status>Fail</Status>\n";
            echo "</RootElement>\n";
            //die("Query to insert fields into table failed: " . mysql_error());
            exit;
        }
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    if ($action == "delete_following") {
        $result = mysql_query("delete from {$table_testchains} where id in ({$chains});", $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (!$result) {
            echo "\t<Status>Fail</Status>\n";
            echo "</RootElement>\n";
            //die("Can't truncate {$table_testchains}: " . mysql_error());
            exit;
        }
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    if ($action == "rotate_chains") {
        // getting current state of table
        // array contains all chains except the running chains
        // keys are ids and values are arrays of data
        $current_content = array();
        // keys of this array are ids of not running chains
        // and values are new ids
        $mapping_between_current_and_rotation = array();

        $temp = array();
        // explode POST data (new sequence of ids)
        $new_rotation_arr = explode(",", $rotations);
        
        $query = "select * from {$table_testchains}
                    where ({$table_testchains}.machineid, {$table_testchains}.testplanid) not in
                        (select distinct first_join.machineid, {$table_testplans}.testplanid
                        from {$table_testplans} join
                                (select {$table_testcases}.id, {$table_tcchains}.machineid
                                from {$table_tcchains} join {$table_testcases}
                                    on {$table_tcchains}.testcase = {$table_testcases}.testcase) first_join
                            on {$table_testplans}.testcaseid = first_join.id);";
        $result = mysql_query($query, $link);
        if (!$result) {
            send_xml_fail_and_exit();
        }
        while ($chain = mysql_fetch_array($result)) {
            // fill current data
            $current_content[$chain[0]] = $chain;
            // $temp contains current sequence of ids
            $temp[] = $chain[0];
        }
        if (count($temp) != count($new_rotation_arr)) {
            send_xml_fail_and_exit();
        } else {
            // build mapping
            for ($i = 0; $i < count($new_rotation_arr); $i++)
                $mapping_between_current_and_rotation[$temp[$i]] = $new_rotation_arr[$i];
        }
        foreach ($mapping_between_current_and_rotation as $key => $value)
            // if mapped ids are not equal than they are under rotation
            // so, rotate them
            // former id ($key) should contain new data from $value id
            if ($key != $value) {
                $query = "update {$table_testchains} set machineid = {$current_content[$value][1]}, 
                    testplanid = {$current_content[$value][2]}, configid = {$current_content[$value][3]}, 
                    is_completed = {$current_content[$value][4]} where id = {$key};";
                $result = mysql_query($query, $link);
                if (!$result)
                    send_xml_fail_and_exit();
            }
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        echo "\t<Status>OK</Status>\n";
        echo "</RootElement>\n";
    }
    mysql_close($link);
    function send_xml_fail_and_exit() {
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        echo "\t<Status>Fail</Status>\n";
        echo "</RootElement>\n";
        exit;
    }
?>

