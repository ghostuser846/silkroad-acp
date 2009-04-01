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
        $query = "select et.testcaseid, t.testcase, et.status, et.testrunid, et.start_time, et.end_time, et.silk_log, et.srv_log, et.failure
                    from executed_testcases et, testcases t
                    where et.testcaseid = t.id
                    and et.status in (1,2,3)
                    and ((et.start_time >= '1000/01/01') or (et.start_time is null))
                    and ((et.end_time <= '9999/12/31') or (et.end_time is null))
                    and et.testrunid = 12402
                    and et.testcaseid in (select testcaseid from testplans where testplanid in (2));";
    }
?>


