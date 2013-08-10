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
	$cb[$event->CUST_GUBN]="checked";//고객 구분 배열 2013-08-05
	$reged_date=substr($event->REGI_DATE,0,10);
	$regi_empl=$event->REGI_EMPL;
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
            for (var i = 0; i < 24; i++) {//combo box로 된 시간 선택 상자
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
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
		
<?php // 진료명 세팅하기
	$rc_sql = "SELECT `RMDY_CODE` FROM `toto_RemedyCode` WHERE `RMDY_CODE` LIKE '%00' AND `USE__FLAG`=\"1\"";
	$rc_hd = mysql_query($rc_sql);
	while($row_rc = mysql_fetch_array($rc_hd)){
	          $rc_parent[]=$row_rc[0];
	}
	$rc_sql = "SELECT `RMDY_CODE`, `RMDY_ENGL` FROM `toto_RemedyCode` WHERE `RMDY_CODE` NOT LIKE '%00' AND `USE__FLAG`=\"1\"";
	$rc_hd = mysql_query($rc_sql);
	while($row_rc = mysql_fetch_array($rc_hd)){
	          $pos=intval($row_rc[0]/100);
	          $rc_child[$pos]["code"][]=$row_rc[0];
	          $rc_child[$pos]["kora"][]=$row_rc[1];
	}
?>
		function rc_setting(obj){
			 rk = document.getElementById("RMDY_KORA");
			 rc = document.getElementById("RMDY_CODE");
			 rc.value = obj.value;
			 rk.value = obj.options[obj.selectedIndex].text;
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
				for($j=0;$j<count($rc_child[$i+1]["code"]);$j++){
					echo "var o".$j." = new Option(\"".$rc_child[$i+1]["kora"][$j]."\",\"".$rc_child[$i+1]["code"][$j]."\", true);\n";		
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
        <form action="./datafeed.php?method=adddetails<?php echo isset($event)?"&id=".$event->Id:""; ?>&re=<?php echo $_SESSION['sunap'];?>" class="fform" id="fmEdit" method="post">
		<div style="border:1px solid #333333;padding:2px;">
		<span><b>&nbsp;<u>고객구분</u> <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="N" onchange="srch_on();" <?php echo $cb["N"];?> />신환 <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="O" <?php echo $cb["O"];?> onchange="srch_on();" />구환&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 고객검색 <input type="text" size="10" name="CUST_CNUM" id="CUST_CNUM" value="<?php echo $event->CUST_CNUM;?>" style="display:none;"></input><input type="text" size="10" id="keyword" name="keyword" value="" disabled></input><input type="button" onclick="cust_srch();" value="검색" id="sc_bt" name="sc_bt" disabled></input>
		</span><br />
		  <span><b>&nbsp;<u>고객성명</u>&nbsp;&nbsp;<input class="required safe" name="CUST_NAME" id="CUST_NAME" size="7" value="<?php echo $event->CUST_NAME;?>"></input>&nbsp;&nbsp;&nbsp; <u>연&nbsp;락&nbsp;처</u> <input class="required safe" name="CUST_TELE" id="CUST_TELE" size="15" value="<?php echo $event->CUST_TELE;?>"></input>&nbsp;&nbsp; 주&nbsp;민&nbsp;번&nbsp;호 <input name="CUST_IDEN" id="CUST_IDEN" size="15" value="" disabled></input></b></span><br />
		  <table><tr><td>고객메모<br />(특이사항)</td><td><textarea id="CUST_MEMO" name="CUST_MEMO" disabled style="width:480px;;height:30px;"></textarea></td></tr></table>
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
              <?php if(isset($event)){
                  $sarr = explode(" ", php2JsTime(mySql2PhpTime($event->StartTime)));
                  $earr = explode(" ", php2JsTime(mySql2PhpTime($event->EndTime)));
              }
			  ?> 
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
			<td style="width:80px;">&nbsp;예&nbsp;약&nbsp;병&nbsp;원</td>
			<td>
				<select name="HOSP_CODE" id="HOSP_CODE" style="width:120px;">
	    	    <option value="">병원선택</option></select>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;<u>진&nbsp;&nbsp;&nbsp;료&nbsp;&nbsp;&nbsp;명</u></td>
			<td>
			<?php
			$rk_sql = "SELECT `RMDY_ENGL` FROM `toto_RemedyCode` WHERE `RMDY_CODE` = '".$event->RMDY_CODE."'";
			$rk_hd=mysql_query($rk_sql);
			$rk_row=mysql_fetch_array($rk_hd);
			?>
				<input type="text" id="RMDY_KORA" name="RMDY_KORA" disabled style="width:120px;" value="<?php echo $rk_row[0];?>" class="required safe"></input>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;처&nbsp;&nbsp;&nbsp;치&nbsp;&nbsp;&nbsp;명</td>
			<td>
				<input type="text" id="CLNC_CODE" name="CLNC_CODE" style="width:120px;" value="<?php echo $event->CLNC_CODE;?>"></input>
			</td>
			</tr>
			<tr>
			<td style="width:80px;">&nbsp;원&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;장</td>
			<td>
				<select name="RMDY_DOCT" id="RMDY_DOCT" style="width:120px;">
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
			<td style="width:80px;">&nbsp;관리&nbsp;/&nbsp;장비</td>
			<td>
				<select name="ASIN_SEQN" id="ASIN_SEQN" style="width:120px;">
	    	    <option value="">관리/장비</option></select>
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
		<td style="width:150px;">진료명&nbsp;<input type="text" style="width:60px;" id="RMDY_CODE" name="RMDY_CODE" value="<?php echo $event->RMDY_CODE;?>"></input>
				<select style="width:50px;" id="par" name="par" onchange="rc_child(this)">
		<?php
			$parent=count($rc_parent);
			for($i=0;$i<$parent;$i++){
			echo "<option value='".$rc_parent[$i]."'>".$rc_parent[$i]."</option>";
			}
		?>
			        </select><hr style="margin: 5px 0 0 0;">
				<select style="width:160px;overflow: scroll;" size="9"" name="chi" id="chi" onchange="rc_setting(this);">   
		<?php
			$child=count($rc_child[1]["code"]);
			for($i=1;$i<$child;$i++){
			echo "<option value='".$rc_child[1]["code"][$i]."'>".$rc_child[1]["kora"][$i]."</option>";
			}
		?>
			</td>
		<td style="width:150px;">처치명&nbsp;<input type="text" style="width:60px;" disabled>
				<select style="width:50px;>
	    	    <option value=""></option></select><hr style="margin: 5px 0 0 0;">
				<select style="width:160px;" size="9">
	    	    <option value=""></option></select>
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
		  <table border="1" width="500">
			<tr>
				<td width="100">진료/처치내역</td><td width="100">예약내역</td><td></td>
			</tr>
			<tr>
				<td colspan="3">
				<table>
					<tr>
						<td>내원병원</td><td>내원일자</td><td>진단명</td><td>처치명</td><td>처치원장</td><td>미등록처치명</td><td>처치직원</td><td>부분</td>
					</tr>
					<tr>
						<td><?php echo $_SESSION['sunap'];?></td><td><?php echo $aclRow[6];?></td><td>진단명</td><td>처치명</td><td>처치원장</td><td>미등록처치명</td><td>처치직원</td><td>부분</td>
					</tr>
				</table>
				</td>
			</tr>
			</table>

            <span>                        장소:
            </span>                    
            <input MaxLength="200" id="Location" name="Location" style="width:95%;" type="text" value="<?php echo isset($event)?$event->Location:""; ?>" />                 
          </label>                   
           
			<label>
            <span>                        Remark:
            </span>                    
<textarea cols="20" id="Description" name="Description" rows="2" style="width:95%; height:70px">
<?php echo isset($event)?$event->Description:""; ?>
</textarea>                
          </label>                
          <input id="timezone" name="timezone" type="hidden" value="" />           
        </form>         
      </div>         
    </div>
  </body>
  <?php
//  var_dump($rc_child);
  ?>
  <script>
  
			var kw = document.getElementById("keyword");
			var sb = document.getElementById("sc_bt");
			var cg = "<?php echo $event->CUST_GUBN;?>";//구환 신환 플래그로 검색창 [비]활성화
		function srch_on(){
			if(cg=="N"){
				kw.removeAttribute('disabled');
				sb.removeAttribute('disabled');
				cg="O";
			}else{
				kw.setAttribute('disabled',true);
				sb.setAttribute('disabled',true);
				cg="N";
			}
		}
			var cn = document.getElementById("CUST_NAME");
			var ct = document.getElementById("CUST_TELE");
			var sj = document.getElementById("Subject");
		function subj(){//달력에 나올 내용 반영
			sj.value=cn.value+" "+ct.value;
		}

	</script>
</html>