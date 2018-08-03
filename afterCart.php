<!DOCTYPE html>
<html>
<head>
	<title>order</title>
	<link rel="stylesheet" type="text/css" href="login_signup.css">
	<link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Concert+One|Josefin+Slab" rel="stylesheet">
	<style type="text/css">
		table, th, td {
	    	border: 1px solid black;
	    	padding: 12px 16px;
	    }
	    th{
	    	background-color: #D3D3D3;
	    }
	    tr:hover{
	    	background: lightgray;
	    }
	    table{
	    	text-align: center;
	    	width: 600px;
	    }
	</style>
</head>
<body>

	<div id="header">
		<a href='homepage.php'><img src='icons8-home-50.png' alt='home' width=2%></a>
		<a href="order_page.php" ><img src='icons8-shopping-bag-50.png' alt='home' width=2%></a>
		<a href="login_page.html" ><img src='icons8-customer-50.png' alt='home' width=2% id='loginimg'></a>
	</div>

	<?php
		// Start the session
		session_start();
	
		include("connect.php");

		if(isset($_SESSION['account'])){//抓出購物車裡的資料
			$a = $_SESSION['account'];
			$myCar_select = "SELECT * FROM  mycar WHERE mAccount LIKE \"$a\"";
			$myCar = $db -> prepare($myCar_select);
			$myCar -> execute();
			$result = $myCar -> fetchAll(PDO::FETCH_ASSOC);

			//抓訂單的資料
			$order_select = "SELECT * FROM order_detail ORDER BY order_number ASC";
			$order_select = $db -> prepare($order_select);
			$order_select -> execute();
			$order = $order_select -> fetchAll(PDO::FETCH_ASSOC);
			// print_r($order);
			$arrayLength = count($order);//計算資料庫中訂單的數量
			$priorNum = $order[$arrayLength-1]['order_number'];//因為陣列從0開始，所以要減1

			//定新的訂單編號
			$newOrderNum = $priorNum+1;

			$count = 0;
			$num = $_POST['amount'];
			// print_r($num);

			//總金額
			$x = 0;
			$sum = 0;
			while (isset($result[$x]['pPrice'])) {
			 	$sum+=($result[$x]['pPrice']*$num[$x]);
				$x++;
			}
			// echo $sum;


			for ($count=0; isset($result[$count]['pName']) ; $count++) { //加入訂單
				$sql_add_order = "INSERT INTO `order_detail` (order_number, mAccount, pName, pPrice, oAmount, oPrice) VALUES (:order_number, :mAccount,:pName, :pPrice, :oAmount, :oPrice)";
				$add_order = $db->prepare($sql_add_order);
				$add_order->execute(array(   ':order_number' => $newOrderNum,
											 ':mAccount'     => $a,
											 ':pName'        => $result[$count]['pName'],
											 ':pPrice'       => $result[$count]['pPrice'],
											 ':oAmount'      => $num[$count],
											 ':oPrice'       => $sum));
				// echo ("<p>".$result[$count]['pName']."加入訂單!</p>");
			}

			$sql_delete_books="DELETE FROM mycar WHERE mAccount LIKE \"$a\"";//清空購物車
			$delete_books = $db -> prepare($sql_delete_books);
			$delete_books->execute();

			echo "<script>
						alert(\"訂購成功\");
				  </script>";
		}
	?>
		
	<div id=maininformation_log>
		<div class="login">
			<div id='logintext'> <h1>Order</h1></div>
			<div id='logindetails' style=" height: 400px">
				<table>
					<tbody>
						<tr>
							<th>訂單號碼</th>
							<th>總金額</th>
						</tr>
						<tr>
							<td><?php echo $newOrderNum?></td>
							<td>$<?php echo $sum ?></td>
						</tr>
					</tbody>
				</table>

				<a href="shop_page.php">返回商品頁</a>
			</div>
		</div>
	</div>
	

	<div class=footer>
		<a href='#' id='footer_img1'><img src='icons8-facebook-50.jpg' alt='home' width= "30px" ></a>
		<a href="#" id='footer_img2' ><img src='icons8-gmail-50.jpg' alt='home' width= "30px"></a>
		<a href="#" id='footer_img3' ><img src='icons8-play-button-50.jpg' alt='home' width= "30px"></a>
		<h1 id='copyright'>Copy Right 2017</h1>
	</div>
</body>
</html>