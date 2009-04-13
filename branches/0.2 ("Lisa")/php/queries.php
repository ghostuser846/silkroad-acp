<?php
    class Queries {
        private $database;
        private $table_tpnames;
        private $table_testchains;
        private $table_machines;
        private $table_configurations;
        private $table_testplans;
        private $table_testcases;
        private $table_tcchains;

        function __construct() {
            $this->database = "silkroad";
            $this->table_tpnames = "testplan_names";
            $this->table_testchains = "testchains";
            $this->table_machines = "machines";
            $this->table_configurations = "configurations";
            $this->table_testplans = "testplans";
            $this->table_testcases = "testcases";
            $this->table_tcchains = "tcchains";
        }
        public function getNotRunningChains() {
            return "select tc.id, tpn.name, m.name, c.name, tc.is_completed
                    from {$this->table_testchains} tc, {$this->table_machines} m, 
                    {$this->table_tpnames} tpn,  {$this->table_configurations} c
                    where tc.machineid = m.id
                    and tc.testplanid = tpn.id
                    and tc.configid = c.id
                    and (tc.machineid, tc.testplanid) not in
                        (select distinct first_join.machineid, {$this->table_testplans}.testplanid
                        from {$this->table_testplans} join
                            (select {$this->table_testcases}.id, {$this->table_tcchains}.machineid
                            from {$this->table_tcchains} join {$this->table_testcases}
                            on {$this->table_tcchains}.testcase = {$this->table_testcases}.testcase) first_join
                        on {$this->table_testplans}.testcaseid = first_join.id)
                    order by tc.id asc;";
        }
        public function getRunningChains() {
            return "select tc.id, tpn.name, m.name, c.name, tc.is_completed
                    from {$this->table_testchains} tc, {$this->table_machines} m, 
                    {$this->table_tpnames} tpn,  {$this->table_configurations} c
                    where tc.machineid = m.id
                    and tc.testplanid = tpn.id
                    and tc.configid = c.id
                    and (tc.machineid, tc.testplanid) in
                        (select distinct first_join.machineid, {$this->table_testplans}.testplanid
                        from {$this->table_testplans} join
                            (select {$this->table_testcases}.id, {$this->table_tcchains}.machineid
                            from {$this->table_tcchains} join {$this->table_testcases}
                            on {$this->table_tcchains}.testcase = {$this->table_testcases}.testcase) first_join
                        on {$this->table_testplans}.testcaseid = first_join.id)
                    order by tc.id asc;";
        }
        public function getMachines() {
            return "select id, name from {$this->table_machines};";
        }
        public function getTestPlans() {
            return "select id, name from {$this->table_tpnames};";
        }
        public function getConfigurations() {
            return "select id, name from {$this->table_configurations};";
        }
        public function getInsertNewChain($machine, $testplan, $config, $is_completed) {
            return "insert into {$this->table_testchains} (machineID, testplanID, configID, is_completed)
                values ({$machine}, {$testplan}, {$config}, {$is_completed});";
        }
        public function getTruncateTestChains() {
            return "truncate {$this->table_testchains};";
        }
        public function getDeleteNotRunningChains() {
            return "delete from {$this->table_testchains}
                    where ({$this->table_testchains}.machineid, {$this->table_testchains}.testplanid) not in
                        (select distinct first_join.machineid, {$this->table_testplans}.testplanid
                        from {$this->table_testplans} join
                            (select {$this->table_testcases}.id, {$this->table_tcchains}.machineid
                            from {$this->table_tcchains} join {$this->table_testcases}
                        on {$this->table_tcchains}.testcase = {$this->table_testcases}.testcase) first_join
                    on {$this->table_testplans}.testcaseid = first_join.id);";
        }
        public function getDeleteFollowingChains($chains) {
            return "delete from {$this->table_testchains} where id in ({$chains});";
        }
        public function getAllTestChainsData() {
            return "select * from {$this->table_testchains}
                    where ({$this->table_testchains}.machineid, {$this->table_testchains}.testplanid) not in
                        (select distinct first_join.machineid, {$this->table_testplans}.testplanid
                        from {$this->table_testplans} join
                            (select {$this->table_testcases}.id, {$this->table_tcchains}.machineid
                            from {$this->table_tcchains} join {$this->table_testcases}
                            on {$this->table_tcchains}.testcase = {$this->table_testcases}.testcase) first_join
                        on {$this->table_testplans}.testcaseid = first_join.id);";
        }
        public function getUpdateChain($machineid, $testplanid, $configid, $iscompleted, $id) {
            return "update {$this->table_testchains} set machineid = {$machineid}, 
                    testplanid = {$testplanid}, configid = {$configid}, 
                    is_completed = {$iscompleted} where id = {$id};";
        }
        public function getDataBase() {
            return $this->database;
        }
    }
?>
