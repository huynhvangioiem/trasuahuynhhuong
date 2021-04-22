<?php
	session_start();
	date_default_timezone_set('asia/ho_chi_minh');
	if(!isset($_SESSION["user_id"])) {
		echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
	}
	include_once('connect.php');
	if(isset($_GET['ngay'])&&isset($_GET['so'])){
		//truy xuất dữ liệu hoadon
		$Result_hoadon=mysqli_query($Conn,'SELECT hoadon.*, ordertable.order_khu, ordertable.order_soban FROM hoadon  LEFT JOIN ordertable ON hoadon.order_id=ordertable.order_id  WHERE hoadon.hoadon_ngay="'.$_GET['ngay'].'" and hoadon.hoadon_so='.$_GET["so"])or die(mysqli_connect_error($Conn));
		$Row_hoadon=mysqli_fetch_array($Result_hoadon,MYSQLI_ASSOC);
		//truy xuất dữ liệu chitiet
		$Result_hoadonchitiet = mysqli_query($Conn,'SELECT hoadonchitiet.*, menu.menu_ten,  menu.menu_gia,  menu_gia*hoadonchitiet_soluong AS thanhtien FROM hoadonchitiet INNER JOIN menu ON hoadonchitiet.menu_id=menu.menu_id WHERE hoadonchitiet.hoadon_ngay="'.$_GET['ngay'].'" and hoadonchitiet.hoadon_so='.$_GET["so"]) or die(mysqli_connect_error($Conn));
	}else {
		echo '<meta http-equiv="refresh" content="0;URL=order.php?table=1A" />';
	}
	if(!isset($_GET["bill"]))echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$Row_hoadon["order_id"].'" />';
	else echo '<meta http-equiv="refresh" content="0;URL=bill.php?ngay='.$_GET["ngay"].'&so='.$_GET["so"].'" />';
?>
<!DOCTYPE html ><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>In Hóa Đơn</title>
</head>

<style>
	body {
		margin:0px;
		font: 14pt "Tohoma";
	}
	.page {
		width: 78.5mm;
		overflow:hidden;
		margin-left:auto;
		margin-right:auto;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	}
	.footer{
		page-break-after:always;
	}
}
@media print {
	.footer{
		page-break-after:always;
	}
}
.company,.address,.title,.footer{
	text-align:center;
	font-size:17px;
}
.border-bottom{
	border-bottom:2px #999999 dashed;
}
.border-top{
	border-top:2px #999999 dashed;
}
.page:after{
	display:none;
}
</style>
<body onload="window.print()">
  <div id="page" class="page">
  	<div class="header">
    	<div class="company"><strong>TRÀ SỮA - MÌ CAY HUỲNH HƯƠNG</strong></div>
      <div class="address"><em>TT. An Châu, Châu Thành, An Giang</em></div>
    </div><br/>
    <div class="title"><strong>PHIẾU THANH TOÁN</strong><br />----------<?php echo $Row_hoadon["hoadon_so"]; ?>----------</div>
    <div class="content">
    	<table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>Khu:</td>
          <td><?php echo $Row_hoadon["order_khu"]; ?>&nbsp;</td>
          <td>Giờ vào: </td>
          <td align="right"><?php echo $Row_hoadon["hoadon_giovao"]; ?></td>
        </tr>
        <tr>
          <td>Bàn:</td>
          <td><?php echo $Row_hoadon["order_soban"]; ?></td>
          <td>Giờ ra: </td>
          <td align="right"><?php echo $Row_hoadon["hoadon_giora"]; ?></td>
        </tr>
      </table>
    	<table width="100%" border="0" cellpadding="0">
        <tr >
          <th class="border-bottom">TT</th>
          <th class="border-bottom">Tên Món</th>
          <th class="border-bottom">SL</th>
          <th class="border-bottom">Đ.Giá</th>
          <th class="border-bottom">T.Tiền</th>
        </tr>
        <?php
					$Result_KM = mysqli_query($Conn, 'SELECT * FROM khuyenmai WHERE khuyenmai_batdau<"'.$Row_hoadon["hoadon_giovao"].'" and khuyenmai_ketthuc>"'.$Row_hoadon["hoadon_giovao"].'" ORDER BY khuyenmai_giatri DESC LIMIT 1');
					$Row_KM=mysqli_fetch_array($Result_KM, MYSQLI_ASSOC);
					$KM=0;
					$stt=1;$tongtien=0; while($Row_hoadonchitiet=mysqli_fetch_array($Result_hoadonchitiet,MYSQLI_ASSOC)){
						$Result_DTKM = mysqli_query($Conn, 'SELECT * FROM doituongkm WHERE khuyenmai_id='.$Row_KM["khuyenmai_id"]);
						while($Row_DTKM=mysqli_fetch_array($Result_DTKM, MYSQLI_ASSOC)){
						 if($Row_DTKM["menu_id"]==$Row_hoadonchitiet['menu_id'])$KM+=($Row_KM["khuyenmai_giatri"]*$Row_hoadonchitiet['thanhtien'])/100;
						 if($Row_DTKM["menu_id"]==0)$KM+=($Row_hoadonchitiet['thanhtien']*$Row_KM["khuyenmai_giatri"])/100;
						}
				?>
        <tr>
          <td align="center"><?php echo $stt; ?></td>
          <td><?php echo $Row_hoadonchitiet["menu_ten"]." ".$Row_hoadonchitiet["hoadonchitiet_yeucau"]; ?></td>
          <td align="center"><?php echo $Row_hoadonchitiet["hoadonchitiet_soluong"]; ?></td>
          <td align="right"><?php echo number_format($Row_hoadonchitiet['menu_gia'],0,",","."); ?></td>
          <td align="right"><?php echo number_format($Row_hoadonchitiet['thanhtien'],0,",","."); ?></td>
        </tr>
        <?php $stt++;$tongtien+=$Row_hoadonchitiet['thanhtien'];}?>
        <tr>
          <td class="border-top" colspan="2">Tổng cộng:</td>
          <td class="border-top" colspan="3" align="right"><?php echo number_format($tongtien,0,",","."); ?></td>
        </tr>
        <tr>
          <td colspan="2">Khuyến mãi:</td>
          <td colspan="3" align="right"><?php echo number_format($KM,0,",",".");?></td>
        </tr>
         <tr>
          <td colspan="2">Số tiền phải trả:</td>
          <td colspan="3" align="right"><strong><?php echo number_format($tongtien-$KM,0,",","."); ?></strong></td>
        </tr>
        <tr>
          <td colspan="2">Số tiền khách đưa:</td>
          <td colspan="3" align="right"><?php echo number_format($Row_hoadon["hoadon_sotien"],0,",","."); ?></td>
        </tr>
        <tr>
          <td colspan="2">Số tiền thói lại:</td>
          <td colspan="3" align="right"><strong><?php echo number_format($Row_hoadon["hoadon_sotien"]-($tongtien-$KM),0,",","."); ?></strong></td>
        </tr>
      </table>
      <div class="footer">
      	<strong>Cảm Ơn Quý Khách!<br />
      	Hẹn Gặp Lại Quý Khách Lần Sau!</strong><br>
        <?php echo 'Ngày in: '.date("H:i:s d/m/Y"); ?><br>
        <em style="font-size:10px">Designed and developed by TLAIT.COM</em><br>
      </div>
    </div>
</div>
</body>
</html>
<?php mysqli_query($Conn,'UPDATE hoadon SET hoadon_km='.$KM.' WHERE hoadon_ngay="'.$_GET['ngay'].'" and hoadon_so='.$_GET["so"])or die(mysqli_connect_error($Conn));?>