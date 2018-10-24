<?php // TAS Sync program
function replicateTimeTable($configs, $room, $subject) {
    $roomToID = array();

    // Get lab room list
    echo "TASSynchronizer.replicateTimeTable(): Collecting Room Information, sucessful = ";
    // $r = getRemoteRoomList($configs->RBS, $roomToID); // need rbs account
    $r = getRoomList($roomToID);
    echo $r . "\n";

    // Get TAS Info
    $staffHT = array();
    $subjectHT = array();
    $teachingRequirementHT = array();

    echo "TASSynchronizer.replicateTimeTable(): Collecting TAS Basic Information, sucessful = ";
    $r = false;
    try {
        $conn = oci_connect($configs->TAS->username, $configs->TAS->password, $configs->TAS->db);
        if (!$conn) die("Connection failed: " . oci_error());

        getStaffHT($conn, $configs->period, $staffHT);
        getSubjectHT($conn, $configs->period, $subjectHT);
        getTeachingRequirementHT($conn, $configs->period, $staffHT, $teachingRequirementHT);
        oci_close($conn);
        $r = true;
    } catch (Exception $e) {
        echo $e->getMessage();
    }		
    echo $r . "\n\n";

    // Make conditions
    $condition="";
    $delCondition="";
    
    $condition = "{$condition}a.Period='{$configs->period}' and a.STerm<={$configs->sem} and {$configs->sem}<=a.ETerm"; 
    $delCondition = "{$delCondition}tas_import=1 and tas_period='{$configs->period}' and tas_sem='{$configs->sem}'";
    if ($room !== null) {
        $condition = "{$condition} and venue='{$room}'"; 
        $delCondition = "{$delCondition} and room_id={$roomToID['room']}"; // Need data from rbs
    }
    if ($subject !== null) {
        $condition = "{$condition} and a.subject_code='{$subject}'"; 
        $delCondition = "{$delCondition} and tas_subject_code='{$subject}'";
    }

    // Get assignment timetable
    echo "Replicating Assignment TimeTable having condition {$condition}";
    echo "\nTASSynchronizer.replicateTimeTable() : Connecting to DB {$configs->TAS->db} by {$configs->TAS->username}\n";

    $conn = oci_connect($configs->TAS->username, $configs->TAS->password, $configs->TAS->db);
    if (!$conn) die("Connection failed: " . oci_error());

    $query = "select JobNo,subject_code,shour,ehour,wday,venue from assignment_timetable a where {$condition}" . 
        " group by JobNo,subject_code,shour,ehour,wday,venue" . 
        " order by a.subject_code";
    $stid = oci_parse($conn, $query);
    $r = oci_execute($stid);
    // $r ? delRepetition($configs->RBS, $delCondition) : null; // Need rbs account
        
    // TAS Synchronizer start replicate time table
    $count = 0;
    $done = 0;
    $login = Api_RbsAuth($configs->RBS);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
        try {
            $count++;
            $jobno = $row["JOBNO"];
            $subjectCode = $row["SUBJECT_CODE"];
            $wday = $row["WDAY"];
            $shour = $row["SHOUR"];
            $ehour = $row["EHOUR"];
            $venue = $row["VENUE"];

            echo "TASSynchronizer.replicateTimeTable(): Processing {$subjectCode} on {$wday} {$shour}-{$ehour} at {$venue}";
            echo $subjectHT[$subjectCode][1];
            if(empty($subjectHT[$subjectCode][1])) {
                throw new Exception("*** ERROR: TASSynchronizer.replicateTimeTable(): subject title of {$subjectCode} not available");
            }
            $subjectTitle = $subjectHT[$subjectCode][1];

            if(empty($teachingRequirementHT[$jobno]["staffHT"])) {
                throw new Exception("*** ERROR: TASSynchronizer.replicateTimeTable(): Teaching Requirement of {$jobno} subject code {$subjectCode} not available");
            }
            $sNameList = getStaffNameList($teachingRequirementHT[$jobno]["staffHT"]); // return StaffNameList in string
            $description = "{$subjectTitle} ({$sNameList})";
            echo "TASSynchronizer.replicateTimeTable(): by {$sNameList}";

            if (convertToDayOfWeek($wday) != "-1" && isset($roomToID[$venue])) {
                $synDate = getCurrentDateFormatted();
                $done++;
                // TODO: Need to modified ht field name to fit new RBS JSON request format 
                $ht = (object) array(
                    "name" => $subjectCode,
                    "description" => $description,
                    "start_day" => $configs->start_day,
                    "start_month" => $configs->start_month,
                    "start_year" => $configs->start_year,
                    "end_day" => $configs->start_day,
                    "end_month" => $configs->start_month,
                    "end_year" => $configs->start_year,
                    "rooms[]" => $roomToID[$venue],
                    "type" => "I",
                    "confirmed" => "1",
                    "private" => "0",
                    "f_tas_import" => "1",					
                    "f_tas_period" => $configs->period,
                    "f_tas_sem" => $configs->sem,
                    "f_tas_user_comp_acc" => "",
                    "rep_type" => "2",
                    "rep_end_day" => $configs->end_day,
                    "rep_end_month" => $configs->end_month,
                    "rep_end_year" => $configs->end_year,
                    "rep_num_weeks" => "",
                    "returl" => "",
                    "create_by" => "cspaulin",
                    "rep_id" => "0",
                    "edit_type" => "series",
                    "f_tas_subject_code" => $subjectCode,
                    "f_tas_syndate" => $synDate
                );
                // print out all array item (var_dump)
                echo "No problem so far<br>";
                // callInsertBookingURL($ht, $configs->RBS);
            } else {
                echo "Not replicating {$subjectCode} by {$sNameList} {$wday} {$shour}-{$ehour} at {$venue}<br>\n";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    Api_RbsSignOut($login, $configs->RBS);
    echo "Count Matching condition = {$count}, done = {$done}\n";
    oci_close($conn);

    echo "TASSynchronizer.replicateTimeTable() : Finished\n\n";
}

// Test: Done
function getStaffHT($conn, $period, &$staffHT) {
    $query = "select sid,sname from staff where period='{$period}'";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
        $sid = $row["SID"];
        $sname = $row["SNAME"];
        $staff = array($sid,$sname); // Staff

        $staffHT[$sid] = $staff;
    }
    oci_free_statement($stid);
}

// Test: Done
function getSubjectHT($conn, $period, &$subjectHT) {
    $query = "select subject_code,subject_title from subject where period='{$period}'";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
        $code = $row["SUBJECT_CODE"];
        $title = $row["SUBJECT_TITLE"];
        $subject = array($code,$title);
        
        $subjectHT[$code] = $subject;
    }
    oci_free_statement($stid);
}

// Test: Done
function getTeachingRequirementHT($conn, $period, $staffHT, &$teachingRequirementHT) {
    $query = "select jobno,subject_code,c_code,a_code from Teaching_Requirement where period='{$period}'";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
        $jobno = $row["JOBNO"];
        $s_code = $row["SUBJECT_CODE"];
        $c_code = $row["C_CODE"];
        $a_code = $row["A_CODE"];

        $teachingRequirement = array($jobno,$s_code,$c_code,$a_code);
        $teachingRequirement["staffHT"] = getTeachingRequirementStaff($conn,$staffHT,$period,$jobno);

        $teachingRequirementHT[$jobno] = $teachingRequirement;
    }
    oci_free_statement($stid);
}

// Test: Done
function getTeachingRequirementStaff($conn, &$staffHT, $period, $jobno) {
    $sHT = array();
    $query = "select sid from assignment_timetable where jobno='{$jobno}' and period='{$period}'";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
        $sid = $row["SID"];
        $sHT[$sid] = $staffHT[$sid];
    }
    oci_free_statement($stid);
    return $sHT;
}

// May not required
function getRemoteRoomList($rbs, &$roomToID) {
    $r = false;
    try {
        $rbsconn = new mysqli($rbs->db, $rbs->username, $rbs->password);
        if ($rbsconn->connect_error) {
            throw new Exception("Connection failed: " . $rbsconn->connect_error);
        }
        $sql = "select * from mrbs_comp_lab;";
        $rs = $rbsconn->query($sql);
        if ($rs->num_rows > 0) {
            while ($row = $rs->fetch_assoc()) {
                $id = $row["ID"];
                $room = $row["ROOM_NAME"];

                $roomToID[$room] = $id;
            }
        }
        $rbsconn->close();            
        $r = true;           		
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $r;
}

// PENDING: require rbs mysql account (currently cannot access)
function delRepetition($rbs, $delCondition) {
    $rbsconn = new mysqli($rbs->db, $rbs->username, $rbs->password);
    if ($rbsconn->connect_error) {
        die("Connection failed: " . $rbsconn->connect_error);
    }
    echo "Removing record from RBS before replicatiion for condition {$delCondition} .... ";
    
    $sql = "delete from mrbs_entry where {$delCondition}";
    if ($rbsconn->query($sql) === true) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $rbsconn->error;
    }

    $sql = "delete from mrbs_repeat where {$delCondition}";
    if ($rbsconn->query($sql) === true) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $rbsconn->error;
    }
    $rbsconn->commit();  
    $rbsconn->close(); 
    echo "Done!\n";
}

/**
 * @throws Exception if operation fail
 */
function callInsertBookingURL($ht, $login, $rbs) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $rbs->Api_Reserve,
        CURLOPT_HTTPHEADER => array(
            "X-Booked-SessionToken: $login->sessionToken",
            "X-Booked-UserId: $login->userId"
        ),
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode($ht)
    ));
    $res = (object) json_decode(curl_exec($ch), true);
    curl_close($ch); // Terminate

    // TODO: Validate request (should throw exception???)
    echo var_dump($res);
}

// ---------------util function--------------//
// May not Required
function convertToSeconds($t) {
    return (strtotime($t) - strtotime('TODAY'));
}

function convertToDayOfWeek($t) {
    return date('N', strtotime($t));
}

function getCurrentDateFormatted() {
    date_default_timezone_set('Asia/Hong_Kong');
    $date = new DateTime();
    return "{$date->format('Y-m-d h:i:s')}";
}

function getStaffNameList($staffHT) {
    $str = "";
    foreach($staffHT as $sid => $staff) {
        $str = $str . " " . $staff[1];
    }
    return $str;
}

function getRoomList(&$roomToID) {
    $roomToID = array(
        "QT402" => 1,
        "QT417" => 2,
        "QR511" => 3,
        "PQ604A" => 4,
        "PQ604B" => 5,
        "PQ604C" => 6,
        "PQ605" => 7,
        "PQ606" => 8,
        "PQ603" => 9,
        "P503" => 10,
        "P507" => 16,
        "P505" => 15,
        "P504" => 14
    );
    return true;
}

//-----------Api function-----------//
/**
 * Test: Done
 * @throws Exception if operation fail
 */
function Api_RbsAuth($rbs) {
    $data = (object) array(
        'username' => trim($rbs->loginEmail),
        'password' => trim($rbs->loginPassword),
    );
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $rbs->Api_Auth,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode($data)
    ));
    $res = (object) json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (!$res->isAuthenticated) {
        throw new Exception("Failed to Authenticated...");
    }
    return $res; // return json assoc array
}

// Test: Done
function Api_RbsSignOut($login, $rbs) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $rbs->Api_SignOut,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode((object) array(
            'userid' => $login->userId,
            'sessionToken' => $login->sessionToken
        ))
    ));
    curl_exec($ch);
    curl_close($ch);
    echo "RBS Logging out...Token has expired\n";
}

function getMockHT() {
    // TODO: make a mock reservation json request
    $ht = (object) array(
        "name" => "lalala",
        "description" => "des",
    );
    return $ht;
}

//-----------Test connection & Mock function-----------//
function testRemoteConn($rbs) {
    try {
        $rbsconn = new mysqli($rbs->host, $rbs->username, $rbs->password, $rbs->db);
        if ($rbsconn->connect_error) {
            throw new Exception("Connection failed: " . $rbsconn->connect_error . "\n");
        }
        $rbsconn->close();
        echo "TASSynchronizer.testRemoteConn : Finished\n";

    } catch (Exception $e) {
        echo "TASSynchronizer.testRemoteConn : Error while creating ORA-Conn Object\n";
        echo $e->getMessage();	
    }
}

function testLocalConn($tas) {
    try {
        echo "TASSynchronizer.testLocalConn : Using Oracle\n";
        echo "TASSynchronizer.testLocalConn : Connecting to {$tas->db} by {$tas->username} with password length " . strlen($tas->password) . "\n";
        
        $conn = oci_connect($tas->username, $tas->password, $tas->db);
        if (!$conn) {
            throw new Exception("Connection failed: " . oci_error() . "\n");
        }
        // Can add test case in here

        oci_close($conn);
        echo "TASSynchronizer.testLocalConn : Finished\n";

    } catch (Exception $e) {
        echo "TASSynchronizer.testLocalConn : Error while creating ORA-Conn Object\n";
        echo $e->getMessage();
    }
}

// For testing token request
function mockRbsRequest($rbs) {
    $login = Api_RbsAuth($rbs);
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://devrbs.comp.polyu.edu.hk/Web/Services/index.php/Attributes/10',
        CURLOPT_HTTPHEADER => array(
            "X-Booked-SessionToken: $login->sessionToken",
            "X-Booked-UserId: $login->userId"
        )
    ));
    $res = (object) json_decode(curl_exec($ch), true);
    curl_close($ch);

    echo var_dump($res);
    Api_RbsSignOut($login, $rbs);
}

//---------------Main------------------//
function start() {
    $configs = include('config.php');
    echo "Replicating TAS Timetable\n";	
    // testRemoteConn($configs->RBS);
    // testLocalConn($configs->TAS);
    mockRbsRequest($configs->RBS);
    // replicateTimeTable($configs, null, null);
}

start();

?>