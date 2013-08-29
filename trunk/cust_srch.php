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
    $sql = "select `CUST_CNUM`, `CUST_NAME`, `CUST_IDEN`, `CUST_MEMO` from `toto_customer` where `CUST_NAME` LIKE '%" . $_GET["cust_name"]."%'";
    $handle = mysql_query($sql);
	while($row = mysql_fetch_array($handle)){
		echo "<a href='#' onclick='in_box(\"".$row[0]."\",\"".$row[1]."\",\"".$row[2]."\",\"".$row[3]."\");'>".$row[1]."</a> ".$row[2]."<br />";
	}
?>
<html>
<head>
<script>
//	if(opener.name==""){
//		self.close();
//	}else{
//	}
	var cc=opener.document.getElementById("CUST_CNUM");
	var cn=opener.document.getElementById("CUST_NAME");
	var ci=opener.document.getElementById("CUST_IDEN");
	var cm=opener.document.getElementById("CUST_MEMO");
	function in_box(cnum, name, ssn, memo){
		cc.value = cnum;
		cn.value = name;
		ci.value = ssn;
		cm.value = memo;
		self.close();
	}
</script>
</head>
</body>
</html>