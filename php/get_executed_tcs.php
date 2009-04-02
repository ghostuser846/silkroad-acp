<?php
    require_once("./choose_db.php");
    require_once("./queries.php");
    //error_reporting(E_ALL);
    header("Content-type: text/xml");
    header("Cache-Control: no-cache");
    $db_host = $_SESSION["silkroad_host"];
    $db_user = $_SESSION["silkroad_login"];
    $db_pwd = $_SESSION["silkroad_pass"];

    $queries = new Queries();
    $link = mysql_connect($db_host, $db_user, $db_pwd)
        or die("Can't connect to DB: " . mysql_error());
    foreach ($_POST as $key => $value)
        $$key = mysql_real_escape_string($value, $link);
    mysql_select_db($queries->getDataBase(), $link) or die("Can't select database: " . mysql_error());

    if ($action == "get_plans") {
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        $result = mysql_query($queries->getTestPlans(), $link);
        if (!$result)
            die("Query to show fields from table failed: " . mysql_error());
        while ($chain = mysql_fetch_array($result)) {
            echo "\t<TestPlan>\n";
            echo "\t\t<ID>$chain[0]</ID>\n";
            echo "\t\t<Name>$chain[1]</Name>\n";
            echo "\t</TestPlan>\n";
        }
        echo "</RootElement>\n";
        mysql_free_result($result);
    }
    if ($action == "get_tests") {
        if ($from_date == "na") $from_date = "1000/01/01";
        if ($to_date == "na") $to_date = "9999/12/31";
        if ($test_plans == "na") $test_plans = "select id from testplan_names";
        if ($test_run == "na") $test_run = "select id from testruns";
        $query = "select tpn.name, et.testrunid, et.testcaseid, t.testcase, et.status, et.start_time, 
                    et.end_time, et.silk_log, et.srv_log, et.failure
                    from executed_testcases et, testcases t, testplans tp, testplan_names tpn
                    where et.testcaseid = t.id
                    and tp.testcaseid = et.testcaseid
                    and tpn.id = tp.testplanid
                    and et.status in ({$statuses})
                    and ((et.start_time >= '{$from_date}') or (et.start_time is null))
                    and ((et.end_time <= '{$to_date}') or (et.end_time is null))
                    and et.testrunid in ({$test_run})
                    and et.testcaseid in (select testcaseid from testplans where testplanid in 
                        ($test_plans))
                    order by tpn.name asc, et.testrunid asc;";
        $result = mysql_query($query, $link);
        if (!$result) die("Query to show fields from table failed: " . mysql_error());
        echo "<?xml version=\"1.0\"?>\n";
        echo "<RootElement>\n";
        $chain_plan = "null_plan";
        $chain_run = "null_run";
        $end_plan = false;
        while ($chain = mysql_fetch_array($result)) {
            if ($chain[0] != $chain_plan) {
                if ($chain_plan != "null_plan") { echo "\t\t</TestRun>\n\t</TestPlan>\n"; $end_plan = true; }
                echo "\t<TestPlan>\n";
                echo "\t\t<PlanName>$chain[0]</PlanName>\n";
                $chain_plan = $chain[0];
            }
            if ($chain[1] != $chain_run) {
                if (($chain_run != "null_run") && !$end_plan) echo "\t\t</TestRun>\n";
                $end_plan = false;
                echo "\t\t<TestRun>\n";
                echo "\t\t\t<RunNumber>$chain[1]</RunNumber>\n";
                $chain_run = $chain[1];
            }
            echo "\t\t\t<TestCase>\n";
            echo "\t\t\t\t<ID>$chain[2]</ID>\n";
            echo "\t\t\t\t<Name>$chain[3]</Name>\n";
            echo "\t\t\t\t<Status>$chain[4]</Status>\n";
            echo "\t\t\t\t<Start>$chain[5]</Start>\n";
            echo "\t\t\t\t<End>$chain[6]</End>\n";
            //echo "\t\t\t\t<SilkLog>$chain[7]</SilkLog>\n";
            //echo "\t\t\t\t<SrvLog>$chain[8]</SrvLog>\n";
            //echo "\t\t\t\t<Failure>$chain[9]</Failure>\n";
            echo "\t\t\t</TestCase>\n";
        }
        echo "\t\t</TestRun>\n";
        echo "\t</TestPlan>\n";
        echo "</RootElement>\n";
        mysql_free_result($result);
    }
?>

