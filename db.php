<?
// Input burnaby information
define("BBY_DB_SERVER", "111.111.111.111, 1111");
define("BBY_DB_USERID", "111");
define("BBY_PASSWORD", "111");
define("BBY_DB_NAME", "1111");

// Generate connection variable
$bbyinfo = array( "Database"=>BBY_DB_NAME, "UID"=>BBY_DB_USERID, "PWD"=>BBY_PASSWORD);
$conn_bby = sqlsrv_connect(BBY_DB_SERVER, $bbyinfo);

// $sel = mssql_select_db(BBY_DB_NAME, $conn_bby);
// $conn_sry = mssql_connect(SRY_DB_SERVER, SRY_DB_USERID, SRY_PASSWORD, SRY_DB_NAME);
// $sel2 = mssql_select_db(SRY_DB_NAME, $conn_sry);

?>
