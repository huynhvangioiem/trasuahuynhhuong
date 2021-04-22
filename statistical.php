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
	}else{/*2*/ if($Row_permiss['user_permissthongke']==1){
		$Result_thongke=mysqli_query($Conn,'SELECT * FROM thongke  WHERE thongke_id=1') or die(mysqli_connect_error($Conn));
		$Row_thongke=mysqli_fetch_array($Result_thongke,MYSQLI_ASSOC);
		$DanhMuc=$Row_thongke["thongke_danhmuc"];
		$TuNgay=$Row_thongke["thongke_tu"];
		$DenNgay=$Row_thongke["thongke_den"];
		if(isset($_POST["btnTk"])){
			$DanhMuc=$_POST["danhmuc"];
			$TuNgay=$_POST["txtTimeIn"];
			$DenNgay=$_POST["txtTimeOut"];
			mysqli_query($Conn,'UPDATE thongke SET thongke_danhmuc="'.$DanhMuc.'",thongke_tu="'.$TuNgay.'",thongke_den="'.$DenNgay.'" WHERE thongke_id=1') or die(mysqli_connect_error($Conn));
		}
		if($DanhMuc=="CP")$Result_chiphi=mysqli_query($Conn,'SELECT chiphi.*, nhanvien.nhanvien_ten, chiphi_soluong*chiphi_dongia AS thanhtien FROM chiphi LEFT JOIN nhanvien ON chiphi.user_id=nhanvien.user_id WHERE chiphi.chiphi_ngay>="'.$TuNgay.'" and chiphi.chiphi_ngay<="'.$DenNgay.'"') or die(mysqli_connect_error($Conn));
		if($DanhMuc=="DS")$Result_doanhso=mysqli_query($Conn,'SELECT hoadon.*, nhanvien.nhanvien_ten FROM hoadon LEFT JOIN nhanvien ON hoadon.user_id=nhanvien.user_id WHERE hoadon.hoadon_ngay>="'.$TuNgay.'" and hoadon.hoadon_ngay<="'.$DenNgay.'" ORDER BY hoadon_ngay ASC, hoadon_so ASC') or die(mysqli_connect_error($Conn));
		if($DanhMuc=="LN"){$Result_loinhuanngay=mysqli_query($Conn,'SELECT hoadon_ngay AS ngayloinhuan FROM hoadon WHERE hoadon_ngay>="'.$TuNgay.'" and hoadon_ngay<="'.$DenNgay.'" UNION SELECT chiphi_ngay FROM chiphi WHERE chiphi_ngay>="'.$TuNgay.'" and chiphi_ngay<="'.$DenNgay.'" UNION SELECT DATE(nhanvienluong_ngaythanhtoan) FROM nhanvienluong WHERE nhanvienluong_ngaythanhtoan>="'.$TuNgay.'" and nhanvienluong_ngaythanhtoan<="'.$DenNgay.'" ORDER BY ngayloinhuan') or die(mysqli_connect_error($Conn));}
	}
?>
<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Thống Kê</title>
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
	<div class="row content_statistical ">
		<div class="col-lg-12 title">Thống Kê</div>
		<div class="col-lg-12 title_lv2">Lựa Chọn Thống Kê</div>
		<div class="col-lg-12" id="margin-bottom_5">
      <form action="" method="post" enctype="multipart/form-data" id="formThongKe">
				<div class="row input">
        	<div class="col-lg-2">Thống kê:</div>
        	<div class="col-lg-2"><div class="row">
          	<select class="form-control" name="danhmuc" id="danhmuc">
              <option value="Null">Chọn Danh Mục</option>
              <option <?php if($DanhMuc=="CP") echo "selected"?> value="CP">Thống kê chi phí</option>
              <option <?php if($DanhMuc=="DS") echo "selected"?> value="DS">Thống kê doanh số</option>
              <option <?php if($DanhMuc=="LN") echo "selected"?> value="LN">Thống kê lợi nhuận</option>
						</select>
          </div></div>
          <div class="col-lg-2">Từ ngày:</div>
          <div class="col-lg-2"><div class="row"><input name="txtTimeIn" required value="<?php echo $TuNgay ?>" type="date" class="form-control" id="txtTimeIn"></div></div>
          <div class="col-lg-2">Đến ngày:</div>
          <div class="col-lg-2"><div class="row"><input name="txtTimeOut" required value="<?php echo $DenNgay ?>" type="date" class="form-control" id="txtTimeOut"></div></div>
          <div class="col-lg-12 text-center">
          	<input name="btnTk" type="submit" class="btn btn-success" id="btnTk" value="Thống Kê">
            <input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='htql.php'">
         	</div>
      	</div>
      </form>
		</div>
		<div class="col-lg-12 title_lv2" id="infothongke">Thống kê <?php if(isset($DanhMuc)){if($DanhMuc=="CP") echo 'Chi Phí';if($DanhMuc=="DS") echo 'Doanh Số';if($DanhMuc=="LN") echo 'Lợi Nhuận';} ?> từ ngày <?php echo date_format(date_create($TuNgay),"d/m/Y"); ?> đến  <?php echo date_format(date_create($DenNgay),"d/m/Y"); ?></div>
		<div class="col-lg-12" id="ChiPhi" >
			<div class="row">
				<div class="table-responsive" id="margin-bottom_0">
					<table class="table table-hover" id="tableChiPhi"  width="100%" border="1">
            <thead>
              <tr class="TH">	
                <td>STT</td>
                <td>Tên Chi Phí</td>
                <td>Đơn Vị Tính</td>
                <td>Số Lượng</td>
                <td>Đơn Giá (VNĐ)</td>
                <td>Thành Tiền (VNĐ)</td>
                <td>Ngày Chi</td>
                <td>Người Chi</td>
                <td>Ghi Chú</td>
              </tr>
            </thead>
            <tbody>
            <?php 
							$stt=1;
							if(isset($Result_chiphi))
							while($Row_chiphi=mysqli_fetch_array($Result_chiphi,MYSQLI_ASSOC)){
						?>
              <tr>
                <td align="center">01</td>
                <td><?php echo $Row_chiphi["chiphi_ten"]; ?></td>
                <td align="center"><?php echo $Row_chiphi["chiphi_donvitinh"]; ?></td>
                <td align="center"><?php echo $Row_chiphi["chiphi_soluong"]; ?></td>
                <td align="right"><?php echo number_format($Row_chiphi["chiphi_dongia"],0,",","."); ?></td>
                <td align="right"><?php echo number_format($Row_chiphi["thanhtien"],0,",","."); ?></td>
                <td align="center"><?php echo date_format(date_create($Row_chiphi["chiphi_ngay"]),"d/m/Y"); ?></td>
                <td align="center"><?php echo $Row_chiphi["nhanvien_ten"]; ?></td>
                <td align="center">&nbsp;</td>
              </tr>
             <?php $stt++; }?> 
            </tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-12" id="DoanhSo">
			<div class="row">
				<div class="table-responsive" id="margin-bottom_0">
					<table class="table table-hover" id="tableDoanhSo"  width="100%" border="1">
						<thead><tr class="TH">	
							<td>STT</td>
							<td>Số Hóa Đơn</td>
							<td>Ngày Lập</td>
							<td>Số Bàn</td>
							<td>Giá Trị Hóa Đơn (VNĐ)</td>
							<td>Nhân Viên</td>
							<td>Xem Chi Tiết Hóa Đơn</td>
						</tr></thead>
						<tbody>
							<?php 
                $stt=1;
                if(isset($Result_doanhso))
                while($Row_doanhso=mysqli_fetch_array($Result_doanhso,MYSQLI_ASSOC)){
              ?>
              <tr>
                <td align="center"><?php echo $stt; ?></td>
                <td align="center"><?php echo $Row_doanhso["hoadon_so"]; ?></td>
                <td align="center"><?php echo date_format(date_create($Row_doanhso["hoadon_ngay"]),"d/m/Y"); ?></td>
                <td align="center"><?php echo $Row_doanhso["order_id"]; ?></td>
                <td align="right">
									<?php
										$Result_doanhsochitiet=mysqli_query($Conn,'SELECT menu.menu_id, menu.menu_gia*hoadonchitiet_soluong AS thanhtien FROM hoadonchitiet LEFT JOIN menu ON hoadonchitiet.menu_id=menu.menu_id WHERE hoadon_ngay="'.$Row_doanhso["hoadon_ngay"].'" and hoadon_so='.$Row_doanhso["hoadon_so"]) or die(mysqli_connect_error($Conn));
										$dsval=0;
										while($Row_doanhsochitiet=mysqli_fetch_array($Result_doanhsochitiet,MYSQLI_ASSOC)){
											$dsval+=$Row_doanhsochitiet["thanhtien"];
										}echo number_format($dsval-$Row_doanhso["hoadon_km"],0,",",".");
									?>
                </td>
                <td><?php echo $Row_doanhso["nhanvien_ten"]; ?></td>
                <td align="center"><a href="bill.php?ngay=<?php echo $Row_doanhso["hoadon_ngay"];?>&so=<?php echo $Row_doanhso["hoadon_so"];?>"><img src="icon/icons8-edit-48.png" width="30" height="30" /></a></td>
              </tr>
              <?php $stt++; }?> 
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-12" id="LoiNhuan">
			<div class="row">
				<div class="table-responsive" id="margin-bottom_0">
					<table class="table table-hover" id="tableLoiNhuan"	  width="100%" border="1">
							<thead><tr class="TH">	
							<td>STT</td>
							<td>Ngày</td>
							<td>Chi Phí (VNĐ)</td>
							<td>Lương Nhân Viên (VNĐ)</td>
							<td>Doanh Thu (VNĐ)</td>
							<td>Lợi Nhuận (VNĐ)</td>
							<td>Ghi Chú</td>
						</tr></thead>
						<tbody>
            <?php 
						$stt=1;
						$tongthu=0;
						$tongchi=0;
						$tongluong=0;
						while($Row_loinhuanngay=mysqli_fetch_array($Result_loinhuanngay,MYSQLI_ASSOC)){
							$Result_doanhthungay=mysqli_query($Conn,'SELECT menu_gia*hoadonchitiet_soluong AS thanhtien FROM hoadonchitiet LEFT JOIN menu ON hoadonchitiet.menu_id=menu.menu_id WHERE hoadon_ngay="'.$Row_loinhuanngay["ngayloinhuan"].'"') or die(mysqli_connect_error($Conn));
							$doanhthu=0;
							while($Row_doanhthungay=mysqli_fetch_array($Result_doanhthungay,MYSQLI_ASSOC)){
								$doanhthu+=$Row_doanhthungay["thanhtien"];
							}
							$Result_kmngay=mysqli_query($Conn,'SELECT hoadon_km FROM hoadon WHERE hoadon_ngay="'.$Row_loinhuanngay["ngayloinhuan"].'"') or die(mysqli_connect_error($Conn));
							while($Row_kmngay=mysqli_fetch_array($Result_kmngay,MYSQLI_ASSOC)){
								$doanhthu-=$Row_kmngay["hoadon_km"];
							}
							$tongthu+=$doanhthu;
							$Result_chiphingay=mysqli_query($Conn,'SELECT chiphi_soluong*chiphi_dongia AS thanhtien FROM chiphi WHERE chiphi_ngay="'.$Row_loinhuanngay["ngayloinhuan"].'"') or die(mysqli_connect_error($Conn));
							$chiphi=0;
							while($Row_chiphingay=mysqli_fetch_array($Result_chiphingay,MYSQLI_ASSOC)){
								$chiphi+=$Row_chiphingay["thanhtien"];
							}
							$tongchi+=$chiphi;
							$Result_ttluong=mysqli_query($Conn,'SELECT nhanvienluong_sotien FROM nhanvienluong WHERE DATE(nhanvienluong_ngaythanhtoan)="'.$Row_loinhuanngay["ngayloinhuan"].'"') or die(mysqli_connect_error($Conn));
							$Luong=0;
							while($Row_ttluong=mysqli_fetch_array($Result_ttluong,MYSQLI_ASSOC)){
								$Luong+=$Row_ttluong["nhanvienluong_sotien"];
							}
							$tongluong+=$Luong;
						?>
              <tr>
                <td align="center"><?php echo $stt; ?></td>
                <td align="center"><?php echo date_format(date_create($Row_loinhuanngay["ngayloinhuan"]),"d/m/Y"); ?></td>
                <td align="right"><?php  if(is_numeric($chiphi))echo number_format($chiphi,0,",","."); ?></td>
                <td align="right"><?php  if(is_numeric($chiphi))echo number_format($Luong,0,",","."); ?></td>
                <td align="right"><?php  if(is_numeric($doanhthu))echo number_format($doanhthu,0,",","."); ?></td>
                <td align="right"><?php echo number_format($doanhthu-$chiphi-$Luong,0,",","."); ?></td>
                <td align="center">&nbsp;</td>
              </tr>
            <?php $stt++;}?>
            </tbody>
						<tr>
							<td colspan="2" align="center" bgcolor="#FF9900"><strong>Tổng cộng</strong></td>
							<td align="right" bgcolor="#FF9900"><strong><?php echo number_format($tongchi,0,",","."); ?></strong></td>
							<td align="right" bgcolor="#FF9900"><strong><?php echo number_format($tongluong,0,",","."); ?></strong></td>
							<td align="right" bgcolor="#FF9900"><strong><?php echo number_format($tongthu,0,",","."); ?></strong></td>
							<td align="right" bgcolor="#FF9900"><strong><?php echo number_format($tongthu-$tongchi-$tongluong,0,",","."); ?></strong></td>
							<td align="center" bgcolor="#FF9900">&nbsp;</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php include_once('footer.html')?>
</div>
</body>
</html>
<script>
	$(document).ready(function(e) {
		var table = $("#tableChiPhi, #tableDoanhSo, #tableLoiNhuan ").DataTable({
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
		$("#infothongke").hide();
		$("#ChiPhi").hide();
		$("#DoanhSo").hide();
		$("#LoiNhuan").hide();
		$('#btnTk').click(function(e) {
			var loi="";
			if($('#danhmuc').val()=="Null")	loi+="Vui lòng chọn danh mục thống kê!\n";
			if($('#txtTimeIn').val()>$('#txtTimeOut').val())	loi+="Ngày không hợp lệ, vui lòng kiểm tra lại!\n";
			if(loi!="") {alert(loi);return false;}
    });
		<?php 
			if(isset($DanhMuc)){
				echo '$("#infothongke").show();';
				if($DanhMuc=="CP") echo '$("#ChiPhi").show();';
				if($DanhMuc=="DS") echo '$("#DoanhSo").show();';
				if($DanhMuc=="LN") echo '$("#LoiNhuan").show();';
			}
		?>
	});
</script>
<?php }} ?>