<?php
include_once("php/dbconfig.php");
include_once("php/functions.php");
// db 커넥션을 무조건 한다. 2013-08-05
    $db = new DBConnection();
    $db->getConnection();
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
}else{//2013-08-05 세부 일정 클릭 시 내용 수신
	$cb["N"]="checked";//새 일정일 경우 고객은 신환으로 설정
	$title_G = $_GET["title"];
	$sarr_G = explode(" ", $_GET["start"]);
	$earr_G = explode(" ", $_GET["end"]);
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
            var DATA_FEED_URL = "php/datafeed.php";
            var arrT = [];
            var tt = "{0}:{1}";
            for (var i = 0; i < 24; i++) {
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
          <span class="Save"  title="예약 입력">저장(<u>S</u>)
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
        <form action="php/datafeed.php?method=adddetails<?php echo isset($event)?"&id=".$event->Id:""; ?>" class="fform" id="fmEdit" method="post">
		<span><b>*고객구분 <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="N" onchange="srch_on();" <?php echo $cb["N"];?> />신환 <input type="radio" name="CUST_GUBN" id="CUST_GUBN" value="O" <?php echo $cb["O"];?> onchange="srch_on();" />구환&nbsp;&nbsp;&nbsp; 고객검색 <input type="text" disabled size="10" name="iden" id="iden"></input><input type="text" size="10" id="keyword" name="keyword" value="" disabled></input><input type="button" onclick="cust_srch();" value="검색" id="sc_bt" name="sc_bt" disabled></input>
		</span><br />
		  <span><b>*고객성명: <input class="required safe" name="CUST_NAME" id="CUST_NAME" size="7" value="<?php echo $event->CUST_NAME;?>"></input>&nbsp;&nbsp;&nbsp; *연락처: <input class="required safe" name="CUST_TELE" id="CUST_TELE" size="15" value="<?php echo $event->CUST_TELE;?>"></input>&nbsp;&nbsp;&nbsp; 주민번호: <input name="CUST_IDEN" id="CUST_IDEN" size="15" value="<?php echo $event->CUST_CNUM;?>"></input></b></span>
		  <label>
            <span>                        *제목:
            </span>                    
            <div id="calendarcolor">
            </div>
            <input MaxLength="200" class="required safe" id="Subject" name="Subject" style="width:85%;" type="text" value="<?php echo isset($event)?$event->Subject:"" ?><?php echo $title_G;?>" />                     
            <input id="colorvalue" name="colorvalue" type="hidden" value="<?php echo isset($event)?$event->Color:"" ?>" />                
          </label>                 
          <label>                    
            <span>*일시:
            </span>                    
            <div>  
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
            </div>                
          </label>                 
          <label>                    
            <span>                        장소:
            </span>                    
            <input MaxLength="200" id="Location" name="Location" style="width:95%;" type="text" value="<?php echo isset($event)?$event->Location:""; ?>" />                 
          </label>                   
<?php
    $rmdy_sql="SELECT * FROM  `toto_doctor`";	
    $rmdy_hd = mysql_query($rmdy_sql);
	
?>
            <span>원장:                  
	    <select name="RMDY_DOCT" id="RMDY_DOCT">
	    	    <option value="">원장 선택</option>
<?php
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
            </span>
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
	</script>
</html>