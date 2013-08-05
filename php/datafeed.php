<?php
include_once("dbconfig.php");
include_once("functions.php");
//기본 내용 입력
function addCalendar($st, $et, $sub, $ade){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `REGI_DATE`, `RESV_USER`, `RESV_IPAD`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .php2MySqlTime(js2PhpTime($et))."', '"
      .mysql_real_escape_string($ade)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"
      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"
      .php2MySqlTime(time())."', 1"
      .", '"
      .$_SERVER['REMOTE_ADDR']."' )";
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

//세부 내용 입력
function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $cust_name, $cust_tele, $cust_iden){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `description`, `location`, `color`, `CUST_NAME`, `CUST_TELE`, `CUST_CNUM`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `RMDY_DOCT`, `REGI_DATE`, `RESV_USER`, `RESV_IPAD`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .php2MySqlTime(js2PhpTime($et))."', '"
      .mysql_real_escape_string($ade)."', '"
      .mysql_real_escape_string($dscr)."', '"
      .mysql_real_escape_string($loc)."', '"
      .mysql_real_escape_string($color)."', '"
      .mysql_real_escape_string($cust_name)."', '"
      .mysql_real_escape_string($cust_tele)."', '"
      .mysql_real_escape_string($cust_iden)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"
      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"
      .mysql_real_escape_string($rmdy_doct)."', '"
      .php2MySqlTime(time())."', 1"
      .", '"
      .$_SERVER['REMOTE_ADDR']."' )";
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function listCalendarByRange($sd, $ed){
  $ret = array();
  $ret['events'] = array();
  $ret["issort"] =true;
  $ret["start"] = php2JsTime($sd);
  $ret["end"] = php2JsTime($ed);
  $ret['error'] = null;
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "select * from `jqcalendar` where `starttime` between '"
      .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."' and `DELE_CHCK`='N'";
    $handle = mysql_query($sql);
    //echo $sql;
    while ($row = mysql_fetch_object($handle)) {
      //$ret['events'][] = $row;
      //$attends = $row->AttendeeNames;
      //if($row->OtherAttendee){
      //  $attends .= $row->OtherAttendee;
      //}
      //echo $row->StartTime;
      $ret['events'][] = array(
        $row->Id,
        $row->Subject,
        php2JsTime(mySql2PhpTime($row->StartTime)),
        php2JsTime(mySql2PhpTime($row->EndTime)),
        $row->IsAllDayEvent,
        0, //more than one day event
        //$row->InstanceType,
        0,//Recurring event,
        $row->Color,
        1,//editable
        $row->Location, 
        ''//$attends
      );
    }
	}catch(Exception $e){
     $ret['error'] = $e->getMessage();
  }
  return $ret;
}

function listCalendar($day, $type){
  $phpTime = js2PhpTime($day);
  //echo $phpTime . "+" . $type;
  switch($type){
    case "month":
      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
      break;
    case "week":
      //suppose first day of a week is monday 
      $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
      //echo date('N', $phpTime);
      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
      break;
    case "day":
      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
      break;
  }
  //echo $st . "--" . $et;
  return listCalendarByRange($st, $et);
}
//날짜 및 시간만 변경, 수정자, 수정 IP도 업데이트 됨
function updateCalendar($id, $st, $et){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "update `jqcalendar` set"
      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "
      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "
      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "
      . " `UPDT_USER`=2, "
      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "' "
      . "where `id`=" . $id;
    //echo $sql;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}
//세부 내용 업데이트
function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $cust_name, $cust_tele, $cust_iden){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "update `jqcalendar` set"
      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      . " `subject`='" . mysql_real_escape_string($sub) . "', "
      . " `isalldayevent`='" . mysql_real_escape_string($ade) . "', "
      . " `description`='" . mysql_real_escape_string($dscr) . "', "
      . " `location`='" . mysql_real_escape_string($loc) . "', "
      . " `color`='" . mysql_real_escape_string($color) . "', "
      . " `CUST_NAME`='" . mysql_real_escape_string($cust_name) . "', "
      . " `CUST_TELE`='" . mysql_real_escape_string($cust_tele) . "', "
      . " `CUST_CNUM`='" . mysql_real_escape_string($cust_iden) . "', "
      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "
      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "
      . " `RMDY_DOCT`='" . $rmdy_doct . "', "
      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "
      . " `UPDT_USER`=2, "
      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "' "
      . "where `id`=" . $id;
    //echo $sql;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function removeCalendar($id){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
	//삭제 쿼리를 삭제 플래그로 설정
    //$sql = "delete from `jqcalendar` where `id`=" . $id;
    $sql = "update `jqcalendar` set"
      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "
      . " `UPDT_USER`=3, "
      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "', "
      . " `DELE_CHCK`='Y' "
      . "where `id`=" . $id;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}




header('Content-type:text/javascript;charset=UTF-8');
$method = $_GET["method"];
switch ($method) {
    case "add":
        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"], $_POST["IsAllDayEvent"]);
        break;
    case "list":
        $ret = listCalendar($_POST["showdate"], $_POST["viewtype"]);
        break;
    case "update":
        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"]);
        break; 
    case "remove":
        $ret = removeCalendar( $_POST["calendarId"]);
        break;
    case "adddetails":
        $st = $_POST["stpartdate"] . " " . $_POST["stparttime"];
        $et = $_POST["etpartdate"] . " " . $_POST["etparttime"];
        if(isset($_GET["id"])){
            $ret = updateDetailedCalendar($_GET["id"], $st, $et, 
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_IDEN"]);
        }else{
            $ret = addDetailedCalendar($st, $et,                    
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_IDEN"]);
        }        
        break; 


}
echo json_encode($ret); 



?>