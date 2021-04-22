<?php
	session_start();
	include_once('connect.php');
	$Mabv_id=rand(01,10);
	$Result_Mbv = mysqli_query($Conn,"Select * From mabaove Where mbv_id=$Mabv_id") or die(mysqli_connect_error($Conn));
	$Row_mbv=mysqli_fetch_array($Result_Mbv,MYSQLI_ASSOC);
	$Mbv=$Row_mbv['mbv_giatri'];
	//login
	if(isset($_POST['btnLogin'])){
		$UserName=$_POST['txtUserName'];
		$PassWord=$_POST['txtPassword'];
		$PassWord=md5($PassWord);
		$Result_login = mysqli_query($Conn,"Select * From user Where user_name='$UserName' and user_password='$PassWord'") or die(mysqli_connect_error($Conn));
		if(mysqli_num_rows($Result_login)==1){
			$Row_user=mysqli_fetch_array($Result_login,MYSQLI_ASSOC);	
			$_SESSION["user_id"]=$Row_user['user_id'];
			echo '<script>alert("Đăng nhập thành công!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
		}else echo '<script>alert("Tên đăng nhập hoặc mật khẩu không đúng.\nVui lòng kiểm tra lại!");</script>';
	}
	//DoiMatKhau
	if(isset($_GET['ChangePass']))
		if(is_numeric($_GET['ChangePass'])){
			$Result_user = mysqli_query($Conn,"Select * From user Where user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
			$Row_userdata=mysqli_fetch_array($Result_user,MYSQLI_ASSOC);
			if(isset($_POST['btnSave'])){
				$OldPass=$_POST['txtOldPass'];		$OldPass=md5($OldPass);
				$PassWord=$_POST['txtPassword'];	$PassWord=md5($PassWord);
				if($OldPass==$Row_userdata['user_password']){
					mysqli_query($Conn,"UPDATE user set user_password='$PassWord' where user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error());
					session_destroy ();
					echo '<script>alert("Mật khẩu đã thay đổi. Vui lòng đăng nhập lại!");</script>';
					echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
				}else  echo '<script>alert("Mật khẩu cũ không đúng. Vui lòng thử lại!");</script>';
			}
		}else echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	else if(isset($_SESSION["user_id"]))
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
?>
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Đăng nhập</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="img/icontitle.jpg" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="body">
	<div class="container">
		<?php include_once('banner.html')?>    
		<div class="row content_login">
			<?php
      	if(isset($_GET['ChangePass'])){
			?>
			<div class="col-md-12 input">
        <form action="" method="post" enctype="multipart/form-data" name="formChangePass" id="formChangePass">
          <div class="row title">Thay Đổi Mật Khẩu</div>
          <div class="row" ><input name="txtUserName" type="text" class="form-control" id="txtUserName" <?php if(isset($Row_userdata['user_name'])) echo 'readonly value="'.$Row_userdata["user_name"].'"'; ?> /></div>
          <div class="row "><input name="txtOldPass" type="password" id="txtOldPass" placeholder="Nhập vào mật khẩu cũ" required class="form-control" /></div>
          <div class="row "><input name="txtPassword" type="password" id="txtPassword" placeholder="Nhập vào mật khẩu mới" required class="form-control" /></div>
          <div class="row "><input name="txtCheckPass" type="password" id="txtCheckPass" placeholder="Xác nhận lại mật khẩu" class="form-control" /></div>
          <div class="row">
            <table width="100%" border="0">
              <tr>
                <td width="100%"><input name="txtMabv" type="text" id="txtMabv" placeholder="Nhập vào mã bảo vệ được hiển thị bên cạnh" required class="form-control" /></td>
                <td><img src="mabv/mabv_<?php echo $Mabv_id; ?>.png" width="200" height="35" /></td>
              </tr>
            </table>          
          </div>
          <div class="row" id="padding-bottom_0">
            <input name="btnSave" class="btn btn-success" type="submit" value="Lưu" id="btnSave" />
            <input name="btnExit" class="btn btn-warning" type="button" value="Thoát" id="btnExit" onclick="window.location='htql.php'" />
          </div>
        </form>
      </div>
			<?php }
			else {
			?>
			<div class="col-md-12 input">
        <form action="" method="post" enctype="multipart/form-data" name="formLogin" id="formLogin">
          <div class="row title">Đăng Nhập</div>
          <div class="row" ><input name="txtUserName" type="text" id="txtUserName" placeholder="Nhập vào tên tài khoản" required class="form-control" /></div>
          <div class="row "><input name="txtPassword" type="password" id="txtPassword" placeholder="Nhập vào mật khẩu" required class="form-control" /></div>
          <div class="row">
            <table width="100%" border="0">
              <tr>
                <td width="100%"><input name="txtMabv" type="text" id="txtMabv" placeholder="Nhập vào mã bảo vệ được hiển thị bên cạnh" class="form-control" /></td>
                <td><img src="mabv/mabv_<?php echo $Mabv_id; ?>.png" width="200" height="35" /></td>
              </tr>
            </table>          
          </div>
          <div class="row" id="padding-bottom_0">
            <input name="btnLogin" class="btn btn-success" type="submit" value="Đăng Nhập" id="btnLogin"/>
            <input name="btnExit" class="btn btn-warning" type="button" value="Thoát" id="btnExit" onclick="window.location='index.php'" />
          </div>
        </form>
      </div>
      <?php }	?>
		</div>
		<?php include_once('footer.html')?>
	</div>
</body>
</html>
<script>
	$(document).ready(function(e) {
		$('#btnLogin').click(function(e) {
      var mabv = $('#txtMabv').val();
			var loi="";
			if($('#txtUserName').val()=="")	loi+="Vui lòng nhập tên tài khoản!\n";
			if($('#txtPassword').val()=="")	loi+="Vui lòng nhập mật khẩu!\n";
			if(mabv!="<?php echo $Mbv?>") loi+="Mã bảo vệ chưa đúng!";
			if(loi!="") {alert(loi);return false;}
    });
		$('#btnSave').click(function(e) {
      var mabv = $('#txtMabv').val();
			var loi="";
			if($('#txtOldPass').val()=="")	loi+="Vui lòng nhập mật khẩu cũ!\n";
			if($('#txtPassword').val()=="")	loi+="Vui lòng nhập mật khẩu mới!\n";
			if($('#txtPassword').val()!=$('#txtCheckPass').val()) loi+="Xác nhận mật khẩu chưa đúng!\n";
			if(mabv!="<?php echo $Mbv?>") loi+="Mã bảo vệ chưa đúng!";
			if(loi!="") {alert(loi);return false;}
    });
  });
</script>