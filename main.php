<?
  include_once("db.php");
  $query = "SELECT prodId, prodName, prodKname, prodType, prodTax, prodOUprice, m.prodsize, prodPromo, CONVERT(char(10), promoEdate, 126) AS promoEdate, promoPrice, memberprice1, CONVERT(char(10), memberEdate, 126) AS memberEdate, MemberLimitQty, t.DeptID
 FROM mfProd as m LEFT JOIN tblGalProdMaster as t ON m.GalCode = t.GalCode AND m.ProdOwnCode = t.ProdOwnCode WHERE webview != 'NULL'
  ORDER BY t.DeptID ASC, prodType, prodName";
  // echo $query;
  $query_result = sqlsrv_query($conn_bby, $query);

  $query_type = "SELECT Distinct p.pName, p.pType FROM mfPtype as p right join mfProd as m on m.prodType = p.pType WHERE m.webView = '1'";
  $query_type_result = sqlsrv_query($conn_bby, $query_type);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Shop Homepage - Start Bootstrap Template</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/shop-homepage.css?ver=10" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/5a898b16eb.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/simpleCart.js?ver=5"></script>
</head>
<script>
function searchCategory(id){
  if(id == 'all'){
      $('.allofthem').css("display","block");
      exit;
  }
  $('.allofthem').css("display","none");
  $('.'+id).css("display","block");
}
$( document ).ready(function() {
    $('.btn-number').click(function(e){
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {
                var minValue = parseInt(input.attr('min'));
                if(!minValue) minValue = 0;
                if(currentVal > minValue) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == minValue) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {
                var maxValue = parseInt(input.attr('max'));
                if(!maxValue) maxValue = 9999999999999;
                if(currentVal < maxValue) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == maxValue) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function(){
       $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function() {

        var minValue =  parseInt($(this).attr('min'));
        var maxValue =  parseInt($(this).attr('max'));
        if(!minValue) minValue = 0;
        if(!maxValue) maxValue = 9999999999999;
        var valueCurrent = parseInt($(this).val());

        var name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }


    });
    $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
    });
});

simpleCart.ready(function(){
    // get the checkout button into var
    var button = $('.simpleCart_checkout');
    // hide the checkout button
    // get the cart quantity
    var quantity = simpleCart.quantity();
    var total = simpleCart.total();

    simpleCart.bind( 'beforeCheckout' , function(){
      var num = localStorage.getItem('simpleCart_items').length;
      if(num == 2){
        alert("There Is No Item In Your Cart");
        exit;
      }
    });
    simpleCart.bind('update', function(){
      if( simpleCart.quantity() === 0 ){
        $(".simpleCart_items").hide();
      } else {
        $(".simpleCart_items").show();
      }
    });
});

simpleCart({
  cartStyle: "table",
    checkout: {
        type: "SendForm" ,
        url: "?page=checkout" ,
        // http method for form, "POST" or "GET", default is "POST"
        method: "POST" ,
        // url to return to on successful checkout, default is null
        success: "success.html" ,
        // url to return to on cancelled checkout, default is null
        cancel: "cancel.html" ,
        // an option list of extra name/value pairs that can
        // be sent along with the checkout data
        extra_data: {
          storename: "Hannam Supermarket",
          cartid: "12321321"
        }
    }

});

$(document).ready(function () {
  var length = $('.simpleCart_table tbody tr').length;
  // alert(length);

});

// $(document).ready(function () {
//
//     $('#myModal').modal('show');
//
// });
</script>
<body>

  <div class="modal fade" id="policy" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Privacy Policy</h4>
        </div>
        <div class="modal-body">
          <div id="ppBody">
            <div style="clear:both;height:10px;"></div>
            <div style="clear:both;height:10px;"></div>
    <div class="innerText">This privacy policy has been compiled to better serve those who are concerned with how their 'Personally Identifiable Information' (PII) is being used online. PII, as described in Canada privacy law and information security, is information that can be used on its own or with other information to identify, contact, or locate a single person, or to identify an individual in context. Please read our privacy policy carefully to get a clear understanding of how we collect, use, protect or otherwise handle your Personally Identifiable Information in accordance with our website.<br></div><span id="infoCo"></span><br><div class="grayText"><strong>What personal information do we collect from the people that visit our blog, website or app?</strong></div><br><div class="innerText">When ordering or registering on our site, as appropriate, you may be asked to enter your name, email address, credit card information  or other details to help you with your experience.</div><br><div class="grayText"><strong>When do we collect information?</strong></div><br><div class="innerText">We collect information from you when you or enter information on our site.</div><br>pay for the item <span id="infoUs"></span><br><div class="grayText"><strong>How do we use your information? </strong></div><br><div class="innerText"> We may use the information we collect from you when you register, make a purchase, sign up for our newsletter, respond to a survey or marketing communication, surf the website, or use certain other site features in the following ways:<br><br></div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> To quickly process your transactions.</div><span id="infoPro"></span><br><div class="grayText"><strong>How do we protect your information?</strong></div><br><div class="innerText">Our website is scanned on a regular basis for security holes and known vulnerabilities in order to make your visit to our site as safe as possible.<br><br></div><div class="innerText">We use regular Malware Scanning.<br><br></div><div class="innerText">Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems, and are required to keep the information confidential. In addition, all sensitive/credit information you supply is encrypted via Secure Socket Layer (SSL) technology. </div><br><div class="innerText">We implement a variety of security measures when a user places an order  to maintain the safety of your personal information.</div><br><div class="innerText">All transactions are processed through a gateway provider and are not stored or processed on our servers.</div><span id="coUs"></span><br><div class="grayText"><strong>Do we use 'cookies'?</strong></div><br><div class="innerText">We do not use cookies for tracking purposes </div><div class="innerText"><br>You can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies. You do this through your browser settings. Since browser is a little different, look at your browser's Help Menu to learn the correct way to modify your cookies.<br></div><br><div class="innerText">If you turn cookies off, Some of the features that make your site experience more efficient may not function properly.that make your site experience more efficient and may not function properly.</div><br><span id="trDi"></span><br><div class="grayText"><strong>Third-party disclosure</strong></div><br><div class="innerText">We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information unless we provide users with advance notice. This does not include website hosting partners and other parties who assist us in operating our website, conducting our business, or serving our users, so long as those parties agree to keep this information confidential. We may also release information when it's release is appropriate to comply with the law, enforce our site policies, or protect ours or others' rights, property or safety. <br><br> However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses. </div><span id="trLi"></span><br><div class="grayText"><strong>Third-party links</strong></div><br><div class="innerText">We do not include or offer third-party products or services on our website.</div><span id="gooAd"></span><br><div class="blueText"><strong>Google</strong></div><br><div class="innerText">Google's advertising requirements can be summed up by Google's Advertising Principles. They are put in place to provide a positive experience for users. https://support.google.com/adwordspolicy/answer/1316548?hl=en <br><br></div><div class="innerText">We use Google AdSense Advertising on our website.</div><div class="innerText"><br>Google, as a third-party vendor, uses cookies to serve ads on our site. Google's use of the DART cookie enables it to serve ads to our users based on previous visits to our site and other sites on the Internet. Users may opt-out of the use of the DART cookie by visiting the Google Ad and Content Network privacy policy.<br></div><div class="innerText"><br><strong>We have implemented the following:</strong></div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Remarketing with Google AdSense</div><br><div class="innerText">We, along with third-party vendors such as Google use first-party cookies (such as the Google Analytics cookies) and third-party cookies (such as the DoubleClick cookie) or other third-party identifiers together to compile data regarding user interactions with ad impressions and other ad service functions as they relate to our website. </div><div class="innerText"><br><strong>Opting out:</strong><br>
    					Users can set preferences for how Google advertises to you using the Google Ad Settings page. Alternatively, you can opt out by visiting the Network Advertising Initiative Opt Out page or by using the Google Analytics Opt Out Browser add on.</div><span id="coppAct"></span><br><div class="blueText"><strong>COPPA (Children Online Privacy Protection Act)</strong></div><br><div class="innerText">When it comes to the collection of personal information from children under the age of 13 years old, the Children's Online Privacy Protection Act (COPPA) puts parents in control.  The Federal Trade Commission, United States' consumer protection agency, enforces the COPPA Rule, which spells out what operators of websites and online services must do to protect children's privacy and safety online.<br><br></div><div class="innerText">We do not specifically market to children under the age of 13 years old.</div><span id="ftcFip"></span><br><div class="blueText"><strong>Fair Information Practices</strong></div><br><div class="innerText">The Fair Information Practices Principles form the backbone of privacy law in the United States and the concepts they include have played a significant role in the development of data protection laws around the globe. Understanding the Fair Information Practice Principles and how they should be implemented is critical to comply with the various privacy laws that protect personal information.<br><br></div><div class="innerText"><strong>In order to be in line with Fair Information Practices we will take the following responsive action, should a data breach occur:</strong></div><div class="innerText">We will notify the users via in-site notification</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Within 7 business days</div><div class="innerText"><br>We also agree to the Individual Redress Principle which requires that individuals have the right to legally pursue enforceable rights against data collectors and processors who fail to adhere to the law. This principle requires not only that individuals have enforceable rights against data users, but also that individuals have recourse to courts or government agencies to investigate and/or prosecute non-compliance by data processors.</div><span id="canSpam"></span><br><div class="blueText"><strong>CAN SPAM Act</strong></div><br><div class="innerText">The CAN-SPAM Act is a law that sets the rules for commercial email, establishes requirements for commercial messages, gives recipients the right to have emails stopped from being sent to them, and spells out tough penalties for violations.<br><br></div><div class="innerText"><strong>We collect your email address in order to:</strong></div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Process orders and to send information and updates pertaining to orders.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Send you additional information related to your product and/or service</div><div class="innerText"><br><strong>To be in accordance with CANSPAM, we agree to the following:</strong></div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Not use false or misleading subjects or email addresses.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Identify the message as an advertisement in some reasonable way.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Include the physical address of our business or site headquarters.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Monitor third-party email marketing services for compliance, if one is used.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Honor opt-out/unsubscribe requests quickly.</div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Allow users to unsubscribe by using the link at the bottom of each email.</div><div class="innerText"><strong><br>If at any time you would like to unsubscribe from receiving future emails, you can email us at</strong></div><div class="innerText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>•</strong> Follow the instructions at the bottom of each email.</div> and we will promptly remove you from <strong>ALL</strong> correspondence.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>


  <!--- For only short term -->

  <div class="modal fade" id="manual" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Giftcon Manual</h4>
        </div>
        <div class="modal-body">
          <img src="img/manual2.png" alt="" width="100%">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>


  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" href="/gifticon">Hannam Supermarket</h4>
        </div>
        <div class="modal-body">
          <h3>
            현재 이 페이지는 테스트중입니다. <br />
            조만간 오픈 예정입니다. 오픈후 이용해주세요.<br /><br /><br />
            This Website Is Currently Under Maintenance. Please Come Back Later.<br />
          </h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <!--- For only short term -->

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button> -->
                <a class="navbar-brand" href="#">HANNAM SUPERMARKET</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div> -->
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="list-group">
                <a onclick="searchCategory('all')" class="list-group-item">ALL</a>
                <? while($tRow = sqlsrv_fetch_array($query_type_result)){ ?>
                  <a onclick="searchCategory('<?=$tRow['pType']?>')" data-toggle="tab" class="list-group-item"><?=iconv('EUC-KR','UTF-8',$tRow['pName'])?></a>
                <? } ?>
            </div>
            <h3><i class="fa fa-shopping-cart" aria-hidden="true"></i> Shopping Cart</h3>
            <table class="table">
              <tr><td colspan="2">Total : <div style="display:inline;" class="simpleCart_total"></div></td></tr>
              <tr>
                <td width="50%">
                  <button style="width:100%;" class="btn btn-danger simpleCart_checkout">Check Out</button>
                </td>
                <td>
                  <button style="width:100%;" class="btn btn-danger simpleCart_empty">Empty</button>
                </td>
              </tr>
              <tr>
                <td colspan="2"><button style="width:100%;" data-toggle="modal" data-target="#manual" class="btn btn-primary btn_manual">Manual</button></td>
              </tr>
              <tr>
                <td colspan="2"><button style="width:100%;" data-toggle="modal" data-target="#policy" class="btn btn-danger">Policy</button></td>
              </tr>
            </table>
          </div>
          <div class="col-md-9">
            <div class="simpleCart_items"></div>
            <div class="row">

        <?
            $i = 0;
            while($row = sqlsrv_fetch_array($query_result)){
            $imageFile = "productImage/".$row['prodId'].".png";
            $promoEdate = $row['promoEdate'];
            $memberEdate = $row['memberEdate'];
            $today = date("Y-m-d");
            if($promoEdate >= $today){
              $price = $row['promoPrice'];
            }
            else{
              if($memberEdate >= $today){
                $price = $row['memberprice1'];
              } else{
                $price = $row['prodOUprice'];
              }
            }
            $price_mod = number_format((float)$price, 2, '.', '');
            $price_mod_ar = explode(".", $price_mod);
            $prodsize = '';
            $size = iconv('euc-kr','utf-8',$row['prodsize']);
            $remove_space_size = str_replace(' ', '', $size);
            if($remove_space_size !== ''){
              $prodsize = " / ".$size;
            } else{
              $prodsize = '';
            }
            $reg_price = "<div class='pricing-plan-price'>Reg. <span class='line-through'><sup>$</sup>".$price_mod_ar[0].".<span class='price-text'>".$price_mod_ar[1]."</span></span></div>";
          ?>
                <div id="<?=$row['prodType']?>" class="allofthem <?=$row['prodType']?>">
                  <div class="col-sm-4 col-lg-4 col-md-4">
                      <div class="thumbnail simpleCart_shelfItem">
                        <div class="img-price">
                          <img src="<?=$imageFile?>" alt="">
                          <div class="position-right">
                            <div class="price-tag">
                              $<?=number_format((float)$price, 2, '.', '')?>
                            </div>
                              <!-- <sup>$</sup><?=$price_mod_ar[0]?><span>.<?=$price_mod_ar[1]?></span> -->
                          </div>
                          <div class="position-right-bottom">
                            <?=$reg_price?>
                          </div>
                          <div class="item_price" style="display:none;"><?=number_format((float)$price, 2, '.', '')?></div>
                        </div>
                          <div class="caption">
                              <h5 class="item_name"><a href="#"><?=$row['prodName']?></a></h5>
                              <input type="text" class="item_code" style="display:none;" value="<?=$row['prodId']?>::<?=$row['prodTax']?>">
                              <!-- <h4 class="pull-right item_price">$<?=number_format((float)$price, 2, '.', '')?></h4> -->
                              <!-- <div class="pull-right pricing-plan-price">
                                  <sup>$</sup>0<span>.00</span>
                              </div> -->
                              <p><?=iconv('euc-kr','utf-8',$row['prodKname'])?><?=$prodsize?></p>
                          </div>

                          <div class="input-group" style="width: 70%;margin: 0 auto;">
                              <span class="input-group-btn">
                                  <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant[<?=$i?>]">
                                      <span class="glyphicon glyphicon-minus"></span>
                                  </button>
                              </span>
                              <input type="text" name="quant[<?=$i?>]" class="form-control input-number item_Quantity" value="1" min="1" max="10">
                              <span class="input-group-btn">
                                  <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[<?=$i?>]">
                                      <span class="glyphicon glyphicon-plus"></span>
                                  </button>
                              </span>
                          </div>
                          <div class="text-center"><a href="javascript:;" class="item_add"> Add to Cart </a></div>

                          <div class="ratings">
                              <!-- <p class="pull-right">15 reviews</p>
                              <p>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                              </p> -->
                              <p>&nbsp;</p>
                          </div>
                      </div>
                  </div>
                </div>
        <? $i++;     } ?>
            </div>
          </div>
        </div>
    </div>
</body>
</html>
<?
  sqlsrv_close($conn_bby);
?>




  <!--?

  ?-->
