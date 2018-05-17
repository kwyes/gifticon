<!DOCTYPE html>
<html>
	<head>
		<title>Shopping Cart</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/custom.css?ver=1"/>
		<script src="js/jquery.js"></script>
		<script src="js/customjs.js"></script>
	</head>
	<script>
	function success(){
		var i = localStorage.length, key;
		while (i--) {
			key = localStorage.key(i);
			if (key.slice(0, 16) == "simpleCart_items") {
				// alert('This is the one');
				// localStorage.clear(); // works also
				localStorage.removeItem(key);
			}
		}
		location.href='/';
	}
	</script>
	<body>
		<nav class="navbar">
			<div class="container">
				<a class="navbar-brand" href="/">Hannam Supermarket</a>
				<div class="navbar-right">
					<div class="container minicart"></div>
				</div>
			</div>
		</nav>

		<div class="container-fluid breadcrumbBox text-center">
			<ol class="breadcrumb">
				<li><a href="#">Review</a></li>
				<li><a href="#">Order</a></li>
				<li class="active"><a href="#">Payment</a></li>
			</ol>
		</div>

		<div class="container text-center">

			<div class="col-md-5 col-sm-12">
				<div class="bigcart"></div>
				<h1>ORDER COMPLETE</h1>
				<p>

				</p>
			</div>

			<div class="col-md-7 col-sm-12 text-left">
				<h1>Payment Successful</h1>
				<h3>Your payment has been processed!<br /> Check the email inbox for giftcon you purchase</h3>
				<br />
				<button class="btn btn-primary" onclick="success();">CLOSE</button>
			</div>
		</div>
	</body>
</html>
