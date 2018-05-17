<?
include_once("db.php");
include_once("db2.php");

require 'PHPmailer/PHPMailerAutoload.php';
// sqlsrv_select_db(BBY_DB_NAME, $conn_sry);

// sqlsrv_select_db(SRY_DB_NAME, $conn_sry);

$itemContainer = $_POST['itemdata'];
$itemContainer =  json_decode(stripslashes($itemContainer),true);
$formdata = unserializeForm($_POST['formdata']);
$exp_date = $formdata['expiry-month'].$formdata['expiry-year'];
$email = $formdata['email'];
$memberid = $formdata['memberid'];
$conv_pst = number_format((float)$_POST['pst'], 2, '.', '');
$conv_gst = number_format((float)$_POST['gst'], 2, '.', '');


  function sendmail($email,$file_attach,$itemContainer){
    global $conn_bby;
    $mail = new PHPMailer;
    $today = date('Y-m-d');
    $mail->IsSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.hannamsm.com';  // smtp 주소
    $mail->SMTPAuth = false;                               // Enable SMTP authentication
    $mail->Username = 'confidential';                            // SMTP username 메일주소
    $mail->Password = 'confidential';                           // SMTP password 메일 접속 시 비밀번호
    $mail->Port = 25;

    $mail->CharSet = "euc-kr";
    $mail->Encoding = "base64";

    $mail->From = 'hannamdev@hannamsm.com';                 //보내는 사람 메일 주소
    $mail->FromName = 'HANNAM SUPERMARKET';
    // $mail->Sender = $email;                           //보내는 사람 이름
    $mail->AddAddress($email);  // Add a recipient 받는 사람 주소, 받는 사람 이름
    $mail->AddReplyTo( 'hannamdev@hannamsm.com', 'Hannam Supermarket' );

    $mail->WordWrap = 50; // Set word wrap to 50 characters
    for($i = 0;$i < sizeof($file_attach);$i++){
      $mail->AddAttachment($file_attach[$i], 'Giftcon_'.$today.'_'.$i.'.jpg');
    }
    //     // Optional name  첨부 파일과 첨부파일 명
    // $mail->AddAttachment('codepic/kwyes2@hotmail.com915000000317.jpg', 'new.jpg');    // Optional name  첨부 파일과 첨부파일 명

    $mail->IsHTML(true);                                  // Set email format to HTML  메일을 보낼 때 html 형식으로 메일을 보낼 경우 true
    $body = "Thanks For Shopping In Hannam Supermarket Online.";
    $header = "<style>
                .footer{
                  width:100%;
                  height:500px;
                  background-color:black;
                }
                div.img {
                  margin: 5px;
                  border: 1px solid #ccc;
                  float: left;
                  width: 232px;
                }
                div.img:hover {
                  border: 1px solid #777;
                }
                div.img img {
                  width: 100%;
                  height: auto;
                }
                div.name {
                  text-align: center;
                  border-top: 1px solid #ccc;
                }
                div.price {
                  text-align: right;
                  border-top: 1px solid #ccc;
                }
                div.quantity {
                  text-align: right;
                  border-top: 1px solid #ccc;
                }
                h3{
                  font-family: inherit;
                  font-weight: 500;
                  line-height: 1.1;
                  color: inherit;
                  margin-top: 20px;
                  margin-bottom: 10px;
                  font-size: 24px;
                }
                h6{
                  color:rgba(255, 255, 255, .7);
                }
              .ft{
                  list-style: none;
                  padding-left: 0;
                  margin-bottom:14px;
                  flaot:left;
                }
                .ft .ftli{
                  color:rgba(255, 255, 255, .7);
                  margin-bottom:5px;

                }
                .ft .ftli a{
                  text-decoration: none;
                  color:rgba(255, 255, 255, .7);
                }
                .ft .ftmli{
                  letter-spacing: .15em;
                  margin-bottom: 20px !important;
                }
                .lli{
                  margin-left: 10px;
                }
                .clear{
                  clear: left;
                  display: block;
                }
              </style>";
    $table_content = '';
    $table_header = "<h3>Item Detail</h3><br />";
    for($i = 0; $i<sizeof($itemContainer); $i++) {
      $itemcode = $itemContainer[$i]['code'];
      $mail->AddEmbeddedImage("productImage/$itemcode.png", "$itemcode");
      $mail->AddEmbeddedImage("img/mail_footer.gif", "mail_footer");

      $query = "SELECT prodId, prodName, prodKname, prodType, prodTax, prodOUprice, prodPromo, CONVERT(char(10), promoEdate, 126) AS promoEdate, promoPrice, memberprice1, CONVERT(char(10), memberEdate, 126) AS memberEdate, MemberLimitQty FROM mfProd WHERE webview != 'NULL' AND prodId = '$itemcode'";
      $query_result = sqlsrv_query($conn_bby, $query);
      $row = sqlsrv_fetch_array($query_result);

      $promoEdate = $row['promoEdate'];
      $memberEdate = $row['memberEdate'];
      $today = date("Y-m-d");
      if($promoEdate >= $today){
        $realprice = $row['promoPrice'];
      }
      else{
        if($memberEdate >= $today){
          $realprice = $row['memberprice1'];
        } else{
          $realprice = $row['prodOUprice'];
        }
      }

      $prodName = $row['prodName'];
      $quantity = $itemContainer[$i]['quantity'];
      $table_content .= "<div class='img'>
                          <img src='cid:$itemcode' />
                          <div class='name'>$prodName</div>
                          <div class='price'>Price&nbsp;:&nbsp;$<span style='color:red;font-weight:bold;'>$realprice</span></div>
                          <div class='quantity'>Quantity&nbsp;:&nbsp;<span style='color:red;font-weight:bold;'>$quantity</span></div>
                        </div>";
    }
    $clear = "<span class='clear'></span>";
    $footer = "<img src='cid:mail_footer' />";
    // $footer = "<div>test</div>";
    $finalbody = $header.$body."<br />".$table_header.$table_content.$clear.$footer;


    $mail->Subject = "Hannam Supermarket Giftcon";                //메일 주소
    $mail->Body    = $finalbody;  //메일 본문


    // $mail->AltBody = $body; // 메일 본문을 오로지 텍스트 형식으로만 보낼 때

    if(!$mail->Send()) {
       echo 'Message could not be sent.';
       echo 'Contact IT DepartMent Please';         // 메일 전송에 실패 했을 경우 뜨는 에러 메세지
       exit;
    } else {
      echo 'Success';
    }
  }

  function cal_Totalprice($itemContainer){
    global $conn_bby;
    $Totalprice = 0;
    for($i = 0; $i<sizeof($itemContainer); $i++) {
      for($j = 0; $j < $itemContainer[$i]["quantity"]; $j++){
        $itemcode = $itemContainer[$i]['code'];
        $query = "SELECT prodId, prodName, prodKname, prodType, prodTax, prodOUprice, prodPromo, CONVERT(char(10), promoEdate, 126) AS promoEdate, promoPrice, memberprice1, CONVERT(char(10), memberEdate, 126) AS memberEdate, MemberLimitQty FROM mfProd WHERE webview != 'NULL' AND prodId = '$itemcode'";
        $query_result = sqlsrv_query($conn_bby, $query);
        $row = sqlsrv_fetch_array($query_result);

        $promoEdate = $row['promoEdate'];
        $memberEdate = $row['memberEdate'];
        $today = date("Y-m-d");
        if($promoEdate >= $today){
          $realprice = $row['promoPrice'];
        }
        else{
          if($memberEdate >= $today){
            $realprice = $row['memberprice1'];
          } else{
            $realprice = $row['prodOUprice'];
          }
        }

        if($row['prodTax'] == 'B'){
          $price = $realprice * 1.12;
        } elseif ($row['prodTax'] == 'G') {
          $price = $realprice * 1.05;
        } else {
          $price = $realprice;
        }
        $Totalprice += $price;
      }
    }
    return $Totalprice;
  }

function parseAsciiResponse($ascii_string){
    $data = array();
    $lines = explode("\n", $ascii_string);
    if (count($lines)) {
        foreach ($lines as $line) {
            $kvp = explode('=', $line);
            $data[$kvp[0]] = $kvp[1];
        }
    }
    return $data;
}

function unserializeForm($str) {
    $returndata = array();
    $strArray = explode("&", $str);
    $i = 0;
    foreach ($strArray as $item) {
        $array = explode("=", $item);
        $returndata[$array[0]] = $array[1];
    }

    return $returndata;
}

function barcodeGenerator($intid,$itemContainer,$email,$memberid){
 global $conn_bby;
 global $conn_sry;
 $file_attach = array();
 $current_year = date('y');
 $barcodeLink = 'http://barcodes4.me/barcode/';
 $type = 'i2of5';
 $firstvalue = "915";
 $lastvalue = $current_year;
 $likequery = "_______";
 $likewhere = "'".$firstvalue.$likequery.$lastvalue."'";
 $imagetype = '.jpg';
 $size = '?width=400&height=200';
 $text = "&IsTextDrawn=1";

 $sdate = date('Y-m-d');
 $edate = date('Y-m-d', strtotime($sdate."+90 day"));

  for($i = 0; $i<sizeof($itemContainer); $i++) {
    for($j = 0; $j < $itemContainer[$i]["quantity"]; $j++){

      $value_query = "SELECT TOP 1 EventNo, EventCouponNo FROM tfEventCoupon WHERE EventCouponNo like $likewhere ORDER BY EventCouponNo DESC";
      $value_query_result = sqlsrv_query($conn_bby, $value_query);
      $value_row = sqlsrv_fetch_array($value_query_result);

      $midvalue = substr($value_row['EventCouponNo'],3,7);
      $eventno = $value_row['EventNo'];
      $eventno = $eventno + 1;
      $midvalue = str_pad($midvalue + 1, 7, 0, STR_PAD_LEFT);

      $finalvalue = $firstvalue.$midvalue.$lastvalue;
      $final = $barcodeLink.$type."/".$finalvalue.$imagetype.$size.$text;
      $itemcode = $itemContainer[$i]['code'];


      $price_query = "SELECT prodId, prodName, prodKname, prodType, prodTax, prodOUprice, prodPromo, CONVERT(char(10), promoEdate, 126) AS promoEdate, promoPrice, memberprice1, CONVERT(char(10), memberEdate, 126) AS memberEdate, MemberLimitQty FROM mfProd WHERE prodId = '$itemcode'";
      $price_query_result = sqlsrv_query($conn_bby, $price_query);
      $price_row = sqlsrv_fetch_array($price_query_result);

      // $event_price = $price_row['price'];
      $promoEdate = $price_row['promoEdate'];
      $memberEdate = $price_row['memberEdate'];
      $today = date("Y-m-d");
      if($promoEdate >= $today){
        $event_price = $price_row['promoPrice'];
      }
      else{
        if($memberEdate >= $today){
          $event_price = $price_row['memberprice1'];
        } else{
          $event_price = $price_row['prodOUprice'];
        }
      }
      $event_price = $event_price * -1;

      $event_tax = $price_row['prodTax'];
      if($event_tax == 'N'){
        $GST = 0;
        $PST = 0;
      } elseif ($event_tax == 'B') {
        $GST = $event_price * 0.05;
        $PST = $event_price * 0.07;
      } else{
        $GST = $event_price * 0.05;
        $PST = 0;
      }
      $GST = number_format((float)$GST, 2, '.', '');
      $PST = number_format((float)$PST, 2, '.', '');

      $query = "INSERT INTO tfEventCoupon(EventNo, EventCouponNo, ProdId, dcPrice, dcGST, dcPST, startDate, endDate) Values('$intid', '$finalvalue','$itemcode','$event_price','$GST','$PST','$sdate','$edate') ";
      $insert_query_result = sqlsrv_query($conn_bby, $query);

      // echo $query;

      $query2 = "INSERT INTO tfEventCoupon(EventNo, EventCouponNo, ProdId, dcPrice, dcGST, dcPST, startDate, endDate) Values('$intid', '$finalvalue','$itemcode','$event_price','$GST','$PST','$sdate','$edate') ";
      $query_result2 = sqlsrv_query($conn_sry, $query2);



      $filename = 'codepic/'.$email.$finalvalue.'.jpg';
      $filename2 = 'code_email_pic/'.$email.$finalvalue.'.jpg';
      copy($final, $filename);

      $dest = imagecreatefromjpeg('codepic/main/'.$itemcode.'.jpg');
      $src = imagecreatefromjpeg($filename);

      // Copy and merge
      imagecopymerge($dest, $src, 58, 530, 50, 100, 300, 100, 100);
      imagejpeg($dest, $filename2);
      imagedestroy($dest);
      imagedestroy($src);
      array_push($file_attach,$filename2);
    }
  }
  sendmail($email,$file_attach,$itemContainer);
}

function saveCardInfo($cardno, $membership, $email, $intdate, $inttime, $gst, $pst, $amount){
  global $conn_bby;
  global $conn_sry;

  $intquery = "SELECT IntID FROM db1gal.dbo.tfTranINT Order BY IntID DESC";
  $intquery_result = sqlsrv_query($conn_bby, $intquery, array(), array("scrollable" => 'keyset'));
  $row_num = sqlsrv_num_rows($intquery_result);
  $intquery_row = sqlsrv_fetch_array($intquery_result);
  if($row_num == 0){
    $intid = '100';
  } else{
    $intid = $intquery_row['IntID'] + 1;
  }
  $currenttime = date("h:i:s");
  $query = "INSERT INTO db1gal.dbo.tfTranINT(IntID,IntCCardNo,IntMemCardNo,IntEmailAddr,IntDate,IntTime,IntGST,IntPST,IntPaidAmount) VALUES('$intid','$cardno', '$membership', '$email', '$intdate', '$currenttime', '$gst', '$pst', '$amount')";
  // $query .= "VALUES('$intid','$cardno', '$membership', '$email', '$intdate', '$inttime', '$gst', '$pst', '$amount')";
  $query_result = sqlsrv_query($conn_bby, $query);
  //
  $query2 = "INSERT INTO db1gal.dbo.tfTranINT(IntID,IntCCardNo,IntMemCardNo,IntEmailAddr,IntDate,IntTime,IntGST,IntPST,IntPaidAmount) VALUES('$intid','$cardno', '$membership', '$email', '$intdate', '$currenttime', '$gst', '$pst', '$amount')";
  // // $query2 .= "VALUES('$intid','$cardno', '$membership', '$email', '$intdate', '$inttime', '$gst', '$pst', '$amount')";
  $query_result2 = sqlsrv_query($conn_sry, $query2);

  return $intid;
}

$Totalprice = cal_Totalprice($itemContainer);

// barcodeGenerator($itemContainer);


//Uncomment the endpoint desired.
//Production URL
//Demo URL
$url ='https://www.myvirtualmerchant.com/VirtualMerchant/process.do';
//Configuration parameters.
$ssl_merchant_id = '111111';
$ssl_user_id = 'admin';
$ssl_pin = 'CONFIDENTIAL';
$ssl_show_form = 'false';
$ssl_result_format = 'ascii';
$ssl_error_url = 'error.php';
$ssl_transaction_type = 'CCSALE';
$ssl_card_number = $formdata['card-number'];
$ssl_exp_date = $exp_date;
$ssl_amount = $Totalprice;
$CardholderName = $formdata['card-holder-name'];
$ssl_email = $formdata['email'];
$ssl_cvv2cvc2 = $formdata['cvv'];
$ssl_cvv2cvc2_indicator= "1";

//Declares base URL in the event that you are using the VM

//Dynamically builds POST request based on the information being
$queryString = "";
foreach($_REQUEST as $key => $value)
{
if($queryString != "")
{
$queryString .= "&";
}
$queryString .= $key . "=" . urlencode($value);
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString .
"&ssl_merchant_id=$ssl_merchant_id".
"&ssl_user_id=$ssl_user_id".
"&ssl_pin=$ssl_pin".
"&ssl_cvv2cvc2=$ssl_cvv2cvc2".
"&ssl_transaction_type=$ssl_transaction_type".
"&CardholderName=$CardholderName".
"&ssl_result_format=$ssl_result_format".
"&ssl_show_form=$ssl_show_form".
"&ssl_card_number=$ssl_card_number".
"&ssl_exp_date=$ssl_exp_date".
"&ssl_cvv2cvc2_indicator=$ssl_cvv2cvc2_indicator".
"&ssl_email=$ssl_email".
"&ssl_amount=$ssl_amount");

// echo "<html><head><base href='" . $url . "'></base></head>";

$result = curl_exec($ch);
curl_close($ch);
$result = parseAsciiResponse($result);
$chkResult = $result['ssl_result'];

if(isset($result['errorCode'])){
  echo "Invaild";
} else{
  if($chkResult == 0){
    $intid = saveCardInfo($result['ssl_card_number'],$memberid,$email,$result['ssl_txn_time'],$result['ssl_txn_time'],$conv_gst,$conv_pst,$result['ssl_amount']);
    barcodeGenerator($intid, $itemContainer,$email,$memberid);
  } else {
    // print_r($result);
    echo "Decline";
  }
}

// $intid = saveCardInfo($result['ssl_card_number'],$memberid,$email,$result['ssl_txn_time'],$result['ssl_txn_time'],$conv_gst,$conv_pst,$result['ssl_amount']);
// // echo $intid;
// barcodeGenerator($intid, $itemContainer,$email,$memberid);


?>
