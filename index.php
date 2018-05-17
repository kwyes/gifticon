<?
error_reporting(E_ALL ^ E_NOTICE);

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
  $redirect = 'https://' . $surl . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);
    exit();
}

$page = $_REQUEST['page'];
  switch ($page) {
    case 'checkout':
      include("checkout.php");
      break;

    case 'payment':
      include("converge.php");
      break;

    case 'approved':
      include("payment.php");
      break;

    default:
      include("main.php");
      break;
  }
?>
