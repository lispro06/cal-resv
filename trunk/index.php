<!--@if($logged_info=="")-->
<?php
if($_GET["login"]=="out"){//로그아웃
	$_SESSION['sunap']="";
}
if(!defined('__ZBXE__')) exit();
	include("./files/config/db.config.php");
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
 require_once('./config/config.inc.php');
 $oContext = &Context::getInstance();
 $oContext->init();
    
 $logged_info = Context::get('logged_info'); 
 $id = $logged_info->user_id;
if(!$_SESSION['sunap']){
	if($logged_info->is_admin=="Y" || $logged_info->group_list[3]=="정회원"){
		$_SESSION['sunap']="";
		$aclSql="select * from `toto_acl` where `user_id`='".$_POST['idtxt']."' and `user_pw`='".$_POST['pdtxt']."'";
		$aclRes = mysql_query($aclSql); 
		$aclRow = mysql_fetch_row($aclRes);
	}
	if($aclRow[1]){//로그인 성공시 세션 등록
		session_register("sunap");
		$_SESSION['sunap']=$aclRow[1];
	}
}else{
	$aclSql="select * from `toto_acl` where `user_id`='".$_SESSION['sunap']."'";
	$aclRes = mysql_query($aclSql); 
	$aclRow = mysql_fetch_row($aclRes);
}
if($aclRow[8]!="Y"){//권한이 없으면 로그인 화면
	$_SESSION['sunap']="";//세션을 초기화 해준다.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<div style="text-align:center;position:relative;top:100px;">
			<center>
			<table style="text-align:center;" width="500">
			<tr>
           <td colspan="2"><img src="./sunap/logo/logins.gif"></td>
			</tr>
			<tr>
			<td align="center"><br />
				<form method="post" name="fr" id="fr">
				<div style="align:center;vertical-align:bottom;height:200px;width:500px;border:3px solid #e6e6e6;"><br /><br />
				<table>
					<tr>
						<td><span style="bolder;">아이디</span></td>
						<td><input type="text" name="idtxt" id="idtxt" tabindex="1" onKeyDown="javascript:if(event.keyCode == 13) { idInput(this); return false;}" style="width:150px;border: 3px solid #e6e6e6;" value="<?php echo $_POST['idtxt']?>" maxlength="20"></td>
						<td rowspan=2><input type="image" tabindex="3" src="./sunap/lb.jpg" value="로그인" onClick="submit();"></td>
					</tr>
						<tr>
							<td>비밀번호</td>
							<td><input type="password" name="pdtxt" id="pdtxt" tabindex="2" onKeyDown="javascript:if(event.keyCode == 13) { pwInput(this); return false;}" style="width:150px;border: 3px solid #e6e6e6;" value="<?php echo $_POST['pdtxt']?>" maxlength="20"></td>
						</tr>
					<tr>
						<td colspan="3"><br />
						<img src="./sunap/only.jpg">
						</td>
					</tr>
				</table>
				</div>
			</td>
			<form>
			</tr>
			</table>
			</center>
			</div>
			<script>
			document.getElementById("idtxt").focus();
			function idInput(obj){
				if(obj.value==""){
					alert("아이디를 입력하세요");
					obj.focus();
				}else{
					document.getElementById("pdtxt").focus();
				}
			}
			function pwInput(obj){
				if(obj.value==""){
					alert("비밀번호를 입력하세요");
					obj.focus();
				}else{
					submit();
				}
				return false;
			}
			</script>
</html>
<?php
	exit();
}
?>
<!--@end-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
    <title>	My Calendar </title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="css/dailog.css" rel="stylesheet" type="text/css" />
    <link href="css/calendar.css" rel="stylesheet" type="text/css" /> 
    <link href="css/dp.css" rel="stylesheet" type="text/css" />   
    <link href="css/alert.css" rel="stylesheet" type="text/css" /> 
    <link href="css/main.css" rel="stylesheet" type="text/css" /> 
    

    <script src="src/jquery.js" type="text/javascript"></script>  
    
    <script src="src/Plugins/Common.js" type="text/javascript"></script>    
    <script src="src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>     
    <script src="src/Plugins/jquery.datepicker.js" type="text/javascript"></script>

    <script src="src/Plugins/jquery.alert.js" type="text/javascript"></script>    
    <script src="src/Plugins/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script>
    <script src="src/Plugins/wdCalendar_lang_KO.js" type="text/javascript"></script>    
    <script src="src/Plugins/jquery.calendar.js" type="text/javascript"></script>   
    
    <script type="text/javascript">
	var doct_ck;
	var dev_ck;
        $(document).ready(function() {
		 doct_ck=document.getElementById("doctdiv").checked;//원장 체크 여부 확인
		 dev_ck=document.getElementById("devdiv").checked;//장비 체크 여부 확인
		 hosp_code=document.getElementById("HOSP_CODE").value;//병원 선택
		 
           var view="day";          
           var re="<?php echo $_SESSION['sunap'];?>";
            var DATA_FEED_URL = "./cal/datafeed.php";
            var op = {
                doc: doct_ck,
                dev: dev_ck,
                hc: hosp_code,
                view: view,
                theme:3,
                showday: new Date(),
                EditCmdhandler:Edit,
                EditCmdhandler2:Edit2,
                EditCusthandler:custEdit,
                DeleteCmdhandler:Delete,
                ViewCmdhandler:View,    
                onWeekOrMonthToDay:wtd,
                onBeforeRequestData: cal_beforerequest,
                onAfterRequestData: cal_afterrequest,
                onRequestDataError: cal_onerror, 
                autoload:true,
                url: DATA_FEED_URL + "?method=list",  
                quickAddUrl: DATA_FEED_URL + "?method=add"+"&re="+re, 
                quickUpdateUrl: DATA_FEED_URL + "?method=update"+"&re="+re,
                quickDeleteUrl: DATA_FEED_URL + "?method=remove"        
            };
            var $dv = $("#calhead");
            var _MH = document.documentElement.clientHeight;
            var dvH = $dv.height() + 2;
            op.height = _MH - dvH;
            op.eventItems =[];

            var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
            $("#caltoolbar").noSelect();
            
            $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
            onReturn:function(r){                          
                            var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                            if (p && p.datestrshow) {
                                $("#txtdatetimeshow").text(p.datestrshow);
                            }
                     } 
            });
            function cal_beforerequest(type)
            {
                var t="처리중...";
                switch(type)
                {
                    case 1:
                        t="읽는중...";
                        break;
                    case 2:                      
                    case 3:  
                    case 4:    
                        t="잠시만 기다리세요...";                                   
                        break;
                }
                $("#errorpannel").hide();
                $("#loadingpannel").html(t).show();    
            }
            function cal_afterrequest(type)
            {
                switch(type)
                {
                    case 1:
                        $("#loadingpannel").hide();
                        break;
                    case 2:
                    case 3:
                    case 4:
                        $("#loadingpannel").html("성공!");
                        window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                    break;
                }              
               
            }
            function cal_onerror(type,data)
            {
                $("#errorpannel").show();
		var info = '';
//  		for (var imsi in data) {
//		    info += imsi + ' = ' + data[imsi] + '\n';
//		}
                window.setTimeout(function(){ $("#gridcontainer").reload(); },1);
		alert(data.Msg);
 		// 과거 날짜 입력 발생에 따른 메시지 발생 2013-08-12
            }
            function Edit(data)
            {//2013-08-05 세부 일정 클릭 시 내용 전송 jquery.calendar.js 2478,25 / jqeury.calendar.js 2502,25 참고
               var what = $("#bbit-cal-what").val();
               var datestart = $("#bbit-cal-start").val();
               var dateend = $("#bbit-cal-end").val();
               var allday = $("#bbit-cal-allday").val();
               var eurl="./cal/edit.php?id={0}&start="+datestart+"&end="+dateend+"&isallday={4}&title="+what;
                if(data)
                {
                    var url = StrFormat(eurl,data);
                    OpenModelWindow(url,{ width: 600, height: 400, caption:"세부 내용 입력",onclose:function(){
                       $("#gridcontainer").reload();
                    }});
                }
            }
            function Edit2(data)
            {//2013-08-05 세부 일정 클릭 시 내용 전송 jquery.calendar.js 2478,25 / jqeury.calendar.js 2502,25 참고
               var what = $("#bbit-cal-what").val();
               var datestart = $("#bbit-cal-start").val();
               var dateend = $("#bbit-cal-end").val();
               var allday = $("#bbit-cal-allday").val();
               var eurl="./cal/edit.php?id={0}&start="+datestart+"&end="+dateend+"&isallday={4}&title="+what+"&same=1";
                if(data)
                {
                    var url = StrFormat(eurl,data);
                    OpenModelWindow(url,{ width: 600, height: 400, caption:"세부 내용 입력",onclose:function(){
                       $("#gridcontainer").reload();
                    }});
                }
            }
            function custEdit(data)
            {//2013-08-18 고객정보 수정
               var what = $("#bbit-cal-what").val();
               var datestart = $("#bbit-cal-start").val();
               var dateend = $("#bbit-cal-end").val();
               var allday = $("#bbit-cal-allday").val();
               var eurl="./cal/customer.php?id={0}&start="+datestart+"&end="+dateend+"&isallday={4}&title="+what;
                if(data)
                {
                    var url = StrFormat(eurl,data);
                    OpenModelWindow(url,{ width: 740, height: 400, caption:"고객 정보 입력",onclose:function(){
                       $("#gridcontainer").reload();
                    }});
                }
            }    
            function View(data)
            {
                var str = "";
                $.each(data, function(i, item){
                    str += "[" + i + "]: " + item + "\n";
                });
                alert(str);               
            }    
            function Delete(data,callback)
            {           
                
                $.alerts.okButton="완료";  
                $.alerts.cancelButton="취소";  
                hiConfirm("예약을 삭제합니까?", '확인',function(r){ r && callback(0);});           
            }
            function wtd(p)
            {
               if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $("#showdaybtn").addClass("fcurrent");
            }
            //to show day view
            $("#showdaybtn").click(function(e) {
                //document.location.href="#day";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            //to show week view
            $("#showweekbtn").click(function(e) {
                //document.location.href="#week";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //to show month view
            $("#showmonthbtn").click(function(e) {
                //document.location.href="#month";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
            $("#showreflashbtn").click(function(e){
                $("#gridcontainer").reload();
            });
            
            //Add a new event
            $("#faddbtn").click(function(e) {
                var url ="./cal/edit.php";
                OpenModelWindow(url,{ width: 500, height: 400, caption: "새 예약 입력"});
            });
            //go to today
            $("#showtodaybtn").click(function(e) {
                var p = $("#gridcontainer").gotoDate().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }


            });
            //previous date range
            $("#sfprevbtn").click(function(e) {
                var p = $("#gridcontainer").previousRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //next date range
            $("#sfnextbtn").click(function(e) {
                var p = $("#gridcontainer").nextRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            // 원장
            $("#doctdiv").click(function(e){
				op.doc=document.getElementById("doctdiv").checked;//원장 체크 여부 확인
				op.dev=document.getElementById("devdiv").checked;//장비 체크 여부 확인
				op.hc=document.getElementById("HOSP_CODE").value;//병원 선택
				$("#gridcontainer").BcalSetOp(op);
                $("#gridcontainer").reload();
            });
            // 장비
            $("#devdiv").click(function(e){
				op.doc=document.getElementById("doctdiv").checked;//원장 체크 여부 확인
				op.dev=document.getElementById("devdiv").checked;//장비 체크 여부 확인
				op.hc=document.getElementById("HOSP_CODE").value;//병원 선택
				$("#gridcontainer").BcalSetOp(op);
                $("#gridcontainer").reload();
            });
            // 병원
            $("#HOSP_CODE").change(function(e){
				op.doc=document.getElementById("doctdiv").checked;//원장 체크 여부 확인
				op.dev=document.getElementById("devdiv").checked;//장비 체크 여부 확인
				op.hc=document.getElementById("HOSP_CODE").value;//병원 선택
				$("#gridcontainer").BcalSetOp(op);
                $("#gridcontainer").reload();
            });
        });
    </script>    
</head>
<body>
    <div>

      <div id="calhead" style="padding-left:1px;padding-right:1px;">          
            <div class="cHead"><div class="ftitle">예약관리</div>
            <div id="logout" class="ptogtitle loaderror"><a href="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];?>&login=out">로그아웃</a></div>
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">데이터 처리중...</div>
             <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">문제가 발생했습니다.</div>
            </div>          
            
            <div id="caltoolbar" class="ctoolbar">
              <div id="faddbtn" class="fbutton">
                <div><span title='새 예약을 입력하시려면 클릭하세요.' class="addcal">

                새 예약                
                </span></div>
            </div>
            <div class="btnseparator"></div>
             <div id="showtodaybtn" class="fbutton">
                <div><span title='오늘 날짜로 ' class="showtoday">
                오늘</span></div>
            </div>
              <div class="btnseparator"></div>

            <div id="showdaybtn" class="fbutton fcurrent">
                <div><span title='일' class="showdayview">일</span></div>
            </div>
              <div  id="showweekbtn" class="fbutton">
                <div><span title='주' class="showweekview">주</span></div>
            </div>
              <div  id="showmonthbtn" class="fbutton">
                <div><span title='월' class="showmonthview">월</span></div>

            </div>
            <div class="btnseparator"></div>
              <div  id="showreflashbtn" class="fbutton">
                <div><span title='다시 읽기' class="showdayflash">새로고침</span></div>
                </div>
             <div class="btnseparator"></div>
            <div id="sfprevbtn" title="이전"  class="fbutton">
              <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="다음" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                    <div>
                        <input type="hidden" name="txtshow" id="hdtxtshow" />
                        <span id="txtdatetimeshow">-</span>

                    </div>
            </div>
            
            <div id="optiondiv" title="선택" class="fbutton">
                <span><input type="checkbox" id="doctdiv" name="doctdiv" checked>원장<input type="checkbox" id="devdiv" name="devdiv" checked>장비</span>
            </div>
            <div id="hospdiv" title="병원선택" class="fbutton">
                <span><select name="HOSP_CODE" id="HOSP_CODE" style="width:120px;">
	    	    <option value=""></option>
	    	    <option value="1234" <?php echo $code["1234"];?>>기본병원</option></select></span>
            </div>
            <div class="clear"></div>
            </div>
      </div>
      <div style="padding:1px;">

        <div class="t1 chromeColor">
            &nbsp;</div>
        <div class="t2 chromeColor">
            &nbsp;</div>
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        <div class="t2 chromeColor">

            &nbsp;</div>
        <div class="t1 chromeColor">
            &nbsp;
        </div>   
        </div>
     
  </div>
    
</body>
</html>
