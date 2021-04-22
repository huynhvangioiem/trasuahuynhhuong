<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permissnhanvien  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permissnhanvien']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else if($Row_permiss['user_permissnhanvien']!=0){//2
		if(isset($_GET["action"])){
			//Thêm
			if($_GET["action"]=="add"){
				if(isset($_POST["btnLuu"])){
					$UserId=$_POST["slIdUser"];
					$TenNhanVien=$_POST["txtHoTen"];
					$HinhAnh=$_FILES["fileHinhAnh"];
					$TenAnh=$HinhAnh['name'];
					$NgaySinh=$_POST["txtNgaySinh"];
					$GioiTinh=$_POST["slGioiTinh"];
					$Cmnd=$_POST["txtCMND"];
					$DiaChi=$_POST["txtDiaChi"];
					$SDT=$_POST["txtSDT"];
					$ViTri=$_POST["txtViTri"];
					$NgayBatDau=$_POST["txtNgayBatDau"];
					$MucLuong=$_POST["txtLuong"];
					if($TenAnh==""){ 
						mysqli_query($Conn,'INSERT INTO nhanvien (user_id, nhanvien_ten, nhanvien_ngaysinh, nhanvien_gioitinh, nhanvien_cmnd, nhanvien_diachi, nhanvien_sdt, nhanvien_vitri, nhanvien_batdau, nhanvien_luong) VALUES ('.$UserId.',"'.$TenNhanVien.'","'.$NgaySinh.'",'.$GioiTinh.',"'.$Cmnd.'","'.$DiaChi.'","'.$SDT.'","'.$ViTri.'","'.$NgayBatDau.'",'.$MucLuong.')') or die(mysqli_connect_error($Conn));
						echo '<script>alert("Thêm nhân viên thành công!");</script>';
						echo '<meta	http-equiv="refresh" content="0;URL=employees.php" />';
					}else{
						if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
							$TenAnh="nhanvien_".$UserId.".jpg";
							copy ($HinhAnh['tmp_name'],"imgnv/".$TenAnh);
							mysqli_query($Conn,'INSERT INTO nhanvien (user_id, nhanvien_ten, nhanvien_hinhanh, nhanvien_ngaysinh, nhanvien_gioitinh, nhanvien_cmnd, nhanvien_diachi, nhanvien_sdt, nhanvien_vitri, nhanvien_batdau, nhanvien_luong) VALUES ('.$UserId.',"'.$TenNhanVien.'","'.$TenAnh.'","'.$NgaySinh.'",'.$GioiTinh.',"'.$Cmnd.'","'.$DiaChi.'","'.$SDT.'","'.$ViTri.'","'.$NgayBatDau.'",'.$MucLuong.')') or die(mysqli_connect_error($Conn));								
							echo '<script>alert("Thêm nhân viên thành công!");</script>';
							echo '<meta	http-equiv="refresh" content="0;URL=employees.php" />';
						}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
					}
				}
			}else if(!is_numeric($_GET["id"])) echo '<meta	http-equiv="refresh" content="0;URL=employees.php" />';
			//ChitietNhanVien
			if(isset($_GET["id"])){
				$Resultnv = mysqli_query($Conn,"SELECT nhanvien.*, user.user_name FROM nhanvien LEFT JOIN user ON nhanvien.user_id=user.user_id WHERE nhanvien_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				$rownv=mysqli_fetch_array($Resultnv, MYSQLI_ASSOC);
				$ResultKl=mysqli_query($Conn,"SELECT *  FROM nhanvienkyluat WHERE nhanvien_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				$ResultNn=mysqli_query($Conn,"SELECT *  FROM nhanvienngaynghi WHERE nhanvien_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				$ResultKt=mysqli_query($Conn,"SELECT *  FROM nhanvienkhenthuong WHERE nhanvien_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				$Resultluong=mysqli_query($Conn,"SELECT *  FROM nhanvienluong WHERE nhanvien_id=".$_GET["id"]) or die(mysqli_connect_error($Conn));
				if(isset($_POST["btnLuuKL"])){
					$tenKl=$_POST["txtViPham"];
					$hinhThucKl=$_POST["txtHinhThucKL"];
					mysqli_query($Conn,'INSERT INTO nhanvienkyluat (nhanvien_id, nhanvienkyluat_ngay, nhanvienkyluat_ten, nhanvienkyluat_hinhthuc) VALUES ('.$_GET["id"].',"'.date("Y-m-d H:i:s").'","'.$tenKl.'",'.$hinhThucKl.')') or die(mysqli_connect_error($Conn));								
					echo '<script>alert("Đã thêm kỹ luật!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
				}
				if(isset($_POST["btnLuuNn"])){
					$lydo=$_POST["txtLydo"];
					$songay=$_POST["txtsongay"];
					mysqli_query($Conn,'INSERT INTO nhanvienngaynghi (nhanvien_id, nhanvienngaynghi_ngay, nhanvienngaynghi_lydo	, nhanvienngaynghi_songay) VALUES ('.$_GET["id"].',"'.date("Y-m-d H:i:s").'","'.$lydo.'",'.$songay.')') or die(mysqli_connect_error($Conn));								
					echo '<script>alert("Đã thêm ngày nghỉ!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
				}
				if(isset($_POST["btnLuuKT"])){
					$tenKT=$_POST["txtThanhTich"];
					$hinhThucKT=$_POST["txtHinhThuc"];
					mysqli_query($Conn,'INSERT INTO nhanvienkhenthuong (nhanvien_id, nhanvienkhenthuong_ngay, nhanvienkhenthuong_ten, nhanvienkhenthuong_hinhthuc) VALUES ('.$_GET["id"].',"'.date("Y-m-d H:i:s").'","'.$tenKT.'",'.$hinhThucKT.')') or die(mysqli_connect_error($Conn));								
					echo '<script>alert("Đã thêm khen thưởng!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
				}
				if(isset($_POST["btnTamUng"])){
					$tamUng=$_POST["txtTamUng"];
					mysqli_query($Conn,'UPDATE nhanvien SET nhanvien_tamung='.$tamUng.', nhanvien_ngaytamung="'.date("Y-m-d H:i:s").'" Where nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));								
					echo '<script>alert("Đã thêm tạm ứng!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
				}
			}
			//cập nhật
			if($_GET["action"]=="update"){
				if(isset($_POST["btnLuu"])){
					$TenNhanVien=$_POST["txtHoTen"];
					$HinhAnh=$_FILES["fileHinhAnh"];
					$TenAnh=$HinhAnh['name'];
					$NgaySinh=$_POST["txtNgaySinh"];
					$GioiTinh=$_POST["slGioiTinh"];
					$Cmnd=$_POST["txtCMND"];
					$DiaChi=$_POST["txtDiaChi"];
					$SDT=$_POST["txtSDT"];
					$ViTri=$_POST["txtViTri"];
					$NgayBatDau=$_POST["txtNgayBatDau"];
					$MucLuong=$_POST["txtLuong"];
					if($TenAnh==""){ 
						mysqli_query($Conn,'UPDATE nhanvien SET nhanvien_ten="'.$TenNhanVien.'", nhanvien_ngaysinh="'.$NgaySinh.'", nhanvien_gioitinh='.$GioiTinh.', nhanvien_cmnd="'.$Cmnd.'", nhanvien_diachi="'.$DiaChi.'", nhanvien_sdt="'.$SDT.'", nhanvien_vitri="'.$ViTri.'", nhanvien_batdau="'.$NgayBatDau.'", nhanvien_luong='.$MucLuong.' WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
						echo '<script>alert("Cập nhật nhân viên thành công!");</script>';
						echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
					}else{
						if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
							$TenAnh="nhanvien_".$_GET["id"].".jpg";
							$tenXoa="imgnv/".$rownv["nhanvien_hinhanh"];
							unlink("$tenXoa");
							copy ($HinhAnh['tmp_name'],"imgnv/".$TenAnh);
							mysqli_query($Conn,'UPDATE nhanvien SET nhanvien_ten="'.$TenNhanVien.'", nhanvien_hinhanh="'.$TenAnh.'", nhanvien_ngaysinh="'.$NgaySinh.'", nhanvien_gioitinh='.$GioiTinh.', nhanvien_cmnd="'.$Cmnd.'", nhanvien_diachi="'.$DiaChi.'", nhanvien_sdt="'.$SDT.'", nhanvien_vitri="'.$ViTri.'", nhanvien_batdau="'.$NgayBatDau.'", nhanvien_luong='.$MucLuong.' WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
							echo '<script>alert("Cập nhật nhân viên thành công!");</script>';
							echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
						}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
					}
				}
			}
			//xóa
			if($_GET["action"]=="delete"){
				mysqli_query($Conn,'DELETE FROM nhanvienkhenthuong WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
				mysqli_query($Conn,'DELETE FROM nhanvienkyluat WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
				mysqli_query($Conn,'DELETE FROM nhanvienluong WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
				mysqli_query($Conn,'DELETE FROM nhanvienngaynghi WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
				mysqli_query($Conn,'DELETE FROM nhanvien WHERE nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
				$tenXoa="imgnv/".$rownv["nhanvien_hinhanh"];
				unlink("$tenXoa");
				echo '<script>alert("Đã xóa nhân viên!");</script>';
				echo '<meta	http-equiv="refresh" content="0;URL=employees.php/>';
			}
		}
?>
<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Nhân Viên</title>
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
    <div class="row content_employees">
      <div class="col-lg-12">
      	<div class="row title">Quản Lý Nhân Viên</div>
        <div class="row" id="DsNv">
        	<?php
          $ResultDsnv = mysqli_query($Conn,"SELECT nhanvien_id, nhanvien_ten, nhanvien_hinhanh, nhanvien_vitri  FROM nhanvien ") or die(mysqli_connect_error($Conn));
					while($rowDsnv=mysqli_fetch_array($ResultDsnv, MYSQLI_ASSOC)){
					?>
          <div class="col-lg-3 NhanVien text-center"><div>
          	<a href="?id=<?php echo $rowDsnv["nhanvien_id"]; ?>&action=detail"><img src="imgnv/<?php if($rowDsnv["nhanvien_hinhanh"]!="")echo $rowDsnv["nhanvien_hinhanh"]; else echo "no_img.png" ?>"  height="225" /></a>
            <p><span class="Cv"><?php echo $rowDsnv["nhanvien_vitri"]; ?></span><br /><span class="ten"><?php echo $rowDsnv["nhanvien_ten"]; ?></span></p>
          </div></div>
          <?php }?>
          <div class="col-lg-3 NhanVien text-center"><div>
          	<a id="linkAdd" href="?action=add"><img src="icon/icons8-plus-480.png" height="235"/></a>
          	<input name="btnExit" class="btn btn-warning form-control" type="button" id="btnExit" value="Trở Về" onclick="window.location='htql.php'"/>
          </div></div>
        </div>
        <div class="row" id="ThemNv">
        	<form action="" method="post" enctype="multipart/form-data" id="formThemNv">
            <table class="table table-striped" width="100%">
              <tr>
                <td>Chọn tài khoản:</td>
                <td>
                	<select name="slIdUser" <?php if(isset($_GET["id"]))echo "disabled";?> class="form-control" id="slIdUser">
                	  <option value="Null" >Chọn tài khoản tương ứng</option>
										<?php
											$Result_user = mysqli_query($Conn,"SELECT user_id, user_name  FROM user ") or die(mysqli_connect_error($Conn));
                    	while($row_user=mysqli_fetch_array($Result_user, MYSQLI_ASSOC)){
                    ?>
                    <option value="<?php echo $row_user["user_id"]; ?>"<?php if(isset($_GET["id"])&&$rownv["user_id"]==$row_user["user_id"]) echo "selected"; ?>><?php echo $row_user["user_name"]; ?></option>
                    <?php }?>
                	</select>
                </td>
              </tr>
              <tr>
                <td>Ảnh Đại Diện:</td>
                <td><input name="fileHinhAnh" class="form-control" placeholder="Nhập họ tên" type="file" id="fileHinhAnh" /></td>
              </tr>
              <tr>
                <td>Họ Tên:</td>
                <td><input name="txtHoTen" required class="form-control" placeholder="Nhập họ tên" type="text" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_ten"];?>" id="txtHoTen" /></td>
              </tr>
              <tr>
                <td>Ngày Sinh:</td>
                <td><input name="txtNgaySinh" required class="form-control" placeholder="Nhập ngày sinh" type="date" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_ngaysinh"];?>" id="txtNgaySinh" /></td>
              </tr>
              <tr>
                <td>Giới Tính:</td>
                <td><select name="slGioiTinh" class="form-control" id="slGioiTinh">
                  <option value="Null">Chọn giới tính</option>
                  <option value="1" <?php if(isset($_GET["id"])&&$rownv["nhanvien_gioitinh"]==1) echo "selected"; ?>>Nam</option>
                  <option value="0" <?php if(isset($_GET["id"])&&$rownv["nhanvien_gioitinh"]==0) echo "selected"; ?>>Nữ</option>
                </select></td>
              </tr>
              <tr>
              	<td>CMND:</td>
                <td><input name="txtCMND" required class="form-control" placeholder="Nhập số chứng minh nhân dân" type="text" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_cmnd"];?>" id="txtCMND" /></td>
              </tr>
              <tr>
              	<td>Địa Chỉ:</td>
                <td><input name="txtDiaChi" required class="form-control" placeholder="Nhập địa chỉ" type="text" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_diachi"];?>" id="txtDiaChi" /></td>
              </tr>
              <tr>
              	<td>Di Động</td>
                <td><input name="txtSDT" required class="form-control" placeholder="Nhập số điện thoại" type="text" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_sdt"];?>" id="txtSDT" /></td>
              </tr>
              <tr>
              	<td>Vị Trí:</td>
                <td><input name="txtViTri" required class="form-control" placeholder="Nhập vị trí làm việc" type="text" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_vitri"];?>" id="txtViTri" /></td>
              </tr>
              <tr>
              	<td>Ngày Bắt Đầu:</td>
                <td><input name="txtNgayBatDau" required class="form-control"  type="date" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_batdau"];?>" id="txtNgayBatDau" /></td>
              </tr>
              <tr>
              	<td>Mức lương:</td>
                <td><input name="txtLuong" required class="form-control" placeholder="Nhập mức lương / ngày" type="number" min="1000" value="<?php if(isset($_GET["id"]))echo $rownv["nhanvien_luong"];?>" id="txtLuong" /></td>
              </tr>
            </table>
            <div class="text-center">
              <input name="btnLuu" type="submit" class="btn btn-success" id="btnLuu" value="Lưu" />
              <input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='employees.php<?php if($_GET["action"]=="update")echo "?id=".$_GET["id"]."&action=detail"?>'"/>
            </div>
        	</form>
        </div>
        <div class="row" id="NvChiTiet">
        	<div class="col-lg-3 text-center">
          	<img src="imgnv/<?php if($rownv["nhanvien_hinhanh"]!="")echo $rownv["nhanvien_hinhanh"]; else echo "no_img.png" ?>" width="251" height="251" />
          </div>
          <div class="col-lg-9"><div class="row">
          	<table class="table table-striped" width="100%" >
              <tr>
                <td>ID Tài Khoản:</td>
                <td><?php echo $rownv["user_id"];?></td>
              </tr>
              <tr>
                <td>Tên Tài Khoản:</td>
                <td><?php echo $rownv["user_name"];?></td>
              </tr>
              <tr>
                <td>Họ Tên:</td>
                <td><?php echo $rownv["nhanvien_ten"];?></td>
              </tr>
              <tr>
                <td>Ngày Sinh:</td>
                <td><?php echo date_format(date_create($rownv["nhanvien_ngaysinh"]),"d/m/Y");?></td>
              </tr>
              <tr>
                <td>Giới Tính:</td>
                <td><?php if($rownv["nhanvien_gioitinh"]==1)echo "Nam"; else echo "Nữ";?></td>
              </tr>
              <tr>
                <td>CMND:</td>
                <td><?php echo $rownv["nhanvien_cmnd"];?></td>
              </tr>
              <tr>
                <td>Địa Chỉ: </td>
                <td><?php echo $rownv["nhanvien_diachi"];?></td>
              </tr>
              <tr>
                <td>Số Điện Thoại:</td>
                <td><?php echo $rownv["nhanvien_sdt"];?></td>
              </tr>
              <tr>
                <td>Vị Trí:</td>
                <td><?php echo $rownv["nhanvien_vitri"];?></td>
              </tr>
              <tr>
                <td>Ngày bắt đầu: </td>
                <td><?php echo date_format(date_create($rownv["nhanvien_batdau"]),"d/m/Y");?></td>
              </tr>
              <tr>
                <td>Mức lương/ngày:</td>
                <td><?php echo number_format($rownv["nhanvien_luong"],0,",",".");?> VNĐ</td>
              </tr>
              <?php 
							if(isset($_GET["action"])&&$_GET["action"]=="detail"){
								$ResultLuongHt=mysqli_query($Conn,"SELECT nhanvienluong_ngaythanhtoan FROM nhanvienluong WHERE nhanvien_id=".$_GET["id"]." ORDER BY nhanvienluong_id DESC LIMIT 1 ") or die(mysqli_connect_error($Conn));
								$rowNgayTT=mysqli_fetch_array($ResultLuongHt, MYSQLI_ASSOC);
								$ngayTT=$rowNgayTT["nhanvienluong_ngaythanhtoan"];
								if($ngayTT==""){
									$ngayTT=$rownv["nhanvien_batdau"];
									$ngaylam=(strtotime(date("Y-m-d"))-strtotime($ngayTT))/(60*60*24)+1;
								}else {
									$ngayTT=date_format(date_create($ngayTT),"Y-m-d");
									$ngaylam=(strtotime(date("Y-m-d"))-strtotime($ngayTT))/(60*60*24);
								}
								//echo date("Y-m-d")."<br>";
								//echo $ngayTT."<br>";
								//echo $ngaylam."<br>";
								//echo $rowNgayTT["nhanvienluong_ngaythanhtoan"];
								$kyluat=0;
								$ResultKlTT=mysqli_query($Conn,"SELECT nhanvienkyluat_hinhthuc FROM nhanvienkyluat WHERE nhanvien_id=".$_GET["id"]." and nhanvienkyluat_ngay>='".$rowNgayTT["nhanvienluong_ngaythanhtoan"]."'") or die(mysqli_connect_error($Conn));
								while($rowKlTT=mysqli_fetch_array($ResultKlTT, MYSQLI_ASSOC)){
									$kyluat+=$rowKlTT["nhanvienkyluat_hinhthuc"];
								}
								$NgayNghi=0;
								$ResultNnTT=mysqli_query($Conn,"SELECT nhanvienngaynghi_songay FROM nhanvienngaynghi WHERE nhanvien_id=".$_GET["id"]." and nhanvienngaynghi_ngay>='".$rowNgayTT["nhanvienluong_ngaythanhtoan"]."'") or die(mysqli_connect_error($Conn));
								while($rowNnTT=mysqli_fetch_array($ResultNnTT, MYSQLI_ASSOC)){
									$NgayNghi+=$rowNnTT["nhanvienngaynghi_songay"];
								}
								$khenthuong=0;
								$ResultKtTT=mysqli_query($Conn,"SELECT nhanvienkhenthuong_hinhthuc FROM nhanvienkhenthuong WHERE nhanvien_id=".$_GET["id"]." and nhanvienkhenthuong_ngay>='".$rowNgayTT["nhanvienluong_ngaythanhtoan"]."'") or die(mysqli_connect_error($Conn));
								while($rowKtTT=mysqli_fetch_array($ResultKtTT, MYSQLI_ASSOC)){
									$khenthuong+=$rowKtTT["nhanvienkhenthuong_hinhthuc"];
								}
								$luong=($ngaylam-$NgayNghi)*$rownv["nhanvien_luong"]+$kyluat+$khenthuong-$rownv["nhanvien_tamung"];
							}
							?>
              <tr>
                <td>Kỷ luật:</td>
                <td>
                  	<table id="KyLuat2" class="table-hover" width="100%" border="1">
                      <tr>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Ngày Kỷ Luật</td>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Lý Do</td>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Hình Thức</td>
                      </tr>
                      <?php
												while($rowKl=mysqli_fetch_array($ResultKl, MYSQLI_ASSOC)){
											?>
                      <tr>
                        <td align="center"><?php echo date_format(date_create($rowKl["nhanvienkyluat_ngay"]),"d/m/Y");?></td>
                        <td><?php echo $rowKl["nhanvienkyluat_ten"]; ?></td>
                        <td align="right"><?php echo number_format($rowKl["nhanvienkyluat_hinhthuc"],0,",","."); ?> VNĐ&nbsp;</td>
                      </tr>
                      <?php }?>
                      <tr>
                        <td colspan="3" align="center"><form action="" method="post" enctype="multipart/form-data" id="formKyLuat">
                        <table width="100%" border="1">
                          <tr>
                            <td><input name="txtViPham" required type="text" class="form-control" placeholder="Nhập vi phạm" id="txtViPham" /></td>
                            <td><input name="txtHinhThucKL" required type="number" max="0" class="form-control" placeholder="Hình thức kỷ luật" id="txtHinhThucKL" /></td>
                            <td align="center"><input name="btnLuuKL" class="btn btn-success" type="submit" id="btnLuuKL" value="Lưu" onClick="return KlConfirm()"/></td>
                          </tr>
                        </table>
                        </form></td>
                      </tr>
                    </table>
                    <span id="KyLuat1"><?php echo number_format($kyluat,0,",",".");?> VNĐ<img src="icon/icons8-edit-48.png" height="25" /></span>
                </td>
              </tr>
              <tr>
                <td>Ngày nghỉ:</td>
                <td>
                  	<table id="ngaynghi2" class="table-hover" width="100%" border="1">
                      <tr>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Thời gian</td>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Lý Do</td>
                        <td align="center" valign="middle" bgcolor="#FFCC66">Số ngày</td>
                      </tr>
                      <?php
												while($rowNn=mysqli_fetch_array($ResultNn, MYSQLI_ASSOC)){
											?>
                      <tr>
                        <td align="center"><?php echo date_format(date_create($rowNn["nhanvienngaynghi_ngay"]),"d/m/Y");?></td>
                        <td><?php echo $rowNn["nhanvienngaynghi_lydo"]; ?></td>
                        <td align="center"><?php echo number_format($rowNn["nhanvienngaynghi_songay"],1,",","."); ?></td>
                      </tr>
                      <?php }?>
                      <tr>
                        <td colspan="3" align="center"><form action="" method="post" enctype="multipart/form-data" id="formNgayNghi">
                          <table width="100%" border="1">
                            <tr>
                              <td><input name="txtLydo" required type="text" class="form-control" placeholder="Nhập lý do nghĩ" id="txtLydo" /></td>
                              <td><input name="txtsongay" required type="text" min="0" class="form-control" placeholder="số ngày nghĩ (0.5~buổi)" id="txtsongay" /></td>
                              <td align="center"><input name="btnLuuNn" class="btn btn-success" type="submit" id="btnLuuNn" value="Lưu" onClick="return NnConfirm()"/></td>
                            </tr>
                          </table>
                        </form></td>
                      </tr>
                    </table>
                    <span id="ngaynghi1"><?php echo number_format($NgayNghi,1,",",".");?> ngày<img src="icon/icons8-edit-48.png" height="25" /></span>
                </td>
              </tr>
              <tr>
                <td>Khen Thưởng:</td>
                <td>
                  <table id="KhenThuong2" class="table-hover" width="100%" border="1">
                    <tr>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Ngày Khen Thưởng</td>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Thành Tích</td>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Hình Thức</td>
                    </tr>
                    <?php
											while($rowKt=mysqli_fetch_array($ResultKt, MYSQLI_ASSOC)){
										?>
                    <tr>
                      <td align="center"><?php echo date_format(date_create($rowKt["nhanvienkhenthuong_ngay"]),"d/m/Y");?></td>
                      <td><?php echo $rowKt["nhanvienkhenthuong_ten"]; ?></td>
                      <td align="right"><?php echo number_format($rowKt["nhanvienkhenthuong_hinhthuc"],0,",",".");?> VNĐ&nbsp;</td>
                    </tr>
                    <?php }?>
                    <tr>
                      <td colspan="3" align="center"><form action="" method="post" enctype="multipart/form-data" id="formKhenThuong">
                        <table width="100%" border="1">
                          <tr>
                            <td><input name="txtThanhTich" required type="text" class="form-control" placeholder="Nhập thành tích" id="txtThanhTich" /></td>
                            <td><input name="txtHinhThuc" required type="number" min="0" class="form-control" placeholder="Hình thức khen thưởng" id="txtHinhThuc" /></td>
                            <td align="center"><input name="btnLuuKT" class="btn btn-success" type="submit" id="btnLuuKT" value="Lưu" onClick="return KtConfirm()" /></td>
                          </tr>
                        </table>
                      </form></td>
                    </tr>
                  </table>
                  <span id="KhenThuong1"><?php echo number_format($khenthuong,0,",",".");?> VNĐ<img src="icon/icons8-edit-48.png" height="25" /></span>
                </td>
              </tr>
              <tr>
                <td>Tạm ứng:</td>
                <td>
                  <form action="" method="post" enctype="multipart/form-data" id="formTamUng">
                  <table id="TamUng2" class="table-hover" width="100%" border="1">
                    <tr>
                      <td align="center"><input name="txtTamUng" type="number" min="1000" class="form-control" placeholder="Nhập số tiền tạm ứng" id="txtTamUng" /></td>
                      <td align="center"><input name="btnTamUng" class="btn btn-success" type="submit" id="btnTamUng" value="Thêm Tạm Ứng" onClick="return TuConfirm()" /></td>
                    </tr>
                  </table></form>
                  <span id="TamUng1"><?php if($rownv["nhanvien_tamung"]!=0)echo number_format($rownv["nhanvien_tamung"],0,",",".")." VNĐ ngày ".date_format(date_create($rownv["nhanvien_ngaytamung"]),"d/m/Y"); else echo '<img src="icon/icons8-edit-48.png" height="25" />';?></span>
                </td>
              </tr>
              <tr>
                <td>Số lương hiện tại:</td>
                <td>
                  <table id="Luong2" class="table-hover" width="100%" border="1">
                    <tr>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Ngày Thanh Toán</td>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Số Tiền (VNĐ)</td>
                      <td align="center" valign="middle" bgcolor="#FFCC66">Trạng Thái Thanh Toán</td>
                    </tr>
                    <?php
										while($rowluong=mysqli_fetch_array($Resultluong, MYSQLI_ASSOC)){
										?>
                    <tr>
                      <td align="center"><?php echo date_format(date_create($rowluong["nhanvienluong_ngaythanhtoan"]),"d/m/Y");?></td>
                      <td align="right"><?php echo number_format($rowluong["nhanvienluong_sotien"],0,",",".");?></td>
                      <td align="center">Đã thanh toán</td>
                    </tr>
                    <?php }?>
                    <tr>
                      <td align="center"><?php echo date("d/m/Y");?></td>
                      <td align="right"><?php  echo number_format($luong,0,",","."); ?></td>
                      <td align="center"><form method="post" enctype="multipart/form-data" id="formTTL"><input type="submit" name="btnTTLuong" id="btnTTLuong" class="btn btn-success" value="Thanh Toán" /></form></td>
                    </tr>
                    <?php 
											if(isset($_POST["btnTTLuong"])){
												mysqli_query($Conn,'INSERT INTO nhanvienluong (nhanvien_id, nhanvienluong_sotien, nhanvienluong_ngaythanhtoan) VALUES ('.$_GET["id"].','.$luong.',"'.date("Y-m-d H:i:s").'")') or die(mysqli_connect_error($Conn));
												mysqli_query($Conn,'UPDATE nhanvien SET nhanvien_tamung=NULL, nhanvien_ngaytamung=NULL Where nhanvien_id='.$_GET["id"]) or die(mysqli_connect_error($Conn));
												echo '<script>alert("Đã thanh toán lương!");</script>';
												echo '<meta	http-equiv="refresh" content="0;URL=employees.php?id='.$_GET["id"].'&action=detail" />';
											} 
										?>
                  </table>
                  <span id="Luong1"><?php  echo number_format($luong,0,",","."); ?> VNĐ<img src="icon/icons8-edit-48.png" height="25" /></span>
                </td>
              </tr>
              <tr>
              	<td colspan="2" align="center">
                	<input name="btnEdit" type="button" class="btn btn-success" id="btnEdit" value="Chỉnh Sửa" onclick="window.location='employees.php?id=<?php echo $_GET["id"];?>&action=update'" />
            			<a href="employees.php?id=<?php echo $_GET["id"];?>&action=delete"><input name="btnDelete" type="button" class="btn btn-warning" id="btnDelete" value="Xóa" onclick="return XoaNvConfirm()" /></a>
            			<input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='employees.php'"/>
                </td>
              </tr>
            </table>
          </div></div>
        </div>
      </div>
    </div>
    <?php include_once('footer.html')?>
  </div>
</body>
</html>
<script>
	$('#NvChiTiet').hide();
	$('#ThemNv').hide();
	$('#Luong2').hide();
	$('#Luong1 img').click(function(e) {
    $('#Luong1').hide();
		$('#Luong2').show();
  });
	$('#TamUng2').hide();
	$('#TamUng1 img').click(function(e) {
    $('#TamUng1').hide();
		$('#TamUng2').show();
  });
	$('#NgayNghi2').hide();
	$('#NgayNghi1 img').click(function(e) {
    $('#NgayNghi1').hide();
		$('#NgayNghi2').show();
  });
	$('#KhenThuong2').hide();
	$('#KhenThuong1 img').click(function(e) {
    $('#KhenThuong1').hide();
		$('#KhenThuong2').show();
  });
	$('#KyLuat2').hide();
	$('#KyLuat1 img').click(function(e) {
    $('#KyLuat1').hide();
		$('#KyLuat2').show();
  });
	$('#ngaynghi2').hide();
	$('#ngaynghi1 img').click(function(e) {
    $('#ngaynghi1').hide();
		$('#ngaynghi2').show();
  });
	<?php 
		if(isset($_GET['action'])&&$_GET['action']==="add") echo "$('#ThemNv').show();$('#DsNv').hide();";
		if(isset($_GET['id'])&&is_numeric($_GET['id'])&&$_GET['action']==="detail") echo "$('#NvChiTiet').show(); $('#DsNv').hide();";
		if(isset($_GET['id'])&&is_numeric($_GET['id'])&&$_GET['action']==="update") echo "$('#ThemNv').show(); $('#DsNv').hide();";
		if($Row_permiss['user_permissnhanvien']==2) echo "$('#linkAdd').hide(); $('#formKyLuat, #formNgayNghi, #formKhenThuong, #formTamUng, #btnTTLuong, #btnEdit, #btnDelete').hide();";
	?>
	//$('').hide();
	//Kiểm tra lỗi nhập liệu
	$('#btnLuu').click(function(e) {
		var loi="";
		if($('#slIdUser').val()=="Null")	loi+="1";
		if($('#slGioiTinh').val()=="Null")	loi+="1";
		if(loi!="") {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
	});
	function KlConfirm(){
		var loi="";
		if($("#txtViPham").val()==""||$("#txtHinhThucKL").val()=="")loi="1";
		if(loi!="") {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
		if(confirm("Chú ý: dữ liệu sau khi thêm không thể thay đổi hoặc xóa.\nBạn có chắc chắn muốn lưu ???")) return true;	
		else return false;
	}
	function NnConfirm(){
		var loi="";
		if($("#txtLydo").val()==""||$("#txtsongay").val()=="")loi="1";
		if(loi!="") {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
		if(confirm("Chú ý: dữ liệu sau khi thêm không thể thay đổi hoặc xóa.\nBạn có chắc chắn muốn lưu ???")) return true;	
		else return false;
	}
	function KtConfirm(){
		var loi="";
		if($("#txtThanhTich").val()==""||$("#txtHinhThuc").val()=="")loi="1";
		if(loi!="") {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
		if(confirm("Chú ý: dữ liệu sau khi thêm không thể thay đổi hoặc xóa.\nBạn có chắc chắn muốn lưu ???")) return true;	
		else return false;
	}
	function TuConfirm(){
		var loi="";
		if($("#txtTamUng").val()=="")loi="1";
		if(loi!="") {alert("Vui lòng điền đầy đủ thông tin vào biểu mẫu!");return false;}
		if(confirm("Chú ý: Chỉ có thể tạm ứng một lần cho lần thanh toán lương gần nhất và không thể chỉnh sửa.\nBạn có chắc chắn muốn lưu ???")) return true;	
		else return false;
	}
	function TTConfirm(){
		if(confirm("Chú ý: Chỉ có thể tạm ứng một lần cho lần thanh toán lương gần nhất và không thể chỉnh sửa.\nBạn có chắc chắn muốn lưu ???")) return true;	
		else return false;
	}
	function XoaNvConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa nhân viên này???")) return true;	
		else return false;
	}
</script>
<?php }}?>