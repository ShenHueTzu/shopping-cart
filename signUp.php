<?php
	include("connect.php");

	//select mId
	$sql_select = "SELECT mId FROM member ORDER BY mId DESC";
	$mId        = $db  -> prepare($sql_select);
	$mId               -> execute();
	$result     = $mId -> fetchAll(PDO::FETCH_BOTH);
	//print_r($result);
	$count      = $result[0][0];

	//select mAccount
	$Acc = $_POST['uAccount'];
	$select_mAccount = "SELECT mAccount FROM member WHERE mAccount LIKE \"$Acc\"";
	$account = $db -> prepare($select_mAccount);
	$account -> execute();
	$account_result = $account -> fetchAll(PDO::FETCH_BOTH);

	if(isset($account_result[0]['mAccount'])){
	?> 
		<script type="text/javascript">
			alert("此帳號名稱已有人使用");
		</script>
	<?php 
	}else{
		$count = $count + 1;
		$sql_insert_member = "INSERT INTO `member` (mId, mAccount, mPassword, mName, mSex, mPhone,mEmail, mAddress)
								VALUES (:mId, :mAccount, :mPassword, :mName, :mSex, :mPhone, :mEmail, :mAddress)";
		$insert_member = $db -> prepare($sql_insert_member);
		$insert_member -> execute(array(':mId'       => $count,
										':mAccount'  => $_POST['uAccount'],
										':mPassword' => $_POST['uPassword'],
										':mName'     => $_POST['uName'],
										':mSex'      => $_POST['uGender'],
										':mPhone'    => $_POST['uPhone'],
										':mEmail'    => $_POST['uEmail'],
										':mAddress'  => $_POST['uAddress']));
		echo "<script>
						alert(\"註冊成功，請重新登入\"); 
						location.href = 'login_page.html';
				  </script>";
		}
    ?>