<?
error_reporting(E_ALL ^ E_NOTICE);

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
