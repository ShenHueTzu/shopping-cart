<?php

			function call(){
						echo "<script>
								 alert(\"尚未登入，請先登入會員\");
							 </script>";					
			}

			function add($product_id){

				include("connect.php");
				
				//選取product的資訊
				$select = "SELECT * FROM product WHERE pId LIKE \"$product_id\"";
				$x = $db -> prepare($select);
				$x -> execute();
				$result = $x -> fetch(PDO::FETCH_ASSOC);
				//print_r($result);

				//check 是否已經加入購物車過了
				$already_add = FALSE;
				$account = $_SESSION['account'];
				$mycar_select = "SELECT * FROM mycar WHERE mAccount LIKE \"$account\"";
				$dbMycar = $db -> prepare($mycar_select);
				$dbMycar -> execute();
				$car_result = $dbMycar -> fetchAll(PDO::FETCH_ASSOC);//選取購物車中所有登入者的商品
				//print_r($car_result);

				if(isset($car_result[0]['mAccount'])){//如果使用者有商品在購物車中
					for($i = 0; $i < count($car_result); $i++){//比對是否有同樣的書本
						if($car_result[$i]['pName'] == $result['pName']){
							$already_add = TRUE;
							break;
						}
						else{
							$already_add = FALSE;
						}
					}
				}else{
					$already_add = FALSE;
				}
				
				//加入購物車
				if(!$already_add){
					$insert = "INSERT INTO `mycar` (mAccount, pName, pPrice)
							VALUES (:mAccount, :pName, :pPrice)";
					$insert_car = $db -> prepare($insert);
					$insert_car -> execute(array(':mAccount'  => $_SESSION['account'],
												 ':pName'     => $result['pName'],
												 ':pPrice'    => $result['pPrice']));
					echo "<script>
							alert(\"加入成功\");
					      </script>";
				}else{
					echo "<script>
							alert(\"商品已加入購物車\");
						  </script>";
				}	
			}

?>