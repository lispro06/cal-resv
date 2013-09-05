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
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");
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
	if($event->CUST_CNUM){
	$c_sql = "select * from `toto_customer` where `CUST_CNUM` = '".$event->CUST_CNUM."';";
    	$c_handle = mysql_query($c_sql);
	$cu_row = mysql_fetch_array($c_handle);
	$cust_name=$cu_row[2];
	$cust_iden=$cu_row[3];
	$cust_tele=$cu_row[5];
	$cust_hand=$cu_row[6];
	$cust_bith=$cu_row[8]."-".$cu_row[9];
	$home_post=$cu_row[11];
	$home_addr=$cu_row[12];
	$offi_post=$cu_row[13];
	$offi_addr=$cu_row[14];
	$mary_date=$cu_row[20]."-".$cu_row[21];
	$cust_mail=$cu_row[22];
	$cust_memo=$cu_row[32];
		if($cu_row[7]=="Y"){//SMS 수신함
			$sc="checked";
		}
		if($cu_row[4]=="Y"){//확인된 주민번호
			$ic="checked";
		}
		if($cu_row[10]==0){//음력/양력
			$bf[0]="checked";
		}else{
			$bf[1]="checked";
		}
		if($cu_row[15]=="Y"){//연락가능한주소
			$ac="checked";
		}
		if($cu_row[17]=="O"){//배송지
			$pg[1]="checked";
		}else{
			$pg[0]="checked";
		}
		if($cu_row[18]=="O"){//병원인접주소
			$xg[1]="checked";
		}else{
			$xg[0]="checked";
		}
		if($cu_row[19]=="M"){//남성/여성
			$sg[1]="checked";
		}else{
			$sg[0]="checked";
		}
		if($cu_row[20]==""){//결혼여부
			$mf[0]="checked";
		}else{
			$mf[1]="checked";
		}
		if($cu_row[23]=="Y"){//메일 수신
			$mc="checked";
		}
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
    <title>고객 세부 내용</title>    
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
            //$("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
            //to define parameters of ajaxform
            var options = {
                beforeSubmit: function() {
                    return true;
                },
                dataType: "json",
                success: function(data) {
                    //alert();
                    //if (data.IsSuccess=1) {
                        CloseModelWindow(null,true);  
                    //}
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
			$("#CUST_HAND").focus(function () {
			var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
			}).keydown(function(e) {
				if( e.keyCode != '8' && e.keyCode != '46' ){
					var input_text=$(this).val();
					if( input_text.length == '3' || input_text.length == '8' ){
						var add_hyphen=$(this).val()+'-';
						$(this).val(add_hyphen);
					}
				}
			}).focusout(function() {
				var this_len=($(this).val()).length;
				if( this_len <= '12' ){
					//var hyphen_chg=/\d{3}-\d{3}-\d{4}$/g;
					var str=$(this).val().replace(/[-]/gi,'');
					//var string=str.replace(/(^01[0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,"$1-$2-$3");
					$(this).val(string);
				}
			});
			$("#CUST_BITH").focus(function () {
			var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
			}).keydown(function(e) {
				if( e.keyCode != '8' && e.keyCode != '46' ){
					var input_text=$(this).val();
					if( input_text.length == '4' || input_text.length == '7' ){
						var add_hyphen=$(this).val()+'-';
						$(this).val(add_hyphen);
					}
				}
			}).focusout(function() {
				var this_len=($(this).val()).length;
				if( this_len <= '12' ){
					var string=str.replace(/(^01[0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,"$1-$2-$3");
					$(this).val(string);
				}
			});
			$("#MARY_DATE").focus(function () {
			var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
			}).keydown(function(e) {
				if( e.keyCode != '8' && e.keyCode != '46' ){
					var input_text=$(this).val();
					if( input_text.length == '4' || input_text.length == '7' ){
						var add_hyphen=$(this).val()+'-';
						$(this).val(add_hyphen);
					}
				}
			}).focusout(function() {
				var this_len=($(this).val()).length;
				if( this_len <= '12' ){
					var string=str.replace(/(^01[0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,"$1-$2-$3");
					$(this).val(string);
				}
			});
			$("#CUST_IDEN").focus(function () {
			var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
			}).keydown(function(e) {
				if( e.keyCode != '8' && e.keyCode != '46' ){
					var input_text=$(this).val();
					if( input_text.length == '6'){
						var add_hyphen=$(this).val()+'-';
						$(this).val(add_hyphen);
					}
				}
			}).focusout(function() {
				var this_len=($(this).val()).length;
				if( this_len <= '13' ){
					var string=str.replace(/(^01[0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,"$1-$2-$3");
					$(this).val(string);
				}
			});
        });
		
<?php // 처치명 세팅하기
	$cc_sql = "SELECT `CLNC_CODE` FROM `toto_ClinicCode` WHERE `CLNC_CODE` LIKE '%00' AND `USE__FLAG`=\"1\"";
	$cc_hd = mysql_query($cc_sql);
	while($row_cc = mysql_fetch_array($cc_hd)){
	          $cc_parent[]=$row_cc[0];
	}
	$cc_sql = "SELECT `CLNC_CODE`, `CLNC_KORA` FROM `toto_ClinicCode` WHERE `CLNC_CODE` NOT LIKE '%00' AND `USE__FLAG`=\"1\"";
	$cc_hd = mysql_query($cc_sql);
	while($row_cc = mysql_fetch_array($cc_hd)){
	          $pos=intval($row_cc[0]/100);
	          $cc_child[$pos]["code"][]=$row_cc[0];
	          $cc_child[$pos]["kora"][]=$row_cc[1];
	}
?>
		function cc_setting(obj){
			 ck = document.getElementById("CLNC_KORA");
			 cc = document.getElementById("CLNC_CODE");
			 cc.value = obj.value;
			 ck.value = obj.options[obj.selectedIndex].text;
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
					echo "var o".$j." = new Option(\"".$cc_child[$i]["kora"][$j]."\",\"".$cc_child[$i]["code"][$j]."\", true);\n";		
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
	$rc_sql = "SELECT `RMDY_CODE`, `RMDY_KORA` FROM `toto_RemedyCode` WHERE `RMDY_CODE` NOT LIKE '%00' AND `USE__FLAG`=\"1\"";
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
				for($j=0;$j<count($rc_child[$i]["code"]);$j++){
					echo "var o".$j." = new Option(\"".$rc_child[$i]["kora"][$j]."\",\"".$rc_child[$i]["code"][$j]."\", true);\n";		
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
          <span class="Save" title="예약 입력">저장(<u>S</u>)
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
        <form action="./datafeed.php?method=customer&re=<?php echo $_SESSION['sunap'];?>" class="fform" id="fmEdit" method="post">
		<div style="border:1px solid #333333;padding:2px;">
		<b>&nbsp;고&nbsp;객&nbsp;검&nbsp;색 <input type="text" size="10" name="CUST_CNUM" id="CUST_CNUM" value="<?php echo $event->CUST_CNUM;?>" style="display:none;"></input><input type="text" size="10" id="keyword" name="keyword" value="" disabled></input><input type="button" onclick="cust_srch();" value="검색" id="sc_bt" name="sc_bt" disabled></input>
		</span><br />
		  <span><b>&nbsp;<u>고&nbsp;객&nbsp;성&nbsp;명</u>&nbsp;<input class="required safe" name="CUST_NAME" id="CUST_NAME" size="7" value="<?php echo $cust_name;?>"></input>&nbsp;&nbsp;
		  <br />&nbsp;<u>주&nbsp;민&nbsp;번&nbsp;호</u></b>&nbsp;<input name="CUST_IDEN" id="CUST_IDEN" size="15" value="<?php echo $cust_iden;?>" style="ime-mode:disabled" maxlength="14" onKeyPress="NumObj(event,this);"></input>&nbsp;&nbsp; <input type="checkbox" name="IDEN_CHCK" id="IDEN_CHCK" style="ime-mode:disabled" onKeyPress="NumObj(event,this);" value="Y" <?php echo $ic;?> />확인된 주민번호임</span><br />
		  <span><b>&nbsp;<u>실&nbsp;제&nbsp;생&nbsp;일</u>&nbsp;<input class="required safe" name="CUST_BITH" id="CUST_BITH" size="10" value="<?php echo $cust_bith;?>" style="ime-mode:disabled" onKeyPress="NumObj(event,this);" maxlength="10"></input>&nbsp;[<input type="radio" name="BITH_FLAG" id="BITH_FLAG" value="0" <?php echo $bf[0];?> />음 <input type="radio" name="BITH_FLAG" id="BITH_FLAG" value="1" <?php echo $bf[1];?> />양]</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;담당원장
				<select name="CHRG_DOCT" id="CHRG_DOCT" style="width:120px;">
	    	    <option value="">원장 선택</option>
			<?php
			
			$rmdy_sql="SELECT * FROM  `toto_doctor`";	
    			$rmdy_hd = mysql_query($rmdy_sql);
			//$row = mysql_fetch_array($rmdy_hd);//원장선택 추가 2013-09-03
			//$tt=mysql_affected_rows();
				while($row = mysql_fetch_array($rmdy_hd)){
					   if($row[1]==$event->CHRG_DOCT){
						echo "<option value='".$row[1]."' selected>".$row[2]."</option>";
						}else{
						echo "<option value='".$row[1]."'>".$row[2]."</option>";
						}
					   }
			?>
    			</select>
		  <br />&nbsp;<u>성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;별 </u>&nbsp; [<input type="radio" name="SEX_GUBN" id="SEX_GUBN" value="F" <?php echo $sg[0];?> />여성 <input type="radio" name="SEX_GUBN" id="SEX_GUBN" value="M" <?php echo $sg[1];?> />남성]</input></b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;담당처치직원
				<select name="AEST_EMPL" id="AEST_EMPL" style="width:120px;">
	    	    <option value="">직원 선택</option>
			<?php
			
			$AEST_EMPL_sql="SELECT * FROM  `toto_empl`";	
    		$AEST_EMPL_hd = mysql_query($AEST_EMPL_sql);
			//$row = mysql_fetch_array($rmdy_hd);//직원선택 추가 2013-09-03
			//$tt=mysql_affected_rows();
				while($row = mysql_fetch_array($AEST_EMPL_hd)){
					   if($row[1]==$event->AEST_EMPL){
						echo "<option value='".$row[1]."' selected>".$row[2]."</option>";
						}else{
						echo "<option value='".$row[1]."'>".$row[2]."</option>";
						}
					   }
			?>
    			</select>
		  <br />&nbsp;전&nbsp;화&nbsp;번&nbsp;호 <input name="CUST_TELE" id="CUST_TELE" size="15" value="<?php echo $cust_tele;?>" maxlength="13"></input>
		  <br />&nbsp;휴대폰번호 <input name="CUST_HAND" id="CUST_HAND" size="15" value="<?php echo $cust_hand;?>"  style="ime-mode:disabled" onKeyPress="NumObj(event,this);" maxlength="13"></input>&nbsp;&nbsp; <input type="checkbox" name="SMS_CHCK" id="SMS_CHCK" value="Y" <?php echo $sc;?> /> SMS 수신함 Ex) 011-1234-5678
		  <br />&nbsp;<u>우편물수령지</u>&nbsp; [<input type="radio" name="POST_GUBN" id="POST_GUBN" class="required safe" value="H" <?php echo $pg[0];?> />자택 <input type="radio" name="POST_GUBN" id="POST_GUBN" value="O" <?php echo $pg[1];?> />직장]</input></b>&nbsp;&nbsp; <input type="checkbox" name="ADDR_CHCK" id="ADDR_CHCK" value="Y" <?php echo $ac;?> /> 연락가능한 주소임&nbsp;&nbsp;  병원인접주소[<input type="radio" name="PROX_GUBN" id="PROX_GUBN" value="H" <?php echo $xg[0];?> />자택 <input type="radio" name="PROX_GUBN" id="PROX_GUBN" value="O" <?php echo $xg[1];?> />직장]</input> 연락불가능사유<select id="CAUS_GUBN" name="CAUS_GUBN"><option>&nbsp;&nbsp;&nbsp;&nbsp;</option></select></span>
		  <br />&nbsp;<b>자&nbsp;택&nbsp;주&nbsp;소 <input name="HOME_POST" id="HOME_POST" size="15" value="<?php echo $home_post;?>" readonly></input>
		  <a href="#" onclick="zip_open(0);">[우편번호]</a>  <input name="HOME_ADDR" id="HOME_ADDR" size="35" value="<?php echo $home_addr;?>"></input>
		  <br />&nbsp;직&nbsp;장&nbsp;주&nbsp;소 <input name="OFFI_POST" id="OFFI_POST" size="15" value="<?php echo $offi_post;?>" readonly></input>
		  <a href="#" onclick="zip_open(1);">[우편번호]</a>  <input name="OFFI_ADDR" id="OFFI_ADDR" size="35" value="<?php echo $offi_addr;?>"></input>
		  <br />&nbsp;이&nbsp;&nbsp;메&nbsp;&nbsp;일&nbsp;&nbsp;<input name="CUST_MAIL" id="CUST_MAIL" size="25" value="<?php echo $cust_mail;?>"></input>
		  &nbsp;&nbsp; <input type="checkbox" name="MAIL_CHCK" id="MAIL_CHCK" value="Y" <?php echo $mc;?> /> 이메일수신함<br />
		  <b>&nbsp;결혼기념일&nbsp; <input name="MARY_DATE" id="MARY_DATE" size="10" value="<?php echo $mary_date;?>" style="ime-mode:disabled" maxlength="10"></input>&nbsp;[<input type="radio" name="BITH_FLAG" id="BITH_FLAG" value="0" <?php echo $mf[0];?> />미혼 <input type="radio" name="MARY_FLAG" id="MARY_FLAG" value="1" <?php echo $mf[1];?> />기혼]</input>&nbsp;
		  <br />
		  <table><tr><td>고객메모<br />(특이사항)</td><td><textarea id="CUST_MEMO" name="CUST_MEMO" style="width:480px;;height:30px;"><?php echo $cust_memo;?></textarea></td></tr></table>
		  </div>
		  <hr style="margin: 5px 0 0 0;border-color:white" >
		  <label style="display:none;">
            <span>*제목:<input MaxLength="200" id="Subject" name="Subject" style="width:70%;" type="text" value="<?php echo isset($event)?$event->Subject:"" ?><?php echo $title_G;?>" />(달력에 보일 내용)
            </span></label>
            <div id="calendarcolor" style="display:none;">
            </div>                    
            <div style="border:1px solid #333333;padding:2px;display:none" >
			<table>
			<tr>
			<td>
			&nbsp;<u>예&nbsp;약&nbsp;일&nbsp;정</u>&nbsp;&nbsp;
              <?php if(isset($event)){
                  $sarr = explode(" ", php2JsTime(mySql2PhpTime($event->StartTime)));
                  $earr = explode(" ", php2JsTime(mySql2PhpTime($event->EndTime)));
              }
			  ?> 
                                              
              <label class="checkp"> 
                <input id="IsAllDayEvent" name="IsAllDayEvent" type="checkbox" value="1" <?php if(isset($event)&&$event->IsAllDayEvent!=0) {echo "checked";} ?>/>          하루 종일                      
              </label>
			  </td>
			  </tr>
			  </table>
            <input id="colorvalue" name="colorvalue" type="hidden" value="<?php echo isset($event)?$event->Color:"" ?>" />
            </div>                
          </label>                 
          <label>
		  <table border="1" width="550">
			<tr>
				<td width="100">패밀리</td><td width="100">소개한고객</td><td>소개받은고객</td>
			</tr>
			<tr>
				<td colspan="3">
				<table>
					<tr>
						<td>관계</td><td>고객명</td><td>주민번호</td><td>고객번호</td><td>패밀리고객번호</td>
					</tr>
<?php
	if($event->CUST_CNUM){
		$sql = "SELECT `HOSP_CODE`, `RESV_YYMD`, `RMDY_CODE`, `CLNC_CODE`, `RMDY_DOCT` FROM `jqcalendar` ORDER BY `StartTime` DESC LIMIT 0, 5";
		$c_hd = mysql_query($sql);
		while($c_row = mysql_fetch_array($c_hd)){
		     echo "<tr>\n";
		     echo "<td>".$c_row[0]."</td><td>".$c_row[1]."</td><td>".$c_row[2]."</td><td>".$c_row[3]."</td><td>".$c_row[4]."</td>";
		     }
		}else{
?>
		<td><?php echo $_SESSION['sunap'];?></td><td><?php echo $aclRow[6];?></td><td>진단명</td><td>처치명</td><td>처치원장</td>
					</tr>
<?php
}
?>
				</table>
				</td>
			</tr>
			</table>
<!--
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
          </label>                -->
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
		function zip_open(ho){
			var url = "./zip_srch.php?ho=" + ho;
			window.open(url,'','width=400, height=300, toolbar=no, menubar=no, location=no, directories=0, status=0,scrollbar=0,resize=0');
		}
		function NumObj(e,val){
			var code = (window.event) ? event.keyCode : e.which; //IE : FF - Chrome both
			if (code > 32 && code < 48) nAllow(e);
			if (code > 57 && code < 65) nAllow(e);
			if (code > 90 && code < 127) nAllow(e);
		}
		function nAllow(e){
        if(navigator.appName!="Netscape"){ //for not returning keycode value
            event.returnValue = false;  //IE ,  - Chrome both
        }else{
            e.preventDefault(); //FF ,  - Chrome both
        }        
    }
	</script>
</html>