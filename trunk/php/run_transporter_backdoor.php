<?php
    require_once("./choose_db.php");
    require_once("./queries.php");
    require_once("./database.php");
    //error_reporting(E_ALL);
    header("Content-type: text/xml");
    header("Cache-Control: no-cache");
    $queries = new Queries();
    foreach ($_POST as $key => $value)
        $$key = mysql_escape_string($value);

    if ($action == "test_connection") {
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        if (test_connect_to_silkroad($dest_host, $dest_user, $dest_pass) && test_connect_to_silkroad($source_host, $source_user, $source_pass))
            echo "\t<Result>Success</Result>\n";
        else
            echo "\t<Result>Failure</Result>\n";
        echo "</RootElement>\n";
    }

    if ($action == "transport_run") {
        // Following vars were created from POST request:
        // $dest_host: Destination host
        // $dest_user: Destination user
        // $dest_pass: Destination pass
        // $source_host: Source host
        // $source_user: Source user
        // $source_pass: Source pass
        // $run: Run to transport from source to destination
        // $config: Destination config
        echo "<RootElement>\n";
        echo "\t<TransportLog>\n";
        $source = new Database($source_host, $source_user, $source_pass, "silkroad");
        $dest = new Database($dest_host, $dest_user, $dest_pass, "silkroad");
        $source->select("executed_testcases", "testrunid = $run");
        $id = $dest->get_max("executed_testcases", 'id') + 50;
        echo "\t\tStarting to transport run " . $run . " from " . $source_host . " to " . $dest_host . " with config " . $config . "\r\n";
        echo "\t\tFirst executed tc id is " . $id . "\r\n";
        $new_testrun = $dest->get_max("testruns", 'id') + 10;
        echo "\t\tNew testrun id is " . $new_testrun . "\r\n";

        echo "\t\tCopying executed tescases (NOTE: NC tests will have following end_date: 9999/12/31 00:00:00)\r\n";
        while($a = $source->get()) {
            $source_etc_id = $a['id'];
            $a['id'] = $id++;
            $a['testrunID'] = $new_testrun;
            if (empty($a['end_time'])) $a['end_time'] = "9999/12/31 00:00:00";
            $dest->insert("executed_testcases", $a);
            echo "\t\tExecuted tc " . $source_etc_id . " was copied to destination with new id " . ($id - 1) . "\r\n";
        }

        $source->select("testruns", "id = $run");
        $a = $source->get();
        $a['id'] = $new_testrun;
        $a['configID'] = $config;
        $dest->insert("testruns", $a);
        echo "\t\tRun was transported\r\n";
        echo "\t</TransportLog>\n";
        echo "</RootElement>\n";
    }

    if ($action == "get_dest_configs") {
        $link = mysql_connect($host, $user, $pass);
        mysql_select_db("silkroad", $link);
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        $result = mysql_query($queries->getConfigurations(), $link);
        if (!$result)
            die("Query to show fields from table failed: " . mysql_error());
        while ($chain = mysql_fetch_array($result)) {
            echo "\t\t<Config>\n";
            echo "\t\t\t<ID>$chain[0]</ID>\n";
            echo "\t\t\t<Name>$chain[1]</Name>\n";
            echo "\t\t</Config>\n";
        }
        echo "</RootElement>\n";
        mysql_free_result($result);
        mysql_close($link);
    }


    function test_connect_to_silkroad($host, $user, $pass) {
        error_reporting(E_ERROR);
        $ret = true;
        $link = mysql_connect($host, $user, $pass);
        if ($link == false)
            $ret = false;
        else
            if (!mysql_select_db("silkroad", $link))
                $ret = false;
        if ($ret) mysql_close($link);
        return $ret;
    }
?>
