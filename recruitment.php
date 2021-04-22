<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else{
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permisstuyendung  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permisstuyendung']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="2;URL=htql.php" />';
	}else{/*2*/ if($Row_permiss['user_permisstuyendung']==1){
		if(isset($_GET["action"])&&isset($_GET["id"])){
			if(($_GET["action"]=="update"||$_GET["action"]=="delete")&&is_numeric($_GET["id"])){
				//CapNhat
				if($_GET["action"]=="update"){
					$Result_tuyendung = mysqli_query($Conn,"Select * From tuyendung Where tuyendung_id=".$_GET["id"]);
					$Row_tuyendung=mysqli_fetch_array($Result_tuyendung,MYSQLI_ASSOC);
					if(isset($_POST['btnLuu'])){
						$Ten=$_POST['txtTieuDe'];
						$MoTa=$_POST['txtMoTa'];
						$File=$_FILES['fileTuyenDung'];
						$TenFile=$File['name'];
						$NgayDang=date("Y-m-d");
						if($Row_tuyendung['tuyendung_file']!=""){
									$tenXoa="files/".$Row_tuyendung['tuyendung_file'];
									unlink("$tenXoa");
								}
						copy ($File['tmp_name'],"files/".$TenFile);
						mysqli_query($Conn,"UPDATE tuyendung SET tuyendung_ten='$Ten', tuyendung_mota='$MoTa' ,tuyendung_file='$TenFile' , tuyendung_ngay='$NgayDang' WHERE tuyendung_id=".$_GET["id"]) or die(mysqli_connect_error());
						echo '<script>alert("Chỉnh sửa tuyển dụng thành công!");</script>';
						echo '<meta http-equiv="refresh" content="0;URL=recruitment.php" />';
					}
				}
				//Xóa
				else {
					$Result = mysqli_query($Conn,"Select tuyendung_ten, tuyendung_mota, tuyendung_file From tuyendung Where tuyendung_id=".$_GET["id"]);
					$Row=mysqli_fetch_array($Result,MYSQLI_ASSOC);
					if($Row['tuyendung_file']!=""){
						$tenXoa="files/".$Row['tuyendung_file'];
						unlink("$tenXoa");
					}
					$Result = mysqli_query($Conn,"Delete From tuyendung where tuyendung_id=".$_GET["id"]);
					echo '<script>alert("Xóa tuyển dụng thành công!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=recruitment.php" />';
				}
			}
		}
		//Them
		else {
			if(isset($_POST['btnLuu'])){
				$Ten=$_POST['txtTieuDe'];
				$MoTa=$_POST['txtMoTa'];
				$File=$_FILES['fileTuyenDung'];
				$TenFile=$File['name'];
				$NgayDang=date("Y-m-d");
				copy ($File['tmp_name'],"files/".$TenFile);
				mysqli_query($Conn,"INSERT INTO tuyendung (tuyendung_ten, tuyendung_mota, tuyendung_file, tuyendung_ngay) VALUES ('$Ten', '$MoTa','$TenFile','$NgayDang')") or die(mysqli_connect_error());
				echo '<script>alert("Thêm tuyển dụng thành công!");</script>';
				echo '<meta	http-equiv="refresh" content="0;URL=recruitment.php" />';
			}
		}
	}
?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trà Sữa Huỳnh Hương - Tuyển Dụng</title>
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
	<div class="row content_recruitment">
    	<div class="col-md-12 title">Tin Tuyển Dụng</div>
        <div class="col-lg-12 title_lv2" id="Them">Thêm Mới</div>
		<div class="col-lg-12 title_lv2" id="CapNhat">Cập Nhật</div>
        <div class="col-md-12 input" id="margin-bottom_5">
        	<form action="" method="post" enctype="multipart/form-data" name="formTuyenDung" id="formTuyenDung">
				<div class="row"><input name="txtTieuDe" type="text" required class="form-control" id="txtTieuDe" placeholder="Tiêu đề tuyển dụng" value="<?php if(isset($Row_tuyendung["tuyendung_ten"]))echo $Row_tuyendung['tuyendung_ten'];?>"/></div>
				<div class="row"><input name="txtMoTa" required type="text" id="txtMoTa" placeholder="Mô tả" class="form-control" value="<?php if(isset($Row_tuyendung["tuyendung_mota"]))echo $Row_tuyendung['tuyendung_mota'];?>"/></div>
				<div class="row"><input name="fileTuyenDung" type="file" id="fileTuyenDung" class="form-control"/></div>
				<div class="row text-center">
					<input type="submit"  class="btn btn-success" name="btnLuu" id="btnLuu" value="Lưu"/>
					<input type="button" class="btn btn-warning" name="btnTroVe"  id="btnTroVe" value="Trở Về" onclick="window.location='<?php if(isset($_GET['id'])) echo 'recruitment.php'; else echo 'htql.php' ?>'"/>
				</div>
			</form>
        </div>
        <div class="col-md-12 title_lv2">Tin Tuyển Dụng</div>
        <div class="col-md-12">
        	<div class="row">
          	<div class="table-responsive">
            	<table width="100%" class="table table-hover" id="tableTuyenDung" border="1">
              	<thead>
                  <tr class="TH">
                    <td>STT</td>
                    <td>Tiêu Đề</td>
                    <td>Mô Tả</td>
                    <td>Tập Tin</td>
                    <td>Ngày Đăng</td>
                    <td class="edit">Chỉnh Sửa</td>
                    <td class="delete">Xóa</td>
                  </tr>
                </thead>
                <tbody>
                <?php
					$result=mysqli_query($Conn,"SELECT * FROM tuyendung");
					$stt=1;
					while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				?>
                  <tr>
                    <td align="center"><?php echo $stt; ?></td>
                    <td><?php echo $row['tuyendung_ten']; ?></td>
                    <td><?php echo $row['tuyendung_mota']; ?></td>
                    <td><a href="files/<?php echo $row['tuyendung_file']; ?>" target="_blank"><?php echo $row['tuyendung_file']; ?></td>
                    <td align="center"><?php echo $row['tuyendung_ngay']; ?></td>
                    <td class="edit" align="center"><a href="?id=<?php echo $row['tuyendung_id'] ?>&action=update"><img src="icon/icons8-edit-48.png" width="40"></a></td>
                    <td class="delete" align="center"><a href="?id=<?php echo $row['tuyendung_id'];  ?>&action=delete"  onClick="return deleteConfirm()"><img src="icon/icons8-delete-forever-48.png" width="40"></a></td>
                  </tr>
                  <?php $stt++; }?>
                </tbody>
              </table>
           	</div>
           	<div class="text-center"><input type="button" class="btn btn-warning" name="btnTroVe2"  id="btnTroVe2" value="Trở Về" onclick="window.location='htql.php'"/></div>
          </div>
        </div>
    </div>  
    <?php include_once("footer.html")?>
  </div>
</body>
</html>
<script>
function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
	}
	$('#CapNhat, #btnTroVe2').hide();
	<?php 
	    if(isset($_GET["action"])&&$_GET["action"]=="update") echo '$("#CapNhat").show(); $("#Them").hide();';
	    if($Row_permiss['user_permisstuyendung']==2) echo '$("#Them, #formTuyenDung, .edit, .delete").hide(); $("#btnTroVe2").show();';
	
	?>
		$(document).ready(function(e) {
			var table = $("#tableTuyenDung").DataTable({
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
<?php }}?>