<?php
header("Content-Type: text/html; charset=UTF-8");

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
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");
    $sql = "select * from `kr_zipcode` where `addr3` LIKE '%" . $_GET["zip_kwd"]."%' LIMIT 0,20";
    if($_GET["ho"]){//직장 또는 집에 대한 플래그
	$ho=1;
	}else{
	$ho=0;
	}
?>
<html>
<head>
</head>
<body>
<form action="./zip_srch.php" method="GET">
<input type="text" id="zip_kwd" name="zip_kwd"></input>
<input type="hidden" id="ho" name="ho" value="<?php echo $ho;?>"></input>
<input type="submit" value="검색"></input>
</form>

<?php
    $handle = mysql_query($sql);
	while($row = mysql_fetch_array($handle)){
		echo "<a href='#' onclick='in_box(\"".$row[0]."\",\"".$row[1]." ".$row[2]." ".$row[3]." ".$row[4]."\",".$ho.");'>".$row[0]."</a> ".$row[1]." ".$row[2]." ".$row[3]." ".$row[4]."<br />";
	}
?>
<script>
//	if(opener.name==""){
//		self.close();
//	}else{
//	}
	var op=opener.document.getElementById("OFFI_POST");
	var hp=opener.document.getElementById("HOME_POST");
	var oa=opener.document.getElementById("OFFI_ADDR");
	var ha=opener.document.getElementById("HOME_ADDR");
	function in_box(zip, addr, ho){
	    if(ho==1){
		//oa.value = addr;
		op.value = zip;
		}else{
		//ha.value = addr;
		hp.value = zip;
		}
		self.close();
	}
</script>
</body>
</html>