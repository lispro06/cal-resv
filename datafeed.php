<?php   	define('__ZBXE__', true);	include("../files/config/db.config.php");	$dbname=$db_info->master_db['db_userid'];	$dbpass=$db_info->master_db['db_password'];class DBConnection{	function getConnection($dbname,$dbpass){	  //change to your database server/user name/password		mysql_connect("localhost",$dbname,$dbpass) or         die("Could not connect: " . mysql_error());    //change to your database name		mysql_select_db($dbname) or 		     die("Could not select database: " . mysql_error());	}}    $db = new DBConnection();    $db->getConnection($dbname,$dbpass);    mysql_query("set session character_set_connection=utf8;");    mysql_query("set session character_set_results=utf8;");    mysql_query("set session character_set_client=utf8;");include_once("functions.php");//±âº» ³»¿ë ÀÔ·Âfunction addCalendar($st, $et, $sub, $ade, $re){  $ret = array();  try{    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `REGI_DATE`, `REGI_EMPL`, `RESV_IPAD`) values ('"      .mysql_real_escape_string($sub)."', '"      .php2MySqlTime(js2PhpTime($st))."', '"      .php2MySqlTime(js2PhpTime($et))."', '"      .mysql_real_escape_string($ade)."', '"      .php2MySqlTime(js2PhpTime($st))."', '"      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"      .php2MySqlTime(time())."', '"	  .$re."', '"      .$_SERVER['REMOTE_ADDR']."' )";    //echo($sql);if(js2PhpTime($st)>time()){    if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'add success';      $ret['Data'] = mysql_insert_id();    }    }else{      $ret['IsSuccess'] = false;      $ret['Msg'] = '과거 날짜에는 예약할 수 없습니다.';    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}//¼¼ºÎ ³»¿ë ÀÔ·Âfunction addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $asin_seqn, $cust_name, $cust_tele, $cust_cnum, $cust_gubn, $rmdy_code, $clnc_code, $clnc_gubn, $hosp_code, $oper_chck, $inet_flag, $tele_flag, $resv_memo, $re){  $ret = array();  try{    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `description`, `location`, `color`, `CUST_NAME`, `CUST_TELE`, `CUST_CNUM`, `CUST_GUBN`, `RMDY_CODE`, `CLNC_CODE`, `RMDY_DOCT`, `ASIN_SEQN`, `RESV_YYMD`, `RSST_HHMI`, `RSED_HHMI`, `REGI_DATE`, `REGI_EMPL`, `OPER_CHCK`, `INET_FLAG`, `TELE_FLAG`, `CLNC_GUBN`, `HOSP_CODE`, `RESV_MEMO`, `RESV_IPAD`) values ('"      .mysql_real_escape_string($sub)."', '"      .php2MySqlTime(js2PhpTime($st))."', '"      .php2MySqlTime(js2PhpTime($et))."', '"      .mysql_real_escape_string($ade)."', '"      .mysql_real_escape_string($dscr)."', '"      .mysql_real_escape_string($loc)."', '"      .mysql_real_escape_string($color)."', '"      .mysql_real_escape_string($cust_name)."', '"      .mysql_real_escape_string($cust_tele)."', '"      .mysql_real_escape_string($cust_cnum)."', '"      .mysql_real_escape_string($cust_gubn)."', '"      .mysql_real_escape_string($rmdy_code)."', '"      .mysql_real_escape_string($clnc_code)."', '"      .mysql_real_escape_string($rmdy_doct)."', '"      .mysql_real_escape_string($asin_seqn)."', '"      .php2MySqlTime(js2PhpTime($st))."', '"      .substr(php2MySqlTime(js2PhpTime($st)),11)."', '"      .substr(php2MySqlTime(js2PhpTime($et)),11)."', '"      .php2MySqlTime(time())."', '"	  .$re."', '"      .mysql_real_escape_string($oper_chck)."', '"      .mysql_real_escape_string($inet_flag)."', '"      .mysql_real_escape_string($tele_flag)."', '"      .mysql_real_escape_string($clnc_gubn)."', '"      .mysql_real_escape_string($hosp_code)."', '"      .mysql_real_escape_string($resv_memo)."', '"      .$_SERVER['REMOTE_ADDR']."' )";    //echo($sql);if(js2PhpTime($st)>time()){    if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'add success';      $ret['Data'] = mysql_insert_id();    }}else{      $ret['IsSuccess'] = false;      $ret['Msg'] = '과거 시간에는 예약하지 못합니다.';}	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}function addCustomer($cust_name, $cust_iden, $iden_chck, $cust_tele, $cust_hand, $sms_chck, $bith_year, $bith_mmdd, $bith_flag, $home_post, $offi_post, $addr_chck, $post_gubn, $sex_gubn, $mary_year, $mary_mmdd, $cust_mail, $mail_chck, $chrg_doct, $offi_addr, $home_addr, $cust_memo, $aest_empl, $uu){	$year = date("Y",time());	$limit_cnum = $year * 1000000;	$cc_sql = "SELECT MAX(  `CUST_CNUM` ) FROM  `toto_customer` WHERE `CUST_CNUM` > ".$limit_cnum;//최대값	$cc_hd = mysql_query($cc_sql);	$row_cc = mysql_fetch_array($cc_hd);	if($row_cc[0]){// 당해년도 고객이 있으면, cnum에 1증가		$cnum=$row_cc[0]+1;	}else{// 당해년도 첫 고객이면, 넘버링 룰에 따라 cnum 부여		$cnum=$limit_cnum + 1;	}	$regi_yymd= date("Y-m-d",time());  $ret = array();  try{    $sql = "INSERT INTO  `toto`.`toto_customer` (`CUST_CNUM` ,`CUST_NAME` ,`CUST_IDEN` ,`IDEN_CHCK` ,`CUST_TELE` ,`CUST_HAND` ,`SMS_CHCK` ,`BITH_YEAR` ,`BITH_MMDD` ,`BITH_FLAG` ,`HOME_POST` ,`HOME_ADDR` ,`OFFI_POST` ,`OFFI_ADDR` ,`ADDR_CHCK` ,`BACK_CODE` ,`POST_GUBN` ,`PROX_GUBN` ,`SEX_GUBN` ,`MARY_YEAR` ,`MARY_MMDD` ,`CUST_MAIL` ,`MAIL_CHCK` ,`CHRG_DOCT` ,`CHNG_YYMD` ,`CHNG_CAUS` ,`TOTL_MILE` ,`USE_MILE` ,`JOB_CODE` ,`GRAD_CODE` ,`JINS_CHCK` ,`CUST_MEMO` ,`FMLY_CNUM` ,`FMLY_INFO` ,`AEST_EMPL` ,`REGI_YYMD` ,`UPDT_DATE` ,`UPDT_USER` ,`UPDT_IPAD`) values ("      .$cnum.", '"      .mysql_real_escape_string($cust_name)."', '"      .mysql_real_escape_string($cust_iden)."', '"      .mysql_real_escape_string($iden_chck)."', '"      .mysql_real_escape_string($cust_tele)."', '"      .mysql_real_escape_string($cust_hand)."', '"      .mysql_real_escape_string($sms_chck)."', '"      .mysql_real_escape_string($bith_year)."', '"      .mysql_real_escape_string($bith_mmdd)."', '"      .mysql_real_escape_string($bith_flag)."', '"      .mysql_real_escape_string($home_post)."', '"      .mysql_real_escape_string($home_addr)."', '"      .mysql_real_escape_string($offi_post)."', '"      .mysql_real_escape_string($offi_addr)."', '"      .mysql_real_escape_string($addr_chck)."', '"      .mysql_real_escape_string($back_code)."', '"      .mysql_real_escape_string($post_gubn)."', '"      .mysql_real_escape_string($prox_gubn)."', '"      .mysql_real_escape_string($sex_gubn)."', '"      .mysql_real_escape_string($mary_year)."', '"      .mysql_real_escape_string($mary_mmdd)."', '"      .mysql_real_escape_string($cust_mail)."', '"      .mysql_real_escape_string($mail_chck)."', '"      .mysql_real_escape_string($chrg_doct)."', '"      .mysql_real_escape_string($chng_yymd)."', '"      .mysql_real_escape_string($chng_caus)."', '"      .mysql_real_escape_string($totl_mile)."', '"      .mysql_real_escape_string($use_mile)."', '"      .mysql_real_escape_string($job_code)."', '"      .mysql_real_escape_string($grad_code)."', '"      .mysql_real_escape_string($jins_chck)."', '"      .mysql_real_escape_string($cust_memo)."', '"      .mysql_real_escape_string($fmly_cnum)."', '"      .mysql_real_escape_string($fmly_info)."', '"      .mysql_real_escape_string($aest_empl)."', '"      .mysql_real_escape_string($regi_yymd)."', '"      .mysql_real_escape_string($updt_date)."', '"      .mysql_real_escape_string($uu)."', '"      .$_SERVER['REMOTE_ADDR']."' )";    //echo($sql);    if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'add success';      $ret['Data'] = mysql_insert_id();    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}function listCalendarByRange($sd, $ed, $doc, $dev, $hc){  $ret = array();  $ret['events'] = array();  $ret["issort"] =true;  $ret["start"] = php2JsTime($sd);  $ret["end"] = php2JsTime($ed);  $ret['error'] = null;  if($doc=="true" && $dev=="true"){//2013-08-30 장비/원장/병원	  $sql_ext="and `ASIN_SEQN` between 0 and 4";  }else if($doc=="true"){	  $sql_ext="and `ASIN_SEQN`='0'";  }else if($dev=="true"){	  $sql_ext="and `ASIN_SEQN` between 1 and 4";  }else{	  $sql_ext="and `ASIN_SEQN`='5'";  }  $sql_ext=$sql_ext." and `HOSP_CODE`='".$hc."' ORDER BY `ASIN_SEQN` ASC ";  try{    $sql = "select * from `jqcalendar` where `starttime` between '"      .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."' and `DELE_CHCK`='N' ".$sql_ext;    $handle = mysql_query($sql);    //echo $sql;    while ($row = mysql_fetch_object($handle)) {      //$ret['events'][] = $row;      //$attends = $row->AttendeeNames;      //if($row->OtherAttendee){      //  $attends .= $row->OtherAttendee;      //}      //echo $row->StartTime;      $ret['events'][] = array(        $row->Id,        $row->Subject,        php2JsTime(mySql2PhpTime($row->StartTime)),        php2JsTime(mySql2PhpTime($row->EndTime)),        $row->IsAllDayEvent,        0, //more than one day event        //$row->InstanceType,        0,//Recurring event,        $row->Color,        1,//editable        $row->Location,         ''//$attends      );    }	}catch(Exception $e){     $ret['error'] = $e->getMessage();  }  return $ret;}function listCalendar($day, $type, $doc, $dev, $hc){  $phpTime = js2PhpTime($day);  //echo $phpTime . "+" . $type;  switch($type){    case "month":      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));      break;    case "week":      //suppose first day of a week is monday       $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;      //echo date('N', $phpTime);      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));      break;    case "day":      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));      break;  }  //echo $st . "--" . $et;  return listCalendarByRange($st, $et, $doc, $dev, $hc);}//³¯Â¥ ¹× ½Ã°£¸¸ º¯°æ, ¼öÁ¤ÀÚ, ¼öÁ¤ IPµµ ¾÷µ¥ÀÌÆ® µÊfunction updateCalendar($id, $st, $et, $uu){  $ret = array();  try{    $sql = "update `jqcalendar` set"      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "      . " `UPDT_USER`='" . $uu . "', "      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "' "      . "where `id`=" . $id;    //echo $sql;		if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'Succefully';    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}function updateCustomer($cust_name, $cust_iden, $iden_chck, $cust_tele, $cust_hand, $sms_chck, $bith_year, $bith_mmdd, $bith_flag, $home_post, $offi_post, $addr_chck, $post_gubn, $sex_gubn, $mary_year, $mary_mmdd, $cust_mail, $mail_chck, $chrg_doct, $offi_addr, $home_addr, $cust_memo, $aest_empe, $uu, $cust_cnum){  $ret = array();  try{    $sql = "UPDATE  `toto_customer` SET"    . "`CUST_NAME` ='" .mysql_real_escape_string($cust_name) ."', "    . "`CUST_IDEN` ='" .mysql_real_escape_string($cust_iden) ."', "    . "`IDEN_CHCK` ='" .mysql_real_escape_string($iden_chck) ."', "    . "`CUST_TELE` ='" .mysql_real_escape_string($cust_tele) ."', "    . "`CUST_HAND` ='" .mysql_real_escape_string($cust_hand) ."', "    . "`SMS_CHCK` ='" .mysql_real_escape_string($sms_chck) ."', "    . "`BITH_YEAR` ='" .mysql_real_escape_string($bith_year) ."', "    . "`BITH_MMDD` ='" .mysql_real_escape_string($bith_mmdd) ."', "    . "`BITH_FLAG` ='" .mysql_real_escape_string($bith_flag) ."', "    . "`HOME_POST` ='" .mysql_real_escape_string($home_post) ."', "    . "`OFFI_POST` ='" .mysql_real_escape_string($offi_post) ."', "    . "`ADDR_CHCK` ='" .mysql_real_escape_string($addr_chck) ."', "    . "`POST_GUBN` ='" .mysql_real_escape_string($post_gubn) ."', "    . "`SEX_GUBN` ='" .mysql_real_escape_string($sex_gubn) ."', "    . "`OFFI_ADDR` ='" .mysql_real_escape_string($offi_addr) ."', "    . "`HOME_ADDR` ='" .mysql_real_escape_string($home_addr) ."', "    . "`HOME_POST` ='" .mysql_real_escape_string($home_post) ."', "    . "`MARY_YEAR` ='" .mysql_real_escape_string($mary_year) ."', "    . "`MARY_MMDD` ='" .mysql_real_escape_string($mary_mmdd) ."', "    . "`CUST_MAIL` ='" .mysql_real_escape_string($cust_mail) ."', "    . "`MAIL_CHCK` ='" .mysql_real_escape_string($mail_chck) ."', "    . "`CHRG_DOCT` ='" .mysql_real_escape_string($chrg_doct) ."', "    . "`CUST_MEMO` ='" .mysql_real_escape_string($cust_memo) ."', "    . "`AEST_EMPL` ='" .mysql_real_escape_string($aest_empl) ."', "    . "`UPDT_IPAD` ='" . $_SERVER['REMOTE_ADDR'] . "', "    . "`UPDT_DATE` ='" . php2MySqlTime(time()) . "', "    . "`UPDT_USER` ='".$uu."' WHERE  `CUST_CNUM` ='".$cust_cnum."';";    if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'Succefully';    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}//¼¼ºÎ ³»¿ë ¾÷µ¥ÀÌÆ®function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz, $rmdy_doct, $asin_seqn, $cust_name, $cust_tele, $cust_cnum, $cust_gubn, $rmdy_code, $clnc_code, $clnc_gubn, $hosp_code, $oper_chck, $inet_flag, $tele_flag, $resv_memo, $uu){  $ret = array();  try{    $sql = "update `jqcalendar` set"      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "      . " `subject`='" . mysql_real_escape_string($sub) . "', "      . " `isalldayevent`='" . mysql_real_escape_string($ade) . "', "      . " `description`='" . mysql_real_escape_string($dscr) . "', "      . " `location`='" . mysql_real_escape_string($loc) . "', "      . " `color`='" . mysql_real_escape_string($color) . "', "      . " `CUST_NAME`='" . mysql_real_escape_string($cust_name) . "', "      . " `CUST_TELE`='" . mysql_real_escape_string($cust_tele) . "', "      . " `CUST_CNUM`='" . mysql_real_escape_string($cust_cnum) . "', "      . " `CUST_GUBN`='" . mysql_real_escape_string($cust_gubn) . "', "      . " `RMDY_CODE`='" . mysql_real_escape_string($rmdy_code) . "', "      . " `CLNC_CODE`='" . mysql_real_escape_string($clnc_code) . "', "      . " `RESV_YYMD`='" . php2MySqlTime(js2PhpTime($st)) . "', "      . " `RSST_HHMI`='" . substr(php2MySqlTime(js2PhpTime($st)),11) . "', "      . " `RSED_HHMI`='" . substr(php2MySqlTime(js2PhpTime($et)),11) . "', "      . " `RMDY_DOCT`='" . $rmdy_doct . "', "      . " `ASIN_SEQN`='" . $asin_seqn . "', "      . " `CLNC_GUBN`='" . mysql_real_escape_string($clnc_gubn) . "', "      . " `HOSP_CODE`='" . mysql_real_escape_string($hosp_code) . "', "      . " `OPER_CHCK`='" . mysql_real_escape_string($oper_chck) . "', "      . " `INET_FLAG`='" . mysql_real_escape_string($inet_flag) . "', "      . " `TELE_FLAG`='" . mysql_real_escape_string($tele_flag) . "', "      . " `RESV_MEMO`='" . mysql_real_escape_string($resv_memo) . "', "      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "      . " `UPDT_USER`='" . $uu . "', "      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "' "      . "where `id`=" . $id;    //echo $sql;		if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'Succefully';    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}function removeCalendar($id){  $ret = array();  try{	//»èÁ¦ Äõ¸®¸¦ »èÁ¦ ÇÃ·¡±×·Î ¼³Á¤    //$sql = "delete from `jqcalendar` where `id`=" . $id;    $sql = "update `jqcalendar` set"      . " `UPDT_DATE`='" . php2MySqlTime(time()) . "', "      . " `UPDT_USER`=3, "      . " `UPDT_IPAD`='" . $_SERVER['REMOTE_ADDR'] . "', "      . " `DELE_CHCK`='Y' "      . "where `id`=" . $id;		if(mysql_query($sql)==false){      $ret['IsSuccess'] = false;      $ret['Msg'] = mysql_error();    }else{      $ret['IsSuccess'] = true;      $ret['Msg'] = 'Succefully';    }	}catch(Exception $e){     $ret['IsSuccess'] = false;     $ret['Msg'] = $e->getMessage();  }  return $ret;}header('Content-type:text/javascript;charset=UTF-8');$method = $_GET["method"];$re = $_GET["re"];switch ($method) {    case "add":        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"], $_POST["IsAllDayEvent"], $re);        break;    case "list":        $ret = listCalendar($_POST["showdate"], $_POST["viewtype"], $_POST["doct_ck"], $_POST["dev_ck"], $_POST["hosp_code"]);        break;    case "update":        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $re);        break;     case "remove":        $ret = removeCalendar( $_POST["calendarId"]);        break;    case "customer":    	$bith_year=substr($_POST["CUST_BITH"],0,4);	$bith_mmdd=substr($_POST["CUST_BITH"],-5);    	$mary_year=str_replace("-","",substr($_POST["MARY_DATE"],0,4));	$mary_mmdd=substr($_POST["MARY_DATE"],-5);        if($_POST["CUST_CNUM"]){				$ret = updateCustomer($_POST["CUST_NAME"], $_POST["CUST_IDEN"], $_POST["IDEN_CHCK"], $_POST["CUST_TELE"], $_POST["CUST_HAND"], $_POST["SMS_CHCK"], $bith_year, $bith_mmdd, $_POST["BITH_FLAG"], $_POST["HOME_POST"], $_POST["OFFI_POST"], $_POST["ADDR_CHCK"], $_POST["POST_GUBN"], $_POST["SEX_GUBN"], $mary_year, $mary_mmdd, $_POST["CUST_MAIL"], $_POST["MAIL_CHCK"], $_POST["CHRG_DOCT"], $_POST["OFFI_ADDR"], $_POST["HOME_ADDR"], $_POST["CUST_MEMO"], $_POST["AEST_EMPL"], $re, $_POST["CUST_CNUM"]);		}else{		$ret = addCustomer($_POST["CUST_NAME"], $_POST["CUST_IDEN"], $_POST["IDEN_CHCK"], $_POST["CUST_TELE"], $_POST["CUST_HAND"], $_POST["SMS_CHCK"], $bith_year, $bith_mmdd, $_POST["BITH_FLAG"], $_POST["HOME_POST"], $_POST["OFFI_POST"], $_POST["ADDR_CHCK"], $_POST["POST_GUBN"], $_POST["SEX_GUBN"], $mary_year, $mary_mmdd, $_POST["CUST_MAIL"], $_POST["MAIL_CHCK"], $_POST["CHRG_DOCT"], $_POST["OFFI_ADDR"], $_POST["HOME_ADDR"], $_POST["CUST_MEMO"], $_POST["AEST_EMPL"], $re, $_POST["CUST_CNUM"]);		}	break;    case "adddetails":        $st = $_POST["stpartdate"] . " " . $_POST["stparttime"];        $et = $_POST["etpartdate"] . " " . $_POST["etparttime"];		if($_POST["CLNC_GUBN"]==""){			$_POST["CLNC_GUBN"]="N";		}				if($_POST["OPER_CHCK"]==""){			$_POST["OPER_CHCK"]="N";		}		if($_POST["INET_FLAG"]==""){			$_POST["INET_FLAG"]="0";		}		if($_POST["TELE_FLAG"]==""){			$_POST["TELE_FLAG"]="0";		}        if(isset($_GET["id"])){            $ret = updateDetailedCalendar($_GET["id"], $st, $et,                 $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"],                 $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["ASIN_SEQN"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_CNUM"], $_POST["CUST_GUBN"], $_POST["RMDY_CODE"], $_POST["CLNC_CODE"], $_POST["CLNC_GUBN"], $_POST["HOSP_CODE"], $_POST["OPER_CHCK"], $_POST["INET_FLAG"], $_POST["TELE_FLAG"], $_POST["RESV_MEMO"], $re);        }else{            $ret = addDetailedCalendar($st, $et,                                    $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"],                 $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"], $_POST["RMDY_DOCT"], $_POST["ASIN_SEQN"], $_POST["CUST_NAME"], $_POST["CUST_TELE"], $_POST["CUST_CNUM"], $_POST["CUST_GUBN"], $_POST["RMDY_CODE"], $_POST["CLNC_CODE"], $_POST["CLNC_GUBN"], $_POST["HOSP_CODE"], $_POST["OPER_CHCK"], $_POST["INET_FLAG"], $_POST["TELE_FLAG"], $_POST["RESV_MEMO"], $re);        }                break; }echo json_encode($ret); ?>