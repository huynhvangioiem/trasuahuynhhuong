<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permissthongke, user_loai  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permissthongke']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else if($Row_permiss['user_permissthongke']==1){
		if(!isset($_GET['ngay'])&&!isset($_GET['so']))
			echo '<meta http-equiv="refresh" content="0;URL=statistical.php" />';
		else {//3
			//truy xuất dữ liệu hoadon
			$Result_hoadon=mysqli_query($Conn,'SELECT hoadon.*, ordertable.order_khu, ordertable.order_soban, nhanvien.nhanvien_ten FROM hoadon  LEFT JOIN ordertable ON hoadon.order_id=ordertable.order_id LEFT JOIN nhanvien ON hoadon.user_id=nhanvien.user_id WHERE hoadon.hoadon_ngay="'.$_GET['ngay'].'" and hoadon.hoadon_so='.$_GET["so"])or die(mysqli_connect_error($Conn));
			$Row_hoadon=mysqli_fetch_array($Result_hoadon,MYSQLI_ASSOC);
			//truy xuất dữ liệu chitiet
			$Result_hoadonchitiet = mysqli_query($Conn,'SELECT hoadonchitiet.*, menu.menu_ten,  menu.menu_gia, menu_gia*hoadonchitiet_soluong AS thanhtien FROM hoadonchitiet INNER JOIN menu ON hoadonchitiet.menu_id=menu.menu_id WHERE hoadonchitiet.hoadon_ngay="'.$_GET['ngay'].'" and hoadonchitiet.hoadon_so='.$_GET["so"]) or die(mysqli_connect_error($Conn));
?>
<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Hóa Đơn Chi Tiết</title>
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
	<div class="row content_bill ">
		<div class="col-lg-12 title">Hóa Đơn Chi Tiết</div>
		<div id="TTKH">
			<div class="col-lg-2"><div class="row">Khu:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["order_khu"]; ?></div></div>
			<div class="col-lg-2"><div class="row">Số lượng khách:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["hoadon_slkh"]; ?></div></div>
			<div class="col-lg-2"><div class="row">Giờ vào:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["hoadon_giovao"]; ?></div></div>
			<div class="col-lg-2"><div class="row">Bàn:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["order_soban"]; ?></div></div>
			<div class="col-lg-2"><div class="row">Nhân Viên:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["nhanvien_ten"]; ?></div></div>
			<div class="col-lg-2"><div class="row">Giờ ra:</div></div>
			<div class="col-lg-2"><div class="row"><?php echo $Row_hoadon["hoadon_giora"]; ?></div></div>
		</div>
		<div class="col-lg-12" id="tablebill">
			<div class="row">
				<div class="table-responsive">
					<table class="table table-hover" id="tabledatabill" width="100%" border="1">
						<thead><tr class="TH">
							<td>STT</td>
							<td>Tên Món</td>
							<td>Yêu Cầu</td>
							<td>Số Lượng</td>
							<td>Đơn Giá (VNĐ)</td>
							<td>Thành Tiền (VNĐ)</td>
						</tr></thead>
            <tbody>
            <?php
            	$stt=1;$tongtien=0; while($Row_hoadonchitiet=mysqli_fetch_array($Result_hoadonchitiet,MYSQLI_ASSOC)){ 
						?>
						<tr>
							<td align="center"><?php echo $stt; ?></td>
							<td><?php echo $Row_hoadonchitiet["menu_ten"]; ?></td>
							<td><?php echo $Row_hoadonchitiet["hoadonchitiet_yeucau"]; ?></td>
							<td align="center"><?php echo $Row_hoadonchitiet["hoadonchitiet_soluong"]; ?></td>
							<td align="right"><?php echo number_format($Row_hoadonchitiet['menu_gia'],0,",","."); ?></td>
							<td align="right"><?php echo number_format($Row_hoadonchitiet['thanhtien'],0,",","."); ?></td>
						</tr>
						<?php $stt++;$tongtien+=$Row_hoadonchitiet['thanhtien'];}?>
            </tbody>
						<tr>
						  <td colspan="2" align="left" bgcolor="#FF9900"><strong>Cộng Khoảng: <?php echo $stt-1; ?></strong></td>
						  <td colspan="2" align="right" bgcolor="#FF9900"><strong>Khuyến mãi: <?php echo number_format($Row_hoadon["hoadon_km"],0,",",".");?> VNĐ</strong></td>
						  <td align="right" bgcolor="#FF9900"><strong>Tổng cộng:</strong></td>
						  <td align="right" bgcolor="#FF9900"><strong><?php echo number_format($tongtien,0,",","."); ?></strong></td>
					  </tr>
					</table>
				</div>
			</div>
		</div>
    <div class="col-lg-12 text-center">
    	<input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='statistical.php'">
      <input name="btnIn" type="button" class="btn btn-success" id="btnIn" value="In Hóa Đơn" onclick="window.location='print.php?bill=1&ngay=<?php echo $_GET['ngay'];?>&so=<?php echo $_GET['so'];?>'">
    </div>
	</div>
	<?php include_once('footer.html')?>
</div>
</body>
</html>
<script>
$(document).ready(function(e) {
	var table = $("#tabledatabill").DataTable({
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
		"lengthMenu":[[10,20,-1],[10,20,"Tất cả"]]	
	});
});
</script>
<?php }}}?>