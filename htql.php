<?php
	session_start();
	date_default_timezone_set('asia/ho_chi_minh');
	if(!isset($_SESSION["user_id"])) {
		echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
	}else {
	include_once('connect.php');
	if(isset($_POST['btnLogOut'])){
		session_destroy ();
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}
	$Result_permiss = mysqli_query($Conn,"SELECT user_loai  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	$Result_userLogin= mysqli_query($Conn,"SELECT user.user_name, nhanvien.nhanvien_ten, nhanvien.nhanvien_vitri FROM user LEFT JOIN nhanvien ON user.user_id=nhanvien.user_id where user.user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_userLogin=mysqli_fetch_array($Result_userLogin,MYSQLI_ASSOC);
	
	$Result_bodem= mysqli_query($Conn,'SELECT * FROM bodem WHERE bodem_id=1') or die(mysqli_connect_error($Conn));
	$Row_bodem=mysqli_fetch_array($Result_bodem,MYSQLI_ASSOC);
	if($Row_bodem["bodem_status"]==1){
		if(isset($_POST["btnEndWord"])){
			mysqli_query($Conn,'UPDATE bodem SET bodem_status=0 where bodem_id=1') or die(mysqli_connect_error());
			echo '<script>alert("Đã kết thúc một ngày làm việc bận rộn.\nGiờ thì đếm tiền thôi nào!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
		}else if(date("H:i:s")>"20:30:00"){echo '<script>alert("Sắp đến giờ đóng cửa. Tính năng kết thúc phiên đã bật.");</script>';}
	}else {
		if(isset($_POST["btnStartWord"])){
			mysqli_query($Conn,'UPDATE bodem SET bodem_status=1, bodem_hoadonngay=1 where bodem_id=1') or die(mysqli_connect_error());
			echo '<script>alert("Một ngày mới lại bắt đầu.\nChúc bạn có một ngày kinh doanh thật hiệu quả!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
		}
		if($Row_permiss['user_loai']!=2&&$Row_permiss['user_loai']!=3){
			echo '<script>alert("Phiên làm việc đã kết thúc vui lòng quay lại sau!");</script>';
			session_destroy ();
			echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
		}else if("06:00:00"<=date("H:i:s")&&date("H:i:s")<="20:30:00"&&!isset($_POST["btnStartWord"]))echo '<script>alert("Vui lòng bắt đầu phiên làm việc mới bằng cách click Mở cửa.");</script>';
	}
	
?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trà Sữa Huỳnh Hương - Hệ thống quản lý</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="img/icontitle.jpg" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body class="body">
<div class="container">
	<?php include_once("banner.html")?>
	<div class="row content_htql">
		<div class="col-md-6 content_ttdn">
			<div class="row title">Thông Tin Đăng Nhập</div>
			<div class="row" id="margin-bottom_5">
				<div class="col-md-12">Tên tài khoản: <strong><?php echo $Row_userLogin['user_name']; ?></strong></div>
				<div class="col-md-12">Họ và Tên: <strong><?php echo $Row_userLogin['nhanvien_ten']; ?></strong></div>
				<div class="col-md-12">Chức danh: <strong><?php echo $Row_userLogin['nhanvien_vitri']; ?></strong></div>
			</div>
      <div class="row text-center">
        <form action="" method="post" id="formLogOut">
          <input name="btnLogOut" class="btn btn-success" type="submit" id="btnLogOut" value="Đăng Xuất" />
          <input name="btnChangePass" class="btn btn-warning" type="button" id="btnChangePass" value="Đổi Mật Khẩu" onclick="window.location='login.php?ChangePass=1'" />
        </form>
        <form action="" method="post" id="formEndWork">
          <input name="btnEndWord" class="btn btn-success" type="submit" id="btnEndWord" value="Đóng cửa" />
        </form>
         <form action="" method="post" id="formStartWork">
          <input name="btnStartWord" class="btn btn-success" type="submit" id="btnStartWord" value="Mở cửa" />
        </form>
      </div>
		</div>
		<div class="col-md-6 danhmuc">
			<div class="row">
				<table width="100%"	>
					<tr>
						<td width="33%"><a href="menu.php"><img class="img-responsive" src="icon/icons8-restaurant-menu-96.png" /></a><p>Quản Lý Menu</p></td>
						<td width="33%"><a href="order.php?table=1A"><img class="img-responsive" src="icon/icons8-order-96.png" /></a><p>Quản Lý Order</p></td>
						<td width="33%"><a href="statistical.php"><img class="img-responsive" src="icon/icons8-account-96.png" /></a><p>Thống kê</p></td>
					</tr>
					<tr>
						<td><a href="promotion.php"><img class="img-responsive" src="icon/icons8-sale-96.png" /></a><p>Khuyến Mãi</p></td>
						<td><a href="employees.php"><img class="img-responsive" src="icon/icons8-name-tag-96.png" /></a><p>Quản Lý Nhân Viên</p></td>
						<td><a href="user.php"><img class="img-responsive" src="icon/icons8-add-user-male-96.png" /></a><p>Quản Lý Tài Khoản</p></td>
					</tr>
					<tr>
						<td><a href="spending.php"><img class="img-responsive" src="icon/icons8-cash-in-hand-96.png" /></a><p>Quản Lý Chi Phí</p></td>
						<td><a href="index_manage.php"><img class="img-responsive" src="icon/icons8-main-page-96.png" /></a><p>Quản Trị Trang Chủ</p></td>
						<td><a href="recruitment.php"><img class="img-responsive" src="icon/icons8-new-job-96.png" /></a><p>Tuyển Dụng</p></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php include_once("footer.html")?>
</div>
</body>
</html>
<script>
	$("#btnEndWord").hide();
	$("#btnStartWord").hide();
	<?php 
		if($Row_permiss['user_loai']==2||$Row_permiss['user_loai']==3){
			if($Row_bodem["bodem_status"]==1){
				if(date("H:i:s")>"20:30:00")echo '$("#btnEndWord").show();';
			}else if("06:00:00"<=date("H:i:s")&&date("H:i:s")<="20:30:00")echo '$("#btnStartWord").show();';
		}
	?>
</script>
<?php }?>