<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permisschiphi  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permisschiphi']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else{/*2*/ if($Row_permiss['user_permisschiphi']==1){
		if(isset($_GET["action"])&&isset($_GET["id"])){
			if(($_GET["action"]=="update"||$_GET["action"]=="delete")&&is_numeric($_GET["id"])){
				//update
				if($_GET["action"]=="update"){
					$Result_spending = mysqli_query($Conn,"Select * From chiphi Where chiphi_id=".$_GET["id"]);
					$Row_spending=mysqli_fetch_array($Result_spending,MYSQLI_ASSOC);
					if(isset($_POST['btnLuu'])){
						$Ten=$_POST['txtTenChiPhi'];
						$DonVi=$_POST['txtDonViTinh'];
						$DonGia=$_POST['txtDonGia'];
						$SoLuong=$_POST['txtSoLuong'];
						$NgayChi=date("Y-m-d");
						mysqli_query($Conn,"UPDATE chiphi set chiphi_ten='$Ten', chiphi_donvitinh='$DonVi', chiphi_soluong=$SoLuong, chiphi_dongia=$DonGia, chiphi_ngay='$NgayChi',user_id=".$_SESSION["user_id"]." WHERE chiphi_id=".$_GET['id']) or die(mysqli_connect_error());
								echo '<script>alert("Chỉnh sửa chi phí thành công!");</script>';
								echo '<meta http-equiv="refresh" content="0;URL=spending.php" />';
						}
				}
				//xoa
				else{
					$result=mysqli_query($Conn,"DELETE FROM chiphi WHERE chiphi_id=".$_GET['id']);
					echo '<script>alert("Xoá chi phí thành công!")</script>';
					echo '<meta http-equiv="refresh" content="0;URL=spending.php" />';
				}
			}else echo '<meta http-equiv="refresh" content="0;URL=spending.php" />';
		}else {
			//them
			if(isset($_POST['btnLuu'])){
				$Ten=$_POST['txtTenChiPhi'];
				$DonVi=$_POST['txtDonViTinh'];
				$DonGia=$_POST['txtDonGia'];
				$SoLuong=$_POST['txtSoLuong'];
				$NgayChi=date("Y-m-d");
				mysqli_query($Conn,"INSERT INTO chiphi(chiphi_ten, chiphi_donvitinh, chiphi_soluong, chiphi_dongia, chiphi_ngay, user_id) VALUES ('$Ten','$DonVi',$SoLuong,$DonGia,'$NgayChi',".$_SESSION["user_id"].")") or die(mysqli_connect_error());
				echo '<script>alert("Thêm chi phí thành công!");</script>';
				echo '<meta	http-equiv="refresh" content="0;URL=spending.php" />';
			}
		}
	}
?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trà Sữa Huỳnh Hương - Quản Lý Chi Phí</title>
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
	<div class="row content_spending">
    	<div class="col-md-12 title">Quản Lý Chi Phí</div>
        <div class="col-lg-12 title_lv2" id="ThemChiPhi">Thêm Mới</div>
		<div class="col-lg-12 title_lv2" id="CapNhat">Cập Nhật</div>
        <div class="col-md-12 input" id="margin-bottom_5">
        	<form action="" method="post" enctype="multipart/form-data" name="formChiPhi" id="formChiPhi">
				<div class="row"><input name="txtTenChiPhi" type="text" required class="form-control" id="txtTenChiPhi" placeholder="Nhập tên chi phí" value="<?php if(isset($Row_spending["chiphi_ten"]))echo $Row_spending['chiphi_ten'];?>" /></div>
				<div class="row"><input name="txtDonViTinh" required type="text" id="txtDonViTinh" placeholder="Nhập đơn vị tính"  class="form-control" value="<?php if(isset($Row_spending['chiphi_donvitinh'])) echo $Row_spending['chiphi_donvitinh']; ?>" /></div>
				<div class="row"><input name="txtSoLuong" required type="number" min="1" id="txtSoLuong" placeholder="Nhập số lượng" class="form-control" value="<?php if(isset($Row_spending['chiphi_soluong'])) echo $Row_spending['chiphi_soluong']; ?>"/></div>
        <div class="row"><input name="txtDonGia" required type="number" min="1" id="txtDonGia" placeholder="Nhập đơn giá"  class="form-control" value="<?php if(isset($Row_spending['chiphi_dongia'])) echo $Row_spending['chiphi_dongia']; ?>" /></div>
				<div class="row text-center">
					<input type="submit"  class="btn btn-success" name="btnLuu" id="btnLuu" value="Lưu"/>
					<input type="button" class="btn btn-warning" name="btnTroVe"  id="btnTroVe" value="Trở Về" onclick="window.location='<?php if(isset($_GET['id'])) echo 'spending.php'; else echo 'htql.php'; ?>'" />
				</div>
			</form>
        </div>
        <div class="col-md-12 title_lv2">Danh Sách Khoản Chi Phí</div>
        <div class="col-md-12">
        	<div class="row">
            <div class="table-responsive" id="margin-bottom_0">
              <table class="table table-hover" id="tableChiPhi"  width="100%" border="1">
                <thead><tr class="TH">	
                  <td>STT</td>
                  <td>Tên Chi Phí</td>
                  <td>Đơn Vị Tính</td>
                  <td>Số Lượng</td>
                  <td>Đơn Giá (VNĐ)</td>
                  <td>Thành Tiền (VNĐ)</td>
                  <td>Ngày Chi</td>
                  <td>Người Chi</td>
                  <td class="edit">Chỉnh Sửa</td>
                  <td class="delete">Xóa</td>
                </tr>
                </thead>
                <tbody>
                <?php
					$result=mysqli_query($Conn,"SELECT chiphi.*, nhanvien.nhanvien_ten, chiphi_soluong*chiphi_dongia AS thanhtien FROM chiphi LEFT JOIN nhanvien ON chiphi.user_id=nhanvien.user_id");
					$stt=1;
					while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				?>
                <tr>
                  <td align="center"><?php echo $stt; ?></td>
                  <td><?php echo $row['chiphi_ten']; ?></td>
                  <td align="center"><?php echo $row['chiphi_donvitinh']; ?></td>
                  <td align="center"><?php echo $row['chiphi_soluong']; ?></td>
                  <td align="center"><?php echo number_format($row['chiphi_dongia'],0,",","."); ?></td>
                  <td align="right"><?php echo number_format($row['thanhtien'],0,",","."); ?></td>
                  <td align="center"><?php echo date_format(date_create($row['chiphi_ngay']),'d/m/Y'); ?></td>
                  <td><?php echo $row['nhanvien_ten']; ?></td>
                  <td class="edit" align="center"><a href="?id=<?php echo $row['chiphi_id']; ?>&action=update"><img src="icon/icons8-edit-48.png" width="40" /></a></td>
                  <td class="delete" align="center"><a href="?id=<?php echo $row['chiphi_id']; ?>&action=delete"><img src="icon/icons8-delete-forever-48.png" width="40" onClick="return deleteConfirm()" /></a></td>
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
	<?php if($Row_permiss['user_permisschiphi']==2) echo '$("#formChiPhi").hide() ;$("#ThemChiPhi").hide() ;$("#btnExit2").show(); $(".edit").hide();$(".delete").hide();';?>
	function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
	}
	$('#CapNhat').hide();
	<?php if(isset($_GET["action"])&&$_GET["action"]=="update") echo '$("#CapNhat").show(); $("#ThemChiPhi").hide();'?>
		$(document).ready(function(e) {
			var table = $("#tableChiPhi").DataTable({
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
</script>
<?php }} ?>