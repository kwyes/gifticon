<!DOCTYPE html>
<html>
	<head>
		<title>Shopping Cart</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/custom.css?ver=1"/>
		<script src="js/jquery.js"></script>
	</head>
	<style>
	.loader {
			display: none;
			background: #f4f4f2 repeat scroll 0 0;
			height: 100%;
			left: 0;
			margin: auto;
			position: fixed;
			top: 0;
			width: 100%;
		}
		.bokeh {
			border: 0.01em solid rgba(150, 150, 150, 0.1);
			border-radius: 50%;
			font-size: 100px;
			height: 1em;
			list-style: outside none none;
			margin: 0 auto;
			position: relative;
			top: 35%;
			width: 1em;
			z-index: 2147483647;
		}
		.bokeh li {
			border-radius: 50%;
			height: 0.2em;
			position: absolute;
			width: 0.2em;
		}
		.bokeh li:nth-child(1) {
			animation: 1.13s linear 0s normal none infinite running rota, 3.67s ease-in-out 0s alternate none infinite running opa;
			background: #00c176 none repeat scroll 0 0;
			left: 50%;
			margin: 0 0 0 -0.1em;
			top: 0;
			transform-origin: 50% 250% 0;
		}
		.bokeh li:nth-child(2) {
			animation: 1.86s linear 0s normal none infinite running rota, 4.29s ease-in-out 0s alternate none infinite running opa;
			background: #ff003c none repeat scroll 0 0;
			margin: -0.1em 0 0;
			right: 0;
			top: 50%;
			transform-origin: -150% 50% 0;
		}
		.bokeh li:nth-child(3) {
			animation: 1.45s linear 0s normal none infinite running rota, 5.12s ease-in-out 0s alternate none infinite running opa;
			background: #fabe28 none repeat scroll 0 0;
			bottom: 0;
			left: 50%;
			margin: 0 0 0 -0.1em;
			transform-origin: 50% -150% 0;
		}
		.bokeh li:nth-child(4) {
			animation: 1.72s linear 0s normal none infinite running rota, 5.25s ease-in-out 0s alternate none infinite running opa;
			background: #88c100 none repeat scroll 0 0;
			margin: -0.1em 0 0;
			top: 50%;
			transform-origin: 250% 50% 0;
		}
		@keyframes opa {
		12% {
			opacity: 0.8;
		}
		19.5% {
			opacity: 0.88;
		}
		37.2% {
			opacity: 0.64;
		}
		40.5% {
			opacity: 0.52;
		}
		52.7% {
			opacity: 0.69;
		}
		60.2% {
			opacity: 0.6;
		}
		66.6% {
			opacity: 0.52;
		}
		70% {
			opacity: 0.63;
		}
		79.9% {
			opacity: 0.6;
		}
		84.2% {
			opacity: 0.75;
		}
		91% {
			opacity: 0.87;
		}
		}

		@keyframes rota {
		100% {
			transform: rotate(360deg);
		}
		}

		}
	</style>

	<body>
		<div class="content_wrapper">
		<nav class="navbar">
			<div class="container">
				<a class="navbar-brand" href="/gifticon">Hannam Supermarket</a>
				<div class="navbar-right">
					<div class="container minicart"></div>
				</div>
			</div>
		</nav>

		<div class="container-fluid breadcrumbBox text-center">
			<ol class="breadcrumb">
				<li><a href="#">Review</a></li>
				<li class="active"><a href="#">Order</a></li>
				<li><a href="#">Payment</a></li>
			</ol>
		</div>

		<div class="container text-center">

			<div class="col-md-5 col-sm-12">
				<div class="bigcart"></div>
				<h1>Your Shopping Cart</h1>
				<p>

				</p>
			</div>

			<div class="col-md-7 col-sm-12 text-left">
        <ul>
          <li class="row custom_row list-inline columnCaptions">
						<span>QTY</span>
						<span>ITEM</span>
						<span>SubTotal Price</span>
					</li>
        <?
          $num_item = $_REQUEST['itemCount'];
          for ($i = 1; $i <= $num_item; $i++) {
            $item_name = $_REQUEST['item_name_'.$i];
            $item_code = $_REQUEST['item_options_'.$i];
            $item_quantity = $_REQUEST['item_quantity_'.$i];
						$item_price = $_REQUEST['item_price_'.$i];
            // echo "The Item Name is: $item_name || $item_code <br>";
						$subtotal_price = $item_quantity * $item_price;
						$subtotal_price = number_format((float)$subtotal_price, 2, '.', '');
						$item_tax = substr($item_code, -1, 1);
						if($item_tax == 'N'){
							$GST = 0;
							$PST = 0;
						} elseif ($item_tax == 'B') {
							$GST = 0.05;
							$PST = 0.07;
						} else{
							$GST = 0.05;
							$PST = 0;
						}

						$GST_PRICE += $subtotal_price * $GST;
						$PST_PRICE += $subtotal_price * $PST;



            $item_real_code = filter_var($item_code, FILTER_SANITIZE_NUMBER_INT);
						$itemContainer[] = array("code"=>$item_real_code,"quantity"=>$item_quantity);

        ?>
          <li class="row custom_row">
						<span class="quantity"><?=$item_quantity?></span>
						<span class="itemName"><?=$item_name?></span>
						<span class="popbtn"><a class="arrow"></a></span>
						<span class="price"><?=$subtotal_price?></span>
					</li>
        <? }  ?>
				<li class="row custom_row">
					<span class="quantity">PST</span>
					<span class="itemName"></span>
					<span class="popbtn"></span>
					<span class="price"><?=number_format((float)$PST_PRICE, 2, '.', '');?></span>
				</li>
				<li class="row custom_row">
					<span class="quantity">GST</span>
					<span class="itemName"></span>
					<span class="popbtn"></span>
					<span class="price"><?=number_format((float)$GST_PRICE, 2, '.', '');?></span>
				</li>
        </ul>
			</div>
		</div>
<div class="container text-center"><div class="card-wrapper"></div></div>
<div class="container text-center">
  <form class="form-horizontal" id="form" role="form" action="?page=payment">
    <fieldset class="payment_form">
      <legend style="text-align:left;">Payment</legend>
			<div class="form-group">
        <label class="col-sm-6 control-label" for="email">Email</label>
        <div class="col-sm-6">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
        </div>
      </div>
			<div class="form-group">
        <label class="col-sm-6 control-label" for="memberid">Membership</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="memberid" id="memberid" maxlength="12" placeholder="Membership Number(Optional)">
        </div>
      </div>
			<div class="form-group">
				<label class="col-sm-6 control-label" for="card-number">Card Number</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" maxlength="22" name="card-number" id="card-number" placeholder="Debit/Credit Card Number">
				</div>
			</div>
      <div class="form-group">
        <label class="col-sm-6 control-label" for="card-holder-name">Name on Card</label>
        <div class="col-sm-6">
          <input type="text" style="text-transform:uppercase" class="form-control" name="card-holder-name" id="card-holder-name" placeholder="Card Holder's Name">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-6 control-label" for="expiry-month">Expiration Date</label>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-xs-6">
              <select class="form-control col-sm-2" name="expiry-month" id="expiry-month" data-source="exp_month">
                <option>Month</option>
                <option value="01">Jan (01)</option>
                <option value="02">Feb (02)</option>
                <option value="03">Mar (03)</option>
                <option value="04">Apr (04)</option>
                <option value="05">May (05)</option>
                <option value="06">June (06)</option>
                <option value="07">July (07)</option>
                <option value="08">Aug (08)</option>
                <option value="09">Sep (09)</option>
                <option value="10">Oct (10)</option>
                <option value="11">Nov (11)</option>
                <option value="12">Dec (12)</option>
              </select>

            </div>
            <div class="col-xs-6">
              <select class="form-control" name="expiry-year" id="expiry-year" data="exp_year">
                <option value="17">2017</option>
                <option value="18">2018</option>
                <option value="19">2019</option>
                <option value="20">2020</option>
                <option value="21">2021</option>
                <option value="22">2022</option>
                <option value="23">2023</option>
								<option value="24">2024</option>
                <option value="25">2025</option>
                <option value="26">2026</option>
								<option value="27">2027</option>
                <option value="28">2028</option>
                <option value="29">2029</option>
              </select>
							<input type="text" name="realexpiry" id="realexpiry" style="display:none;">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-6 control-label" for="cvv">Card CVV</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-6 col-sm-6">
          <!-- <button onclick="pay();" type="button" class="btn btn-success">Pay Now</button> -->
					<button onclick="pay();" type="button" class="btn btn-success">Pay Now</button>
					<!-- <button type="submit" class="btn btn-success">Pay Now</button> -->
        </div>
      </div>
    </fieldset>
  </form>
</div>
</div>
<div class="loader">
	<ul class="bokeh">
									 <li></li>
									 <li></li>
									 <li></li>
							 </ul>
</div>
<!-- <div id="test"></div> -->
<script src="js/card.js"></script>
<script>
	new Card({
			form: document.querySelector('form'),
			container: '.card-wrapper',
			formSelectors: {
			 numberInput: 'input#card-number',
			 expiryInput: 'select[name="expiry-month"]',
			 cvcInput: 'input#cvv',
			 nameInput: 'input#card-holder-name'
	 }
	});

$(document).ready(function(){
	year_selector = 'select#expiry-year';
	month_selector = 'select#expiry-month';
		$(month_selector).change(function(){
			year = $(year_selector).val() == '' ? '••' : $(year_selector).val();
			$('.jp-card-expiry').text($(this).val()+'/'+year);
		});
		$(year_selector).change(function(){
			month = $(month_selector).val() == '' ? '••': $(month_selector).val();
			$('.jp-card-expiry').text(month+'/'+$(this).val());
		});
		$(month_selector).add(year_selector).on('focus', function(){
			$('.jp-card-expiry').addClass('jp-card-focused');
		}).on('blur', function(){
			$('.jp-card-expiry').removeClass('jp-card-focused');
		});
});

</script>
<script>
	function expirychg(){
		var x = $("#expiry-month").val();
		$("#realexpiry").html(x);
	}

	function expirychg2(){
		var x = $("#expiry-year").val();
		$("#realexpiry").html(x);
	}

	function pay(){
		var other_data = $('form').serialize();
		var form_check = true;
		other_data = decodeURIComponent(other_data);
		var arr = '<? echo json_encode($itemContainer); ?>'
		var pst = '<?=$PST_PRICE?>'
		var gst = '<?=$GST_PRICE?>'

		var email = $('#email').val();
		var name = $('#card-holder-name').val();
		var cardnumber = $('#card-number').val();
		var expdate = $('#expiry-month').val();
		var cvv = $('#cvv').val();

		if(email == ''){
			form_check = false;
		}
		if(name == ''){
			form_check = false;
		}
		if(cardnumber == ''){
			form_check = false;
		}
		if(expdate == ''){
			form_check = false;
		}
		if(cvv == ''){
			form_check = false;
		}
		if(!form_check){
			alert("Please Fill The Correct Information");
		} else {
			$.ajax({
	            url:'converge.php',
	            type:'POST',
	            data:{
								formdata : other_data,
								itemdata : arr,
								pst : pst,
								gst : gst
							},
							beforeSend: function()
					    {
				        $('.loader').show();
								$(".content_wrapper").fadeOut("slow");
					    },
	            success:function(data){
								if(data == 'Success'){
									location.href="?page=approved";
								}
								else{
									// $('#test').html(data);
									// alert(data);
									$('.loader').hide();
									$(".content_wrapper").fadeIn("slow");
									alert("Declined. Please Check Your Card Information.")
								}
	            }
	    })
		}
	}

</script>


		<!-- The popover content -->



		<!-- JavaScript includes -->
	</body>
</html>
