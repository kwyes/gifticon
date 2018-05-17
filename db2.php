<?
define("SRY_DB_SERVER", "11.11.11.11");		// telus
define("SRY_DB_USERID", "111");
define("SRY_PASSWORD", "111");
define("SRY_DB_NAME", "111");

$sryinfo = array( "Database"=>SRY_DB_NAME, "UID"=>SRY_DB_USERID, "PWD"=>SRY_PASSWORD);
$conn_sry = sqlsrv_connect(SRY_DB_SERVER, $sryinfo);

?>
