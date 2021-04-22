<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else{//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permissuser  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permissuser']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else{ if($Row_permiss['user_permissuser']==1){
		if(isset($_GET['id'])){
			//cập nhật
			if($_GET["action"]=="update"){
				$Result = mysqli_query($Conn,"Select user_name, user_loai, user_permissmenu, user_permissoder, user_permissthongke, user_permisskhuyenmai, user_permissnhanvien, user_permissuser, user_permisschiphi, user_permisstrangchu, user_permisstuyendung from user Where user_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				$Row = mysqli_fetch_row($Result);
				if(isset($_POST['btnSave'])){
					$userName=$_POST['txtUserName'];
					$userPassword=$_POST['txtPassword'];
					$userLoaiTK=$_POST['slloaiTK'];
					$userPermissMenu=$_POST['slPermissMenu'];			
					$userPermissOrder=$_POST['slPermissOrder'];			
					$userPermissThongKe=$_POST['slPermissThongKe'];		
					$userPermissKhuyenMai=$_POST['slPermissKm'];	
					$userPermissNhanVien=$_POST['slPermissNhanVien'];	
					$userPermissUser=$_POST['slPermissUser'];			
					$userPermissChiPhi=$_POST['slPermissChiPhi'];		
					$userPermissTrangChu=$_POST['slPermissIndex'];	
					$userPermissTuyenDung=$_POST['slPermissTuyenDung'];
					echo $userPassword;
					if($userPassword!=""){
						$userPassword=md5($userPassword);
						mysqli_query($Conn,"UPDATE user  set user_name='$userName', user_password='$userPassword', user_loai=$userLoaiTK, user_permissmenu=$userPermissMenu, user_permissoder=$userPermissOrder, user_permissthongke=$userPermissThongKe, user_permisskhuyenmai=$userPermissKhuyenMai, user_permissnhanvien=$userPermissNhanVien, user_permissuser=$userPermissUser, user_permisschiphi=$userPermissChiPhi, user_permisstrangchu=$userPermissTrangChu, user_permisstuyendung=$userPermissTuyenDung where user_id=".$_GET["id"]) or die(mysqli_connect_error());
					}else mysqli_query($Conn,"UPDATE user  set user_name='$userName', user_loai=$userLoaiTK, user_permissmenu=$userPermissMenu, user_permissoder=$userPermissOrder, user_permissthongke=$userPermissThongKe, user_permisskhuyenmai=$userPermissKhuyenMai, user_permissnhanvien=$userPermissNhanVien, user_permissuser=$userPermissUser, user_permisschiphi=$userPermissChiPhi, user_permisstrangchu=$userPermissTrangChu, user_permisstuyendung=$userPermissTuyenDung where user_id=".$_GET["id"]) or die(mysqli_connect_error());
					echo '<script>alert("Cập nhật tài khoản thành công!");</script>';
					echo '<meta http-equiv="refresh" content="0;URL=user.php" />';
				}
			}
			//Xóa
			if($_GET["action"]=="delete"){
				mysqli_query($Conn,"Delete From user where user_id=".$_GET["id"]);
				echo '<script>alert("Xóa tài khoản thành công!");</script>';
				echo '<meta	http-equiv="refresh" content="0;URL=user.php" />';
			}
		}else {
			//themtk
			if(isset($_POST['btnSave'])){
				$userName=$_POST['txtUserName'];
				$userPassword=$_POST['txtPassword'];$userPassword=md5($userPassword);
				$userLoaiTK=$_POST['slloaiTK'];
				$userNgayKichHoat=date("Y-m-d");
				$userPermissMenu=$_POST['slPermissMenu'];			
				$userPermissOrder=$_POST['slPermissOrder'];			
				$userPermissThongKe=$_POST['slPermissThongKe'];		
				$userPermissKhuyenMai=$_POST['slPermissKm'];	
				$userPermissNhanVien=$_POST['slPermissNhanVien'];	
				$userPermissUser=$_POST['slPermissUser'];			
				$userPermissChiPhi=$_POST['slPermissChiPhi'];		
				$userPermissTrangChu=$_POST['slPermissIndex'];	
				$userPermissTuyenDung=$_POST['slPermissTuyenDung'];
				$Result_user= mysqli_query($Conn,"SELECT *  FROM user WHERE user_name='".$userName."'") or die(mysqli_connect_error($Conn));
				if(mysqli_num_rows($Result_user)!=0){
					echo '<script>alert("Tên tài khoản đã tồn tại. Vui lòng kiểm tra lại!");</script>';
					echo '<meta http-equiv="refresh" content="0;URL=user.php" />';
				}else {
					mysqli_query($Conn,"INSERT INTO user (user_name, user_password, user_loai, user_ngaykichhoat, user_permissmenu, user_permissoder, user_permissthongke, user_permisskhuyenmai, user_permissnhanvien, user_permissuser, user_permisschiphi, user_permisstrangchu, user_permisstuyendung) VALUES('$userName','$userPassword', $userLoaiTK, '$userNgayKichHoat', $userPermissMenu, $userPermissOrder, $userPermissThongKe, $userPermissKhuyenMai, $userPermissNhanVien, $userPermissUser, $userPermissChiPhi, $userPermissTrangChu, $userPermissTuyenDung)") or die(mysqli_connect_error());
					echo '<script>alert("Tạo tài khoản thành công!");</script>';
					echo '<meta http-equiv="refresh" content="0;URL=user.php" />';
				}
			}
		}
	}
	
?>

<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Quản Lý Tài Khoản</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="img/icontitle.jpg" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" />
 <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="body">
	<div class="container">
  	<?php include_once('banner.html')?>
    <div class="row content_user">
    	<div class="col-lg-12 title">Quản Lý Tài Khoản</div>
      <div class="col-lg-12 title_lv2" id="ThemUser">Thêm Mới</div>
      <div class="col-lg-12 title_lv2" id="CapNhat">Cập Nhật</div>
      <div class="col-lg-12 input" id="margin-bottom_5"><form action="" method="post" enctype="multipart/form-data" name="formUser" id="formUser">
      	<div class="row" ><input name="txtUserName" type="text" required class="form-control" id="txtUserName" placeholder="Nhập vào tên tài khoản" <?php if(isset($Row[0])) echo 'readonly value="'.$Row[0].'"'; ?> /></div>
        <div class="row "><input name="txtPassword" type="text" id="txtPassword" <?php if(isset($Row[0])) echo 'placeholder="Nhập vào mật khẩu mới nếu cần cấp lại"'; else echo 'placeholder="Nhập vào mật khẩu" required'; ?> class="form-control" /></div>
        <div class="row"><select name="slloaiTK" id="slloaiTK" class="form-control">
        	<option value="">chọn loại tài khoản</option>
          <option value="1" <?php if(isset($Row[1])&&$Row[1]==1) echo 'selected'; ?>>admin</option>
          <option value="2" <?php if(isset($Row[1])&&$Row[1]==2) echo 'selected'; ?>>boss</option>
          <option value="3" <?php if(isset($Row[1])&&$Row[1]==3) echo 'selected'; ?>>Quản Lý</option>
          <option value="4" <?php if(isset($Row[1])&&$Row[1]==4) echo 'selected'; ?>>Nhân Viên Phục Vụ</option>
          <option value="5" <?php if(isset($Row[1])&&$Row[1]==5) echo 'selected'; ?>>Nhân Viên Pha Chế</option>
          <option value="6" <?php if(isset($Row[1])&&$Row[1]==6) echo 'selected'; ?>>Bếp, Phụ Bếp</option>
        </select></div>
        <div class="row title_lv2">Phân Quyền Hệ Thống</div>
        <div class="row permiss">
        	<div class="col-lg-2"><div class="row">
        	  <label for="slPermissMenu">Quản Lý Menu:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissMenu" id="slPermissMenu">
              <option value="1" <?php if(isset($Row[2])&&$Row[2]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[2])&&$Row[2]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[2])&&$Row[2]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row">
            <label for="slPermissOrder">Quản Lý Order:</label>
          </div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissOrder" id="slPermissOrder">
              <option value="1" <?php if(isset($Row[3])&&$Row[3]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[3])&&$Row[3]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[3])&&$Row[3]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row"><label for="slPermissThongKe">Quản Lý Thống Kê:</label>
          </div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissThongKe" id="slPermissThongKe">
              <option value="1" <?php if(isset($Row[4])&&$Row[4]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[4])&&$Row[4]==0) echo 'selected'; ?>>Không</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row"><label for="slPermissKm">Quản Lý Khuyến Mãi:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissKm" id="slPermissKm">
              <option value="1" <?php if(isset($Row[5])&&$Row[5]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[5])&&$Row[5]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[5])&&$Row[5]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row"><label for="slPermissNhanVien">Quản Lý Nhân Viên:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissNhanVien" id="slPermissNhanVien">
              <option value="1" <?php if(isset($Row[6])&&$Row[6]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[6])&&$Row[6]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[6])&&$Row[6]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row"><label for="slPermissUser">Quản Lý Tài Khoản:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissUser" id="slPermissUser">
              <option value="1" <?php if(isset($Row[7])&&$Row[7]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[7])&&$Row[7]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[7])&&$Row[7]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row"><label for="slPermissChiPhi">Quản Lý Chi Phí:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissChiPhi" id="slPermissChiPhi">
              <option value="1" <?php if(isset($Row[8])&&$Row[8]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[8])&&$Row[8]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[8])&&$Row[8]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row">
            <label for="slPermissIndex">Quản Trị Trang Chủ:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissIndex" id="slPermissIndex">
              <option value="1" <?php if(isset($Row[9])&&$Row[9]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[9])&&$Row[9]==0) echo 'selected'; ?>>Không</option>
            </select>
          </div></div>
          <div class="col-lg-2"><div class="row">
            <label for="slPermissTuyenDung">Tuyển Dụng:</label></div></div>
          <div class="col-lg-2"><div class="row">
            <select class="form-control" name="slPermissTuyenDung" id="slPermissTuyenDung">
              <option value="1" <?php if(isset($Row[10])&&$Row[10]==1) echo 'selected'; ?>>Có</option>
              <option value="0" <?php if(isset($Row[10])&&$Row[10]==0) echo 'selected'; ?>>Không</option>
              <option value="2" <?php if(isset($Row[10])&&$Row[10]==2) echo 'selected'; ?>>Chỉ xem</option>
            </select>
          </div></div>
        </div>
        <div class="row text-center" id="padding-bottom_0">
        	<input name="btnSave" class="btn btn-success" type="submit" value="Lưu Tài Khoản" id="btnSave" />
          <input name="btnExit" class="btn btn-warning" type="button" value="Trở Về" id="btnExit" onclick="window.location='<?php if(isset($_GET['id'])) echo 'user.php'; else echo 'htql.php'; ?>'" />
        </div></form>
      </div>
      <div class="col-lg-12 title_lv2">Danh Sách Tài Khoản</div>
      <div class="col-lg-12">
        <div class="row">
        	<div class="table-responsive" id="margin-bottom_0">
            <table class="table table-hover" id="tableUser"  width="100%" border="1">
              <thead><tr class="TH">	
                <td rowspan="2">ID</td>
                <td rowspan="2">Tên Tài Khoản</td>
                <td rowspan="2">Loại Tài Khoản</td>
                <td rowspan="2">Ngày Kích Hoạt</td>
                <td colspan="9">Phân Quyền</td>
                <td class="edit" rowspan="2">Chỉnh Sửa</td>
                <td class="delete" rowspan="2">Xóa</td>
              </tr>
              <tr class="TH">
                <td>Menu</td>
                <td>Order</td>
                <td>Thống Kê</td>
                <td>Khuyến Mãi</td>
                <td>Nhân Viên</td>
                <td>Tài Khoản</td>
                <td>Chi Phi</td>
                <td>Trang Chủ</td>
                <td>Tuyển Dụng</td>
              </tr></thead>
              <tbody>
								<?php 
									$result = mysqli_query($Conn, "SELECT * FROM user");
									$stt=1;
                	while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                ?>
                <tr>
                  <td align="center"><?php echo $row["user_id"] ?></td>
                  <td><?php echo $row["user_name"] ?></td>
                  <td align="center">
										<?php
											switch($row["user_loai"]){ 
												case 1: echo "admin"; break;
												case 2: echo "boss"; break;
												case 3: echo "Quản lý"; break;
												case 4: echo "Nhân viên phục vụ"; break;
												case 5: echo "Nhân viên pha chế"; break;
												case 6: echo "Bếp, phụ bếp"; break;
											}
                    ?>
                  </td>
                  <td align="center"><?php echo date_format(date_create($row["user_ngaykichhoat"]),"d-m-Y"); ?></td>
                  <td align="center">
										<?php
                      switch($row["user_permissmenu"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permissoder"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permissthongke"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permisskhuyenmai"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permissnhanvien"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permissuser"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permisschiphi"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permisstrangchu"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td align="center">
										<?php
                      switch($row["user_permisstuyendung"]){ 
                        case 1: echo "Có"; break;
                        case 2: echo "Chỉ xem"; break;
                        case 0: echo "Không"; break;
                      }
                    ?>
                  </td>
                  <td class="edit" align="center"><a href="?id=<?php echo $row["user_id"] ?>&action=update"><img src="icon/icons8-edit-48.png" width="40" /></a></td>
                  <td class="delete" align="center"><a href="?id=<?php echo $row["user_id"] ?>&action=delete" onClick="return deleteConfirm()"><img src="icon/icons8-delete-forever-48.png" width="40" /></a></td>
                </tr>
							<?php
              	$stt++;
              	}
              ?>
              </tbody>
            </table>        
          </div>
          <div class="text-center"><input name="btnExit2" type="button" class="btn btn-warning" id="btnExit2" value="Trở Về" onclick="window.location='htql.php'"></div>
        </div>
      </div>
    </div>
    <?php include_once('footer.html')?>
  </div>
</body>
</html>
<script>
	function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
	}
	$('#CapNhat').hide();
	$("#btnExit2").hide()
	$(document).ready(function(e) {
		var table = $("#tableUser").DataTable({
				responsive:true,
					"language":{
						"lengthMenu": "Hiển thị _MENU_ dòng dữ liệu trên một trang",
						"info":"Hiển thị trang _PAGE_ trên _PAGES_",
						"infoEmpty": "Dữ liệu rỗng",
						"processing":"Đang xử lý...",
						"search":"Tìm kiếm",
						"loadingRecords":"Đang load dữ liệu...",
						"zeroRecords":"Không tìm thấy dữ liệu",
						"infoFiltered":"(Được lọc từ _MAX_ dòng dữ liệu)",
						"paginate":{
							"first":"|<",
							"last":">|",
							"next":">>",
							"previous":"<<"	
						}
				},
			"lengthMenu":[[2,5,10,15,20,-1],[2,5,10,15,20,"Tất cả"]]	
		});
	});
	$("#btnSave").click(function(e) {
    var loi=0;
		if($('#slloaiTK').val()=="")	loi=1;
		if(loi==1) {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
  });
	<?php 
		if($Row_permiss['user_permissuser']==2) echo '$("#formUser, .edit, .delete, #ThemUser").hide();$("#btnExit2").show()';
		if(isset($_GET["action"])&&$_GET["action"]=="update") echo '$("#CapNhat").show(); $("#ThemUser").hide();'
	?>
</script>
<?php }} ?>