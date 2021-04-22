<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permissmenu  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permissmenu']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else if($Row_permiss['user_permissmenu']==1){
		if(isset($_GET["action"])&&isset($_GET["id"])){
			if(($_GET["action"]=="update"||$_GET["action"]=="delete")&&is_numeric($_GET["id"])){
				//CapNhat
				if($_GET["action"]=="update"){
					$Result_menu = mysqli_query($Conn,"Select * From menu Where menu_id=".$_GET["id"]);
					$Row_menu=mysqli_fetch_array($Result_menu,MYSQLI_ASSOC);
					if(isset($_POST['btnLuu'])){
						$Ten=$_POST['txtTenMon'];
						$Gia=$_POST['txtDonGia'];
						$Loai=$_POST['txtLoai'];
						$MoTa=$_POST['txtMoTa'];
						$NgayThem=date("Y-m-d");
						$HinhAnh=$_FILES["fileHinhAnh"];
						$TenAnh =$HinhAnh['name'];
						if($TenAnh==""){
							mysqli_query($Conn,"UPDATE menu  set menu_ten='$Ten', menu_gia=$Gia, menu_loai=$Loai, menu_mota='$MoTa', menu_ngaythem='$NgayThem' where menu_id=".$_GET["id"]) or die(mysqli_connect_error());
							echo '<script>alert("Chỉnh sửa menu thành công!");</script>';
							echo '<meta http-equiv="refresh" content="0;URL=menu.php" />';
						}else {
							if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
								if($Row_menu['menu_hinhanh']!=""){
									$tenXoa="imgmenu/".$Row_menu['menu_hinhanh'];
									unlink("$tenXoa");
								}
								copy ($HinhAnh['tmp_name'],"imgmenu/".$TenAnh);
								mysqli_query($Conn,"UPDATE menu  set menu_ten='$Ten', menu_gia=$Gia, menu_loai=$Loai, menu_mota='$MoTa', menu_hinhanh='$TenAnh', menu_ngaythem='$NgayThem' where menu_id=".$_GET["id"]) or die(mysqli_connect_error());
								echo '<script>alert("Chỉnh sửa menu thành công!");</script>';
								echo '<meta http-equiv="refresh" content="0;URL=menu.php" />';
							}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
						}
					}
				}
				//Xóa
				else {
					$Result = mysqli_query($Conn,"Select  menu_hinhanh From menu Where menu_id=".$_GET["id"]);
					$Row=mysqli_fetch_array($Result,MYSQLI_ASSOC);
					if($Row['menu_hinhanh']!="no_img.png"){
						$tenXoa="imgmenu/".$Row['menu_hinhanh'];
						unlink("$tenXoa");
					}
					mysqli_query($Conn,"Delete From menu where menu_id=".$_GET["id"]);
					echo '<script>alert("Xóa menu thành công!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=menu.php" />';
				}
			}else echo '<meta http-equiv="refresh" content="0;URL=menu.php" />';
		}
		//Them
		else {
			if(isset($_POST['btnLuu'])){
				$Ten=$_POST['txtTenMon'];
				$Gia=$_POST['txtDonGia'];
				$Loai=$_POST['txtLoai'];
				$MoTa=$_POST['txtMoTa'];
				$NgayThem=date("Y-m-d");
				$HinhAnh=$_FILES["fileHinhAnh"];
				$TenAnh =$HinhAnh['name'];
				$Result = mysqli_query($Conn,"Select * From menu Where menu_ten='$Ten'") or die(mysqli_connect_error($Conn));
				if(mysqli_num_rows($Result)==0){
					if($TenAnh==""){ 
						mysqli_query($Conn,'INSERT INTO menu (menu_ten, menu_gia, menu_loai, menu_mota, menu_ngaythem) VALUES("'.$Ten.'", '.$Gia.', '.$Loai.', "'.$MoTa.'", "'.$NgayThem.'")') or die(mysqli_connect_error());
						echo '<script>alert("Thêm menu thành công!");</script>';
						echo '<meta	http-equiv="refresh" content="0;URL=menu.php" />';
					}else{
						if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
							copy ($HinhAnh['tmp_name'],"imgmenu/".$TenAnh);
							mysqli_query($Conn,'INSERT INTO menu (menu_ten, menu_gia, menu_loai, menu_mota, menu_hinhanh, menu_ngaythem) VALUES("'.$Ten.'", '.$Gia.', '.$Loai.', "'.$MoTa.'", "'.$TenAnh.'", "'.$NgayThem.'")') or die(mysqli_connect_error());
							echo '<script>alert("Thêm menu thành công!");</script>';
							echo '<meta	http-equiv="refresh" content="0;URL=menu.php" />';
						}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
					}
				}else echo '<script>alert("Tên món đã tồn tại.\nVui lòng thử lại!");</script>';
			}
		}
	}
	if($Row_permiss['user_permissmenu']==0){}else{//2
?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trà Sữa Huỳnh Hương - Menu</title>
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
	<?php include_once("banner.html")?>
	<div class="row content_menu">
		<div class="col-lg-12 title">Quản Lý Menu</div>
		<div class="col-lg-12 title_lv2" id="ThemMenu">Thêm Mới</div>
		<div class="col-lg-12 title_lv2" id="CapNhat">Cập Nhật</div>
		<div class="col-lg-12 text-center" id="margin-bottom_5">
			<div class="row input">
				<form action="" method="post" enctype="multipart/form-data" id="formMenu">
					<input name="txtTenMon" class="form-control" placeholder="Nhập tên món" required value="<?php if(isset($Row_menu["menu_ten"]))echo $Row_menu['menu_ten'];?>" type="text" id="txtTenMon">
					<input name="txtDonGia" class="form-control" placeholder="Nhập đơn giá" required value="<?php if(isset($Row_menu["menu_gia"]))echo $Row_menu['menu_gia'];?>" type="number" min="1000" id="txtDonGia">
          <input name="txtLoai" class="form-control" required placeholder="Nhập loại thực phẩm (1:Thức ăn/0:Nước uống)" value="<?php if(isset($Row_menu["menu_loai"]))echo $Row_menu['menu_loai'];?>" type="number" min="0" max="1" id="txtLoai">
					<input name="txtMoTa" class="form-control" placeholder="Nhập mô tả" value="<?php if(isset($Row_menu["menu_mota"]))echo $Row_menu['menu_mota'];?>" type="text" id="txtMoTa">
					<input name="fileHinhAnh" class="form-control" type="file" id="fileHinhAnh">
					<input name="btnLuu" type="submit" class="btn btn-success" id="btnLuu" value="Lưu">
					<input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='<?php if(isset($_GET['id'])) echo 'menu.php'; else echo 'htql.php'; ?>'">
				</form>
			</div>
		</div>
		<div class="col-lg-12 title_lv2">Danh Sách Các Món Trong Menu</div>
		<div class="col-lg-12">
			<div class="row">
				<div class="table-responsive" id="margin-bottom_0">
					<table class="table table-hover" id="tableMenu" width="100%" border="1">
						<thead>
            	<tr class="TH">
                <td>STT</td>
                <td>Tên Món</td>
                <td>Đơn Giá (VNĐ)</td>
                <td>Loại</td>
                <td>Mô Tả</td>
                <td>Hình Ảnh</td>
                <td class="edit">Chỉnh Sửa</td>
                <td class="delete">Xóa</td>
              </tr>
            </thead>
						<tbody>
            <?php 
							$result = mysqli_query($Conn, "SELECT * FROM menu");
							$stt=1;
							while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
						{
						?>
            	<tr>
                <td align="center"><?php echo $stt ?></td>
                <td><?php echo $row["menu_ten"] ?></td>
                <td align="center"><?php echo number_format($row["menu_gia"],0,",","."); ?></td>
                <td align="center"><?php if($row["menu_loai"]==1) echo "Thức ăn"; else echo "Nước uống";?></td>
                <td><?php echo $row["menu_mota"] ?></td>
                <td align="center"><a href="imgmenu/<?php if($row["menu_hinhanh"]!="")echo $row["menu_hinhanh"]; else echo "no_img.png";  ?>" target="_blank"><img src="imgmenu/<?php if($row["menu_hinhanh"]!="")echo $row["menu_hinhanh"]; else echo "no_img.png";  ?>" width="45"></a></td>
                <td class="edit" align="center"><a href="menu.php?id=<?php echo $row["menu_id"] ?>&action=update"><img src="icon/icons8-edit-48.png" width="40"></a></td>
                <td class="delete" align="center"><a href="menu.php?id=<?php echo $row["menu_id"] ?>&action=delete" onClick="return deleteConfirm()"><img src="icon/icons8-delete-forever-48.png" width="40"></a></td>
              </tr>
						<?php
              $stt++;
              }
            ?>
            </tbody>
					</table>
				</div>
			</div>
		</div>
    <div class="text-center"><input name="btnExit2" type="button" class="btn btn-warning" id="btnExit2" value="Trở Về" onclick="window.location='htql.php'"></div>
	</div>
	<?php include_once("footer.html")?>
</div>
</body>
</html>
<script>
	$("#btnExit2").hide()
	<?php if($Row_permiss['user_permissmenu']==2) echo '$("#formMenu").hide();$("#ThemMenu").hide();$("#btnExit2").show();$(".edit").hide();$(".delete").hide();';?>
	function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
	}
	$('#CapNhat').hide();
	<?php if(isset($_GET["action"])&&$_GET["action"]=="update") echo '$("#CapNhat").show(); $("#ThemMenu").hide();';?>
	$(document).ready(function(e) {
		var table = $("#tableMenu").DataTable({
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
			"lengthMenu":[[5,10,15,20,-1],[5,10,15,20,"Tất cả"]]	
		});
	});
</script>
<?php }}?>