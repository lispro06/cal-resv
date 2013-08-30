<?php
header("Content-Type: text/html; charset=UTF-8");
//이중 로그인과 db정보 연동을 위한 파일 로딩2013-08-07
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
 require_once('../config/config.inc.php');
 $oContext = &Context::getInstance();
 $oContext->init();
    
 $logged_info = Context::get('logged_info'); 
 $id = $logged_info->user_id;

if(!$_SESSION['sunap']){//권한이 없으면 종료
	exit();
}else{
	$aclSql="select * from `toto_acl` where `user_id`='".$_SESSION['sunap']."'";
	$aclRes = mysql_query($aclSql); 
	$aclRow = mysql_fetch_row($aclRes);
}
if($aclRow[8]!="Y"){//권한이 없으면 종료
	exit();
}
include_once("./functions.php");
// db 커넥션을 무조건 한다. 2013-08-05
    $db = new DBConnection();
    $db->getConnection($dbname,$dbpass);
function getCalendarByRange($id){
  try{    
    $sql = "select * from `jqcalendar` where `id` = " . $id;
    $handle = mysql_query($sql);
    //echo $sql;
    $row = mysql_fetch_object($handle);
	}catch(Exception $e){
  }
  return $row;
}
if($_GET["id"]){
	$event = getCalendarByRange($_GET["id"]);
	$sarr = explode(" ", php2JsTime(mySql2PhpTime($event->StartTime)));
	$earr = explode(" ", php2JsTime(mySql2PhpTime($event->EndTime)));
	if($_GET["same"]){
		$event="";
	}
	$cb[$event->CUST_GUBN]="checked";//고객 구분 배열 2013-08-05
	$reged_date=substr($event->REGI_DATE,0,10);
	$regi_empl=$event->REGI_EMPL;
	if($event->CUST_CNUM){
		$c_sql = "select `CUST_IDEN`, `CUST_MEMO` from `toto_customer` where `CUST_CNUM` = '".$event->CUST_CNUM."';";
    	$c_handle = mysql_query($c_sql);
		$cu_row = mysql_fetch_array($c_handle);
		$cust_iden=$cu_row[0];
		$cust_memo=$cu_row[1];
	}
    
}else{//2013-08-05 세부 일정 클릭 시 내용 수신
	$reged_date=date("Y-m-d",time());
	$cb["N"]="checked";//새 일정일 경우 고객은 신환으로 설정
	$title_G = $_GET["title"];
	$sarr_G = explode(" ", $_GET["start"]);
	$earr_G = explode(" ", $_GET["end"]);
	$regi_empl=$_SESSION["sunap"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>    
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">    
    <title>세부 내용</title>    
    <link href="css/main.css" rel="stylesheet" type="text/css" />       
    <link href="css/dp.css" rel="stylesheet" />    
    <link href="css/dropdown.css" rel="stylesheet" />    
    <link href="css/colorselect.css" rel="stylesheet" />   
     
    <script src="src/jquery.js" type="text/javascript"></script>    
    <script src="src/Plugins/Common.js" type="text/javascript"></script>        
    <script src="src/Plugins/jquery.form.js" type="text/javascript"></script>     
    <script src="src/Plugins/jquery.validate.js" type="text/javascript"></script>     
    <script src="src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>        
    <script src="src/Plugins/jquery.datepicker.js" type="text/javascript"></script>     
    <script src="src/Plugins/jquery.dropdown.js" type="text/javascript"></script>     
    <script src="src/Plugins/jquery.colorselect.js" type="text/javascript"></script>    
     
    <script type="text/javascript">
        if (!DateAdd || typeof (DateDiff) != "function") {
            var DateAdd = function(interval, number, idate) {
                number = parseInt(number);
                var date;
                if (typeof (idate) == "string") {
                    date = idate.split(/\D/);
                    eval("var date = new Date(" + date.join(",") + ")");
                }
                if (typeof (idate) == "object") {
                    date = new Date(idate.toString());
                }
                switch (interval) {
                    case "y": date.setFullYear(date.getFullYear() + number); break;
                    case "m": date.setMonth(date.getMonth() + number); break;
                    case "d": date.setDate(date.getDate() + number); break;
                    case "w": date.setDate(date.getDate() + 7 * number); break;
                    case "h": date.setHours(date.getHours() + number); break;
                    case "n": date.setMinutes(date.getMinutes() + number); break;
                    case "s": date.setSeconds(date.getSeconds() + number); break;
                    case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
                }
                return date;
            }
        }
        function getHM(date)
        {
             var hour =date.getHours();
             var minute= date.getMinutes();
             var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
             return ret;
        }
        $(document).ready(function() {
            //debugger;
            var DATA_FEED_URL = "./cal/datafeed.php";
            var arrT = [];
            var tt = "{0}:{1}";
            for (var i = 6; i < 21; i++) {//combo box로 된 시간 선택 상자
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "10"]) });
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "20"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "40"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "50"]) });
            }
            $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
            $("#stparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            $("#etparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            var check = $("#IsAllDayEvent").click(function(e) {
                if (this.checked) {
                    $("#stparttime").val("00:00").hide();
                    $("#etparttime").val("00:00").hide();
                }
                else {
                    var d = new Date();
                    var p = 60 - d.getMinutes();
                    if (p > 30) p = p - 30;
                    d = DateAdd("n", p, d);
                    $("#stparttime").val(getHM(d)).show();
                    $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
                }
            });
            if (check[0].checked) {
                $("#stparttime").val("00:00").hide();
                $("#etparttime").val("00:00").hide();
            }
            $("#Savebtn").click(function() { $("#fmEdit").submit(); });
            $("#Closebtn").click(function() { CloseModelWindow(); });
            $("#Deletebtn").click(function() {
                 if (confirm("삭제하시겠습니까?")) {  
                    var param = [{ "name": "calendarId", value: 8}];                
                    $.post(DATA_FEED_URL + "?method=remove",
                        param,
                        function(data){
                              if (data.IsSuccess) {
                                    alert(data.Msg); 
                                    CloseModelWindow(null,true);                            
                                }
                                else {
                                    alert("오류 발생.\r\n" + data.Msg);
                                }
                        }
                    ,"json");
                }
            });
            
           $("#stpartdate,#etpartdate").datepicker({ picker: "<button class='calpick'></button>"});    
            var cv =$("#colorvalue").val() ;
            if(cv=="")
            {
                cv="-1";
            }
            $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
            //to define parameters of ajaxform
            var options = {
                beforeSubmit: function() {
                    return true;
                },
                dataType: "json",
                success: function(data) {
                    alert(data.Msg);
                    if (data.IsSuccess) {
                        CloseModelWindow(null,true);  
                    }
                }
            };
            $.validator.addMethod("date", function(value, element) {                             
                var arrs = value.split(i18n.datepicker.dateformat.separator);
                var year = arrs[i18n.datepicker.dateformat.year_index];
                var month = arrs[i18n.datepicker.dateformat.month_index];
                var day = arrs[i18n.datepicker.dateformat.day_index];
                var standvalue = [year,month,day].join("-");
                return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
            }, "Invalid date format");
            $.validator.addMethod("time", function(value, element) {
                return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
            }, "Invalid time format");
            $.validator.addMethod("safe", function(value, element) {
                return this.optional(element) || /^[^$\<\>]+$/.test(value);
            }, "$<> not allowed");
            $("#fmEdit").validate({
                submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
                errorElement: "div",
                errorClass: "cusErrorPanel",
                errorPlacement: function(error, element) {
                    showerror(error, element);
                }
            });
            function showerror(error, target) {
                var pos = target.position();
                var height = target.height();
                var newpos = { left: pos.left, top: pos.top + height + 2 }
                var form = $("#fmEdit");             
                error.appendTo(form).css(newpos);
            }
        });
		function cust_srch(){
			var cust_name = document.getElementById("keyword");
			var ln=cust_name.value.length;
			var url="./cust_srch.php?cust_name="+cust_name.value;
			if(parseInt(ln)>1){
				window.open(url,'','width=200, height=200, toolbar=no, menubar=no, location=no, directories=0, status=0,scrollbar=0,resize=0');
			}else{
				alert("고객 이름을 2자 이상 입력해 주세요.");
			}
		}
		
<?php // 처치명 세팅하기
	$cc_sql = "SELECT `CLNC_CODE` FROM `toto_ClinicCode` WHERE `CLNC_CODE` LIKE '%00' AND `USE__FLAG`=\"1\"";
	$cc_hd = mysql_query($cc_sql);
	while($row_cc = mysql_fetch_array($cc_hd)){
	          $cc_parent[]=$row_cc[0];
	}
	$cc_sql = "SELECT `CLNC_CODE`, `CLNC_KORA`, `CLNC_ENGL` FROM `toto_ClinicCode` WHERE `CLNC_CODE` NOT LIKE '%00' AND `USE__FLAG`=\"1\"";
	$cc_hd = mysql_query($cc_sql);
	while($row_cc = mysql_fetch_array($cc_hd)){
	          $pos=intval($row_cc[0]/100)-1;
	          $cc_child[$pos]["code"][]=$row_cc[0];
	          $cc_child[$pos]["kora"][]=$row_cc[1];
	          $cc_child[$pos]["engl"][]=$row_cc[2];
	}
?>
		function cc_setting(obj){
			 ck = document.getElementById("CLNC_KORA");
			 cc = document.getElementById("CLNC_CODE");
			 cc.value = obj.value;
			 kren = obj.options[obj.selectedIndex].text;// "/" 로 나눈 kora과 engl 을 추출
			 kr = kren.split("/");
			 ck.value = kr[0];
		}
		function cc_child(obj)
		{
		selectBox=document.getElementById("cc_chi");
		if (null == selectBox || null == selectBox.options){
		   return;
		}
		
		var length = selectBox.options.length;
		    for (var index=0;index<length ;index++){
		    	selectBox.options.remove(0);
		    }
		var ov=obj.value;
		switch(ov){
		<?php
			for($i=0;$i<count($cc_parent);$i++){
				echo " case \"".$cc_parent[$i]."\" :\n";
				for($j=0;$j<count($cc_child[$i]["code"]);$j++){
					echo "var o".$j." = new Option(\"".$cc_child[$i]["kora"][$j]." / ".$cc_child[$i]["engl"][$j]."\",\"".$cc_child[$i]["code"][$j]."\", true);\n";		
					echo "selectBox.options[".$j."] = o".$j.";\n";
				}
				echo "		break;\n";
			}
		?>
			default :
				var option1 = new Option(ov, "1st_option", true);
                     		selectBox.options[0] = option1;
			}
		} 		
<?php // 진료명 세팅하기
	$rc_sql = "SELECT `RMDY_CODE` FROM `toto_RemedyCode` WHERE `RMDY_CODE` LIKE '%00' AND `USE__FLAG`=\"1\"";
	$rc_hd = mysql_query($rc_sql);
	while($row_rc = mysql_fetch_array($rc_hd)){
	          $rc_parent[]=$row_rc[0];
	}
	$rc_sql = "SELECT `RMDY_CODE`, `RMDY_KORA`, `RMDY_ENGL` FROM `toto_RemedyCode` WHERE `RMDY_CODE` NOT LIKE '%00' AND `USE__FLAG`=\"1\"";
	$rc_hd = mysql_query($rc_sql);
	while($row_rc = mysql_fetch_array($rc_hd)){
	          $pos=intval($row_rc[0]/100)-1;
	          $rc_child[$pos]["code"][]=$row_rc[0];
	          $rc_child[$pos]["kora"][]=$row_rc[1];
	          $rc_child[$pos]["engl"][]=$row_rc[2];
	}
?>
		function rc_setting(obj){
			 rk = document.getElementById("RMDY_KORA");
			 rc = document.getElementById("RMDY_CODE");
			 rc.value = obj.value;
			 koen = obj.options[obj.selectedIndex].text;
			 ko = koen.split("/");
			 rk.value = ko[0];
		}
		function rc_child(obj)
		{
		selectBox=document.getElementById("chi");
		if (null == selectBox || null == selectBox.options){
		   return;
		}
		
		var length = selectBox.options.length;
		    for (var index=0;index<length ;index++){
		    	selectBox.options.remove(0);
		    }
		var ov=obj.value;
		switch(ov){
		<?php
			for($i=0;$i<count($rc_parent);$i++){
				echo " case \"".$rc_parent[$i]."\" :\n";
				for($j=0;$j<count($rc_child[$i]["code"]);$j++){
					echo "var o".$j." = new Option(\"".$rc_child[$i]["kora"][$j]." / ".$rc_child[$i]["engl"][$j]."\",\"".$rc_child[$i]["code"][$j]."\", true);\n";		
					echo "selectBox.options[".$j."] = o".$j.";\n";
				}
				echo "		break;\n";
			}
		?>
			default :
				var option1 = new Option(ov, "1st_option", true);
                     		selectBox.options[0] = option1;
			}
		} 


</script>      
    <style type="text/css">     
    .calpick     {        
        width:16px;   
        height:16px;     
        border:none;        
        cursor:pointer;        
        background:url("sample-css/cal.gif") no-repeat center 2px;        
        margin-left:-22px;    
    }      
    </style>
  </head>
  <body>    
    <div>      
      <div class="toolBotton">           
        <a id="Savebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Save" title="예약 입력" onClick="subj();">저장(<u>S</u>)
          </span>          
        </a>                           
        <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">                    
          <span class="Delete" title="예약 취소">삭제(<u>D</u>)
          </span>                
        </a>             
        <?php } ?>            
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">                
          <span class="Close" title="취소하고 닫기" >닫기
          </span></a>            
        </a>        
      </div>                  
      <div style="clear: both">         
      </div>        
      <div class="infocontainer">
<?php
	if($_GET["same"]){
?>
        <form action="./datafeed.php?method=adddetails&re=<?php echo $_SESSION['sunap'];?>" class="fform" id="fmEdit" method="post">
<?php
	}else{
?>
        <form action="./datafeed.php?method=adddetails<?php echo isset($event)?"&id=".$event->Id:""; ?>&re=<?php echo $_SESSION['sunap'];?>" class="fform" id="fmEdit" method="post">
<?php
	}
?>
		<div style="border:1px solid #333333;padding:2px;">
		<span><b>&nbsp;<u>고객구분</u> <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="N" <?php echo $cb["N"];?> />신환 <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="O" <?php echo $cb["O"];?> />구환&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 고객검색 <input type="text" size="10" name="CUST_CNUM" id="CUST_CNUM" value="<?php echo $event->CUST_CNUM;?>" style="display:none;"></input><input type="text" size="10" id="keyword" name="keyword" value=""></input><input type="button" onclick="cust_srch();" value="검색" id="sc_bt" name="sc_bt"></input>
		</span><br />
		  <span><b>&nbsp;<u>고객성명</u>&nbsp;&nbsp;<input class="required safe" name="CUST_NAME" id="CUST_NAME" size="7" value="<?php echo $event->CUST_NAME;?>"></input>&nbsp;&nbsp;&nbsp; <u>연&nbsp;락&nbsp;처</u> <input class="required safe" name="CUST_TELE" id="CUST_TELE" size="15" value="<?php echo $event->CUST_TELE;?>"></input>&nbsp;&nbsp; 주&nbsp;민&nbsp;번&nbsp;호 <input name="CUST_IDEN" id="CUST_IDEN" size="15" value="<?php echo $cust_iden;?>" disabled></input></b></span><br />
		  <table><tr><td>고객메모<br />(특이사항)</td><td><textarea id="CUST_MEMO" name="CUST_MEMO" disabled style="width:480px;;height:30px;"><?php echo $cust_memo;?></textarea></td></tr></table>
		  </div>
		  <hr style="margin: 5px 0 0 0;border-color:white" >
		  <label style="display:none;">
            <span>*제목:<input MaxLength="200" id="Subject" name="Subject" style="width:70%;" type="text" value="<?php echo isset($event)?$event->Subject:"" ?><?php echo $title_G;?>" />(달력에 보일 내용)
            </span></label>
            <div id="calendarcolor" style="display:none;">
            </div>                    
            <div style="border:1px solid #333333;padding:2px;">
			<table>
			<tr>
			<td>
			&nbsp;<u>예&nbsp;약&nbsp;일&nbsp;정</u>&nbsp;&nbsp;
              <input MaxLength="10" class="required date" id="stpartdate" name="stpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event)?$sarr[0]:""; ?><?php echo $sarr_G[0]; ?>" />                       
              <input MaxLength="5" class="required time" id="stparttime" name="stparttime" style="width:40px;" type="text" value="<?php echo isset($event)?$sarr[1]:""; ?><?php echo $sarr_G[1]; ?>" />To                       
              <input MaxLength="10" class="required date" id="etpartdate" name="etpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event)?$earr[0]:""; ?><?php echo $earr_G[0]; ?>" />                       
              <input MaxLength="50" class="required time" id="etparttime" name="etparttime" style="width:40px;" type="text" value="<?php echo isset($event)?$earr[1]:""; ?><?php echo $earr_G[1]; ?>" />                                            
              <label class="checkp"> 
                <input id="IsAllDayEvent" name="IsAllDayEvent" type="checkbox" value="1" <?php if(isset($event)&&$event->IsAllDayEvent!=0) {echo "checked";} ?>/>          하루 종일                      
              </label>
			  </td>
			  </tr>
			  </table>
	<table cellpadding="0" cellspacing="0" border="0" width="550"><tr><td width="200">
			<table><tr>
			<td style="width:80px;">&nbsp;<u>예&nbsp;약&nbsp;병&nbsp;원</u></td>
			<td>
<?php
	if($event->HOSP_CODE=="1234"){
		$code["1234"]="selected";
	}
?>
				<select name="HOSP_CODE" id="HOSP_CODE" style="width:120px;" class="required safe">
	    	    <option value=""></option>
	    	    <option value="1234" <?php echo $code["1234"];?>>기본병원</option></select>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;<u>진&nbsp;&nbsp;&nbsp;료&nbsp;&nbsp;&nbsp;명</u></td>
			<td>
			<?php
			$rk_sql = "SELECT `RMDY_KORA`, `RMDY_ENGL` FROM `toto_RemedyCode` WHERE `RMDY_CODE` = '".$event->RMDY_CODE."'";
			$rk_hd=mysql_query($rk_sql);
			$rk_row=mysql_fetch_array($rk_hd);
			?>
				<input type="text" id="RMDY_KORA" name="RMDY_KORA" style="width:120px;" value="<?php echo $rk_row[0];?>" class="required safe" readonly></input>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;처&nbsp;&nbsp;&nbsp;치&nbsp;&nbsp;&nbsp;명</td>
			<td>
			<?php //2013-08-12 헉
			$cc_sql = "SELECT `CLNC_KORA` FROM `toto_ClinicCode` WHERE `CLNC_CODE` = '".$event->CLNC_CODE."'";
			$cc_hd=mysql_query($cc_sql);
			$cc_row=mysql_fetch_array($cc_hd);
			?>
				<input type="text" id="CLNC_KORA" name="CLNC_KORA" style="width:120px;" value="<?php echo $cc_row[0];?>" readonly></input>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;원&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;장</td>
			<td>
				<select name="RMDY_DOCT" id="RMDY_DOCT" style="width:120px;" onchange="rd_ch();">
	    	    <option value="">원장 선택</option>
			<?php
			
			$rmdy_sql="SELECT * FROM  `toto_doctor`";	
    			$rmdy_hd = mysql_query($rmdy_sql);
			//$row = mysql_fetch_array($rmdy_hd);//원장선택 추가 2013-8-3
			//$tt=mysql_affected_rows();
				while($row = mysql_fetch_array($rmdy_hd)){
					   if($row[1]==$event->RMDY_DOCT){
						echo "<option value='".$row[1]."' selected>".$row[2]."</option>";
						}else{
						echo "<option value='".$row[1]."'>".$row[2]."</option>";
						}
					   }
			?>
    			</select>
            </td>
			</tr>
			<tr>
<?php
	$as_arr[$event->ASIN_SEQN]="selected";
?>
			<td style="width:80px;">&nbsp;관리&nbsp;/&nbsp;장비</td>
			<td>
				<select name="ASIN_SEQN" id="ASIN_SEQN" style="width:120px;" onchange="as_color();">
	    	    <option value="0"></option>
	    	    <option value="1" <?php echo $as_arr[1];?>>관리</option>
	    	    <option value="2" <?php echo $as_arr[2];?>>가예약</option>
	    	    <option value="3" <?php echo $as_arr[3];?>>병원일정</option>
	    	    <option value="4" <?php echo $as_arr[4];?>>고객일정</option></select>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;예약 접수일</td>
			<td>
				<input type="text" style="width:120px;" id="today_YYMD" name="today_YYMD" comment="입력 일이며 내부적으로 처리되므로 서버로 전송되지 않음" disabled value="<?php echo $reged_date;?>"></input>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;예약 접수자</td>
			<td>
				<input type="text" style="width:120px;" id="logged_ID" name="logged_ID" comment="로그인 세션 값" disabled value="<?php echo $regi_empl;?>"></input>
			</td>
			</tr></table>
		</td>
		<td style="width:150px;">진료명&nbsp;<input type="text" style="width:60px;" id="RMDY_CODE" name="RMDY_CODE" value="<?php echo $event->RMDY_CODE;?>" class="required safe" readonly></input>
				<select style="width:50px;overflow:scroll;" id="par" name="par" onchange="rc_child(this)">
		<?php
			$parent=count($rc_parent);
			for($i=0;$i<$parent;$i++){
			echo "<option value='".$rc_parent[$i]."'>".$rc_parent[$i]."</option>";
			}
		?>
			        </select><hr style="margin: 5px 0 0 0;">
				<select style="width:160px;overflow:auto;" size="9"" name="chi" id="chi" onchange="rc_setting(this);">   
		<?php
			$child=count($rc_child[0]["code"]);
			for($i=0;$i<$child;$i++){
			echo "<option value='".$rc_child[0]["code"][$i]."'>".$rc_child[0]["kora"][$i]." / ".$rc_child[0]["engl"][$i]."</option>";
			}
		?>
			</td>
		<td style="width:150px;">처치명&nbsp;<input type="text" style="width:60px;" id="CLNC_CODE" name="CLNC_CODE" value="<?php echo $event->CLNC_CODE;?>" readonly></input>
				<select style="width:50px;overflow:scroll;" id="cpar" name="cpar" onchange="cc_child(this)">
		<?php
			for($i=0;$i<count($cc_parent);$i++){
			echo "<option value='".$cc_parent[$i]."'>".$cc_parent[$i]."</option>";
			}
		?>	    	    
		    </select><hr style="margin: 5px 0 0 0;">
				<select style="width:160px;overflow:auto;" size="9" id="cc_chi" name="cc_chi" onchange="cc_setting(this);">
		<?php
			$child=count($cc_child[0]["code"]);
			for($i=0;$i<$child;$i++){
			echo "<option value='".$cc_child[0]["code"][$i]."'>".$cc_child[0]["kora"][$i]." / ".$cc_child[0]["engl"][$i]."</option>";
			}
		?>
				</select>
			</td>
		</tr></table>
		<table cellpadding="0" cellspacing="0" border="0" width="550"><tr>
		<td width="88">&nbsp;&nbsp;예&nbsp;약&nbsp;메&nbsp;모
		</td>
		<td>
		<textarea style="width:97%;" id="RESV_MEMO" name="RESV_MEMO"><?php echo $event->RESV_MEMO;?></textarea>
		</td>
		</tr>
		<tr>
		<td>&nbsp;&nbsp;예&nbsp;약&nbsp;구&nbsp;분</td>
<?php
$cg[0]="";
$cg[1]="";
$cg[2]="";
$cg[$event->CLNC_GUBN]="checked";
if($event->OPER_CHCK=="Y"){
	$oc="checked";
}
if($event->INET_FLAG){
	$if="checked";
}
if($event->TELE_FLAG){
	$tf="checked";
}
?>
		<td>
		[<input type="radio" name="CLNC_GUBN" id="CLNC_GUBN" value="0" <?php echo $cg[0];?> />일반처치 <input type="radio" name="CLNC_GUBN" id="CLNC_GUBN" value="1" <?php echo $cg[1];?> />처치2 <input type="radio" name="CLNC_GUBN" id="CLNC_GUBN" value="2" <?php echo $cg[2];?> />제모] <input type="checkbox" name="OPER_CHCK" id="OPER_CHCK" value="Y" <?php echo $oc;?> />수술 <input type="checkbox" name="INET_FLAG" id="INET_FLAG" value="1" <?php echo $if;?> />채팅 <input type="checkbox" name="TELE_FLAG" id="TELE_FLAG" value="1" <?php echo $tf;?> />아웃바운드콜
		</td>
		</tr>
		</table>
            <input id="colorvalue" name="colorvalue" type="hidden" value="<?php echo isset($event)?$event->Color:"" ?>" />
            </div>                
          </label>                 
          <label>
		  <table border="1" width="550">
			<tr>
				<td width="100"><a href="#rdiv" onclick="rc_view();">진료/처치내역</a></td><td width="100"><a href="#rdiv" onclick="rv_view();">예약내역</a></td><td></td>
			</tr>
			<tr>
				<td colspan="3">
				<div id="rmdyclnc" name="rmdyclnc" style="display:block;">
				<table>
					<tr><a name="rdiv"></a>
						<td>내원병원</td><td width="120">내원일자</td><td>진단명</td><td>처치명</td><td>처치원장</td><td>미등록처치명</td><td>처치직원</td><td>부분</td>
					</tr>
<?php
	if($event->CUST_CNUM){
		$sql = "SELECT `HOSP_CODE`, `RESV_YYMD`, `RMDY_CODE`, `CLNC_CODE`, `RMDY_DOCT` FROM `jqcalendar` ORDER BY `StartTime` DESC LIMIT 0, 5";
		$c_hd = mysql_query($sql);
		while($c_row = mysql_fetch_array($c_hd)){
		     echo "<tr>\n";
		     echo "<td>".code2hosp($c_row[0])."</td><td>".$c_row[1]."</td><td>".code2rmdy($c_row[2])."</td><td>".code2clnc($c_row[3])."</td><td>".code2doct($c_row[4])."</td><td></td><td></td><td>전체</td>";
		     }
		}else{
?>
		<td><?php //echo $_SESSION['sunap'];?></td><td><?php //echo $aclRow[6];?></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
<?php
}
?>
				</table></div>
				<div id="reserv" name="reserv" style="display:none;">
				<table align="center">
					<tr><a name="rdiv"></a>
						<td>예약병원</td><td width="120">예약일자</td><td>예약시간</td><td>진료명</td><td width="150">예약메모</td><td>원장/장비</td>
					</tr>
<?php
	if($event->CUST_CNUM){
		$sql = "SELECT `HOSP_CODE`, `RESV_YYMD`, `RSST_HHMI`, `RMDY_CODE`, `RESV_MEMO`, `ASIN_SEQN` FROM `jqcalendar` ORDER BY `StartTime` DESC LIMIT 0, 5";
		$c_hd = mysql_query($sql);
		while($c_row = mysql_fetch_array($c_hd)){
		     echo "<tr>\n";
		     echo "<td>".code2hosp($c_row[0])."</td><td>".$c_row[1]."</td><td>".$c_row[2]."</td><td>".code2rmdy($c_row[3])."</td><td>".$c_row[4]."</td><td>".code2asin($c_row[5])."</td>";
		     }
		}else{
?>
		<td><?php //echo $_SESSION['sunap'];?></td><td><?php //echo $aclRow[6];?></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
<?php
}
?>
				</table></div>
				</td>
			</tr>
			</table>
<!--
            <span>                        장소:
            </span>                    -->
            <input MaxLength="200" id="Location" name="Location" style="width:95%;display:none" type="text" value="<?php echo isset($event)?$event->Location:""; ?>" />                 
<!--
           
			<label>
            <span>                        Remark:
            </span>                    
<textarea cols="20" id="Description" name="Description" rows="2" style="width:95%; height:70px">
<?php echo isset($event)?$event->Description:""; ?>
</textarea>                
          </label>                -->
          <input id="timezone" name="timezone" type="hidden" value="" />           
        </form>         
      </div>         
    </div>
  </body>
  <?php
//  var_dump($rc_child);
function code2rmdy($cd){
	$rc_sql = "SELECT `RMDY_CODE`, `RMDY_KORA`, `RMDY_ENGL` FROM `toto_RemedyCode` WHERE `RMDY_CODE`='".$cd."'";
	$rc_hd = mysql_query($rc_sql);
	$row_rc = mysql_fetch_array($rc_hd);
	return $row_rc[1];
	}
function code2clnc($cd){
	$cc_sql = "SELECT `CLNC_CODE`, `CLNC_KORA`, `CLNC_ENGL` FROM `toto_ClinicCode` WHERE `CLNC_CODE`='".$cd."'";
	$cc_hd = mysql_query($cc_sql);
	$row_cc = mysql_fetch_array($cc_hd);
	return $row_cc[1];
	}
function code2doct($cd){
	$cc_sql = "SELECT `doct_name` FROM `toto_doctor` WHERE `doct_numb`='".$cd."'";
	$cc_hd = mysql_query($cc_sql);
	$row_cc = mysql_fetch_array($cc_hd);
	return $row_cc[1];
	}
function code2hosp($cd){
	if($cd=="1234")
		return "기본병원";
}
function code2asin($cd){
	switch($cd){
		case "1" :
		return "관리";
		break;
		case "2" :
		return "가예약";
		break;
		case "3" :
		return "병원일정";
		break;
		case "4" :
		return "고객일정";
		break;
	}
}
  ?>
  <script>
  
			var kw = document.getElementById("keyword");
			var sb = document.getElementById("sc_bt");
			var cg = document.getElementById("CUST_GUBN");
		function srch_on(){
			cg = document.getElementById("CUST_GUBN");
			alert(cg.value);
			if(cg.value=="N"){
				kw.removeAttribute('disabled');
				sb.removeAttribute('disabled');
			}else{
				kw.setAttribute('disabled',true);
				sb.setAttribute('disabled',true);
			}
		}
			var cn = document.getElementById("CUST_NAME");
			var ct = document.getElementById("CUST_TELE");
			var sj = document.getElementById("Subject");
			var lc = document.getElementById("Location");
			var rk = document.getElementById("RMDY_KORA");
			var rm = document.getElementById("RESV_MEMO");
			var hn = document.getElementById("HOSP_CODE");
			var hosp_name = hn.options[hn.selectedIndex].text;// 병원이름
			
			var li = document.getElementById("logged_ID");
		function subj(){//달력에 나올 내용 반영
			sj.value=cn.value+" "+ct.value+"<br />("+hosp_name+"/"+li.value+")";
			lc.value=rk.value+" ("+rm.value+")";
		}
			var cv = document.getElementById("colorvalue");
			var asin = document.getElementById("ASIN_SEQN");
			var rd_sel = document.getElementById("RMDY_DOCT");
		function as_color(){//관리/장비에 따른 색 지정, 원장 disable
			cv.value=asin.value;
			if(asin.value!="0"){
				rd_sel.setAttribute('disabled',true);
			}else{
				rd_sel.removeAttribute('disabled');
			}
		}
			var rdiv = document.getElementById("reserv");
			var rcdiv = document.getElementById("rmdyclnc");
		function rc_view(){//관리/장비에 따른 색 지정
			rdiv.style.display = "none";
			rcdiv.style.display = "block";
		}
		function rv_view(){//관리/장비에 따른 색 지정
			rdiv.style.display = "block";
			rcdiv.style.display = "none";
		}
		function rd_ch(){//원장이 있으면 disable
			if(rd_sel.value==""){
				asin.removeAttribute('disabled');
			}else{
				asin.setAttribute('disabled',true);
			}
		}
		rd_ch();
		as_color();
	</script>
</html>