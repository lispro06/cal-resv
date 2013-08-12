<?php
   	define('__ZBXE__', true);
	include("../files/config/db.config.php");
	$dbname=$db_info->master_db['db_userid'];
	$dbpass=$db_info->master_db['db_password'];
class DBConnection{
	function getConnection($dbname,$dbpass){
	  //change to your database server/user name/password
		mysql_connect("localhost",$dbname,$dbpass) or
         die("Could not connect: " . mysql_error());
    //change to your database name
		mysql_select_db($dbname) or 
		     die("Could not select database: " . mysql_error());
	}
}
    $db = new DBConnection();
    $db->getConnection($dbname,$dbpass);
include_once("functions.php");
//±âº» ³»¿ë ÀÔ·Â
function addCalendar($st, $et, $sub, $ade, $re){
  $ret = array();
  try{
    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `REGI_DATE`, `REGI_EMPL`, `RESV_IPAD`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .php2MySqlTime(js2PhpTime($et))."', '"
      .mysql_real_escape_string($ade)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"
      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"
      .php2MySqlTime(time())."', '"
	  .$re."', '"
      .$_SERVER['REMOTE_ADDR']."' )";
    //echo($sql);
if(js2PhpTime($st)>time()){
    if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
    }else{
      $ret['IsSuccess'] = false;
      $ret['Msg'] = '과거 날짜에는 예약할 수 없습니다.';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

//¼¼ºÎ ³»¿ë ÀÔ·Â
function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $cust_name, $cust_tele, $cust_cnum, $cust_gubn, $rmdy_code, $clnc_code, $clnc_gubn, $oper_chck, $inet_flag, $tele_flag, $resv_memo, $re){
  $ret = array();
  try{
    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `description`, `location`, `color`, `CUST_NAME`, `CUST_TELE`, `CUST_CNUM`, `CUST_GUBN`, `RMDY_CODE`, `CLNC_CODE`, `RMDY_DOCT`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `REGI_DATE`, `REGI_EMPL`, `OPER_CHCK`, `INET_FLAG`, `TELE_FLAG`, `CLNC_GUBN`, `RESV_MEMO`, `RESV_IPAD`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .php2MySqlTime(js2PhpTime($et))."', '"
      .mysql_real_escape_string($ade)."', '"
      .mysql_real_escape_string($dscr)."', '"
      .mysql_real_escape_string($loc)."', '"
      .mysql_real_escape_string($color)."', '"
      .mysql_real_escape_string($cust_name)."', '"
      .mysql_real_escape_string($cust_tele)."', '"
      .mysql_real_escape_string($cust_cnum)."', '"
      .mysql_real_escape_string($cust_gubn)."', '"
      .mysql_real_escape_string($rmdy_code)."', '"
      .mysql_real_escape_string($clnc_code)."', '"
      .mysql_real_escape_string($rmdy_doct)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"
      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"
      .php2MySqlTime(time())."', '"
	  .$re."', '"
      .mysql_real_escape_string($oper_chck)."', '"
      .mysql_real_escape_string($inet_flag)."', '"
      .mysql_real_escape_string($tele_flag)."', '"
      .mysql_real_escape_string($clnc_gubn)."', '"
      .mysql_real_escape_string($resv_memo)."', '"
      .$_SERVER['REMOTE_ADDR']."' )";
    //echo($sql);
if(js2PhpTime($st)>time()){
    if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
}else{
      $ret['IsSuccess'] = false;
      $ret['Msg'] = '과거 시간에는 예약하지 못합니다.';
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
//³¯Â¥ ¹× ½Ã°£¸¸ º¯°æ, ¼öÁ¤ÀÚ, ¼öÁ¤ IPµµ ¾÷µ¥ÀÌÆ® µÊ
function updateCalendar($id, $st, $et, $uu){
  $ret = array();
  try{
    $sql = "update `jqcalendar` set"
      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "
      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "
      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "
      . " `UPDT_USER`='" . $uu . "', "
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
//¼¼ºÎ ³»¿ë ¾÷µ¥ÀÌÆ®
function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $cust_name, $cust_tele, $cust_cnum, $cust_gubn, $rmdy_code, $clnc_code, $clnc_gubn, $oper_chck, $inet_flag, $tele_flag, $resv_memo, $uu){
  $ret = array();
  try{
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
      . " `CUST_CNUM`='" . mysql_real_escape_string($cust_cnum) . "', "
      . " `CUST_GUBN`='" . mysql_real_escape_string($cust_gubn) . "', "
      . " `RMDY_CODE`='" . mysql_real_escape_string($rmdy_code) . "', "
      . " `CLNC_CODE`='" . mysql_real_escape_string($clnc_code) . "', "
      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "
      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "
      . " `RMDY_DOCT`='" . $rmdy_doct . "', "
      . " `CLNC_GUBN`='" . mysql_real_escape_string($clnc_gubn) . "', "
      . " `OPER_CHCK`='" . mysql_real_escape_string($oper_chck) . "', "
      . " `INET_FLAG`='" . mysql_real_escape_string($inet_flag) . "', "
      . " `TELE_FLAG`='" . mysql_real_escape_string($tele_flag) . "', "
      . " `RESV_MEMO`='" . mysql_real_escape_string($resv_memo) . "', "
      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "
      . " `UPDT_USER`='" . $uu . "', "
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
	//»èÁ¦ Äõ¸®¸¦ »èÁ¦ ÇÃ·¡±×·Î ¼³Á¤
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
$re = $_GET["re"];
switch ($method) {
    case "add":
        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"], $_POST["IsAllDayEvent"], $re);
        break;
    case "list":
        $ret = listCalendar($_POST["showdate"], $_POST["viewtype"]);
        break;
    case "update":
        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $re);
        break; 
    case "remove":
        $ret = removeCalendar( $_POST["calendarId"]);
        break;
    case "adddetails":
        $st = $_POST["stpartdate"] . " " . $_POST["stparttime"];
        $et = $_POST["etpartdate"] . " " . $_POST["etparttime"];
		if($_POST["CLNC_GUBN"]==""){
			$_POST["CLNC_GUBN"]="N";
		}		
		if($_POST["OPER_CHCK"]==""){
			$_POST["OPER_CHCK"]="N";
		}
		if($_POST["INET_FLAG"]==""){
			$_POST["INET_FLAG"]="0";
		}
		if($_POST["TELE_FLAG"]==""){
			$_POST["TELE_FLAG"]="0";
		}
        if(isset($_GET["id"])){
            $ret = updateDetailedCalendar($_GET["id"], $st, $et, 
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_CNUM"], $_POST["CUST_GUBN"], $_POST["RMDY_CODE"], $_POST["CLNC_CODE"], $_POST["CLNC_GUBN"], $_POST["OPER_CHCK"], $_POST["INET_FLAG"], $_POST["TELE_FLAG"], $_POST["RESV_MEMO"], $re);
        }else{
            $ret = addDetailedCalendar($st, $et,                    
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_CNUM"], $_POST["CUST_GUBN"], $_POST["RMDY_CODE"], $_POST["CLNC_CODE"], $_POST["CLNC_GUBN"], $_POST["OPER_CHCK"], $_POST["INET_FLAG"], $_POST["TELE_FLAG"], $_POST["RESV_MEMO"], $re);
        }        
        break; 


}
echo json_encode($ret); 



?>