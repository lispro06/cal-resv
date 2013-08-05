<?php
header("Content-Type: text/html; charset=UTF-8");
include_once("php/dbconfig.php");
include_once("php/functions.php");
    $db = new DBConnection();
    $db->getConnection();
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");
    $sql = "select `CUST_CNUM`, `CUST_NAME`, `CUST_IDEN` from `toto_customer` where `CUST_NAME` LIKE '%" . $_GET["cust_name"]."%'";
    $handle = mysql_query($sql);
	while($row = mysql_fetch_array($handle)){
		echo "<a href='#' onclick='in_box(\"".$row[2]."\",\"".$row[1]."\");'>".$row[1]."</a><br />";
	}
?>
<html>
<head>
<script>
//	if(opener.name==""){
//		self.close();
//	}else{
//	}
	var ident=opener.document.getElementById("iden");
	var cn=opener.document.getElementById("CUST_NAME");
	function in_box(ssn, nm){
		ident.value = ssn;
		cn.value = nm;
		self.close();
	}
</script>
</head>
</body>
</html>