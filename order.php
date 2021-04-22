<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//get Table
	if(isset($_GET['table'])){
		$table=$_GET['table'];
	}
	//truy xuất dữ liệu ordertable
	$Result_order = mysqli_query($Conn,'SELECT * FROM ordertable WHERE order_id="'.$table.'"') or die(mysqli_connect_error($Conn));
	$Row_order=mysqli_fetch_array($Result_order,MYSQLI_ASSOC);
	//truy xuất dữ liệu chitiet
	$Result_orderchitiet = mysqli_query($Conn,'SELECT * FROM orderchitiet WHERE order_id="'.$table.'"') or die(mysqli_connect_error($Conn));
	//getstatus
	$statusTT=0;
	while($Row_orderchitiet=mysqli_fetch_array($Result_orderchitiet,MYSQLI_ASSOC)){
		$statusTT=$Row_orderchitiet['orderchitiet_status'];
		if($statusTT!=3)break;
	}
	//Xác thực quyền truy cập
	$Result = mysqli_query($Conn,"SELECT user_permissoder, user_loai  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row=mysqli_fetch_array($Result,MYSQLI_ASSOC);
	if($Row['user_loai']==5) echo '<meta http-equiv="refresh" content="0;URL=bartender.php" />';
	if($Row['user_loai']==6) echo '<meta http-equiv="refresh" content="0;URL=cooking.php" />';
	if($Row['user_permissoder']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else if($Row['user_permissoder']==2){
		echo '<meta http-equiv="refresh" content="30;URL=order.php?table='.$table.'" />';
	}else {
		if($Row['user_loai']!=4) echo '<meta http-equiv="refresh" content="30;URL=order.php?table='.$table.'" />'; else echo '<meta http-equiv="refresh" content="100;URL=order.php?table='.$table.'" />';
		//Thêm thông tin khách hàng
		if(isset($_POST['btnLuuKh'])){
			$slkh=$_POST['txtSlKh'];
			$giovao=date("Y-m-d H:i:s");
			mysqli_query($Conn,'UPDATE ordertable SET order_status=1, user_id='.$_SESSION["user_id"].', order_slkh='.$slkh.', order_giovao="'.$giovao.'" where order_id="'.$table.'"') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
		}
		//GoiMon
		if($statusTT==0&&isset($_POST['btnGoiMon'])&&!isset($_GET['action'])&&$table!="6868"){
			mysqli_query($Conn,'INSERT INTO orderchitiet (order_id, menu_id, orderchitiet_yeucau, orderchitiet_soluong, orderchitiet_status) VALUES("'.$table.'",'.$_POST["RadioGroupMon"].',"'.$_POST["txtGhiChu"].'",'.$_POST["txtSoLuong"].',0)') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
		}
		if($statusTT==0&&isset($_POST['btnGoiMon'])&&!isset($_GET['action'])&&$table=="6868"){
			mysqli_query($Conn,'INSERT INTO orderchitiet (order_id, menu_id, orderchitiet_yeucau, orderchitiet_soluong, orderchitiet_status) VALUES("'.$table.'",'.$_POST["RadioGroupMon"].',"'.$_POST["txtGhiChu"].'",'.$_POST["txtSoLuong"].',3)') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
		}
		//guiorder
		if(isset($_GET['action'])&&$_GET['action']=="submitorder"){
			mysqli_query($Conn,'UPDATE orderchitiet SET orderchitiet_status=1 where order_id="'.$table.'"') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
		}
		//GoiThem
		if($statusTT!=0&&isset($_POST['btnGoiMon'])&&!isset($_GET['action'])){
			mysqli_query($Conn,'INSERT INTO orderchitiet (order_id, menu_id, orderchitiet_yeucau, orderchitiet_soluong, orderchitiet_status) VALUES("'.$table.'",'.$_POST["RadioGroupMon"].',"'.$_POST["txtGhiChu"].'",'.$_POST["txtSoLuong"].',1)') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
		}
		//ThanhToan
		if(isset($_POST['btnYcTT'])){
			$Result_bodem= mysqli_query($Conn,'SELECT * FROM bodem WHERE bodem_id=1') or die(mysqli_connect_error($Conn));
			$Row_bodem=mysqli_fetch_array($Result_bodem,MYSQLI_ASSOC);
			mysqli_query($Conn,'INSERT INTO hoadon (hoadon_ngay, hoadon_so, order_id, user_id, hoadon_slkh, hoadon_giovao, hoadon_giora, hoadon_sotien) VALUES ("'.date("Y-m-d").'",'.$Row_bodem["bodem_hoadonngay"].',"'.$Row_order["order_id"].'",'.$_SESSION["user_id"].','.$Row_order["order_slkh"].',"'.$Row_order["order_giovao"].'","'.date("Y-m-d H:i:s").'",'.$_POST["txtMoney"].')') or die(mysqli_connect_error());
			mysqli_query($Conn,'UPDATE bodem SET bodem_hoadonngay='.($Row_bodem["bodem_hoadonngay"]+1).' where bodem_id=1') or die(mysqli_connect_error());
			$Result_orderchitiet= mysqli_query($Conn,'SELECT * FROM orderchitiet WHERE order_id="'.$table.'"') or die(mysqli_connect_error($Conn));
			while($Row_orderchitiet=mysqli_fetch_array($Result_orderchitiet,MYSQLI_ASSOC)){
				mysqli_query($Conn,'INSERT INTO hoadonchitiet (hoadon_ngay, hoadon_so , menu_id, hoadonchitiet_yeucau, hoadonchitiet_soluong) VALUES ("'.date("Y-m-d").'",'.$Row_bodem["bodem_hoadonngay"].','.$Row_orderchitiet['menu_id'].',"'.$Row_orderchitiet['orderchitiet_yeucau'].'",'.$Row_orderchitiet['orderchitiet_soluong'].')') or die(mysqli_connect_error());
			}
			if($Row['user_loai']==3){
				mysqli_query($Conn,'DELETE FROM orderchitiet WHERE order_id="'.$table.'"') or die(mysqli_connect_error());
				mysqli_query($Conn,'UPDATE ordertable SET order_status=0, user_id=null, order_slkh=null, order_giovao=null where order_id="'.$table.'"') or die(mysqli_connect_error());
				echo '<meta http-equiv="refresh" content="0;URL=print.php?ngay='.date("Y-m-d").'&so='.$Row_bodem["bodem_hoadonngay"].'"/>';
			}else {
				mysqli_query($Conn,'UPDATE ordertable SET order_status=2 where order_id="'.$table.'"') or die(mysqli_connect_error());
				mysqli_query($Conn,'DELETE FROM orderchitiet WHERE order_id="'.$table.'"') or die(mysqli_connect_error());
				echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
			}
		}
		if(isset($_POST['btnThanhToanHD'])){
			$Result_hoadon = mysqli_query($Conn,'SELECT hoadon_so FROM hoadon WHERE order_id="'.$table.'" and hoadon_giovao="'.$Row_order["order_giovao"].'"') or die(mysqli_connect_error($Conn));
			$Row_hoadon=mysqli_fetch_array($Result_hoadon,MYSQLI_ASSOC);
			mysqli_query($Conn,'UPDATE ordertable SET order_status=0, user_id=null, order_slkh=null, order_giovao=null where order_id="'.$table.'"') or die(mysqli_connect_error());
			echo '<meta http-equiv="refresh" content="0;URL=print.php?ngay='.date("Y-m-d").'&so='.$Row_hoadon["hoadon_so"].'"/>';
		}
		//chỉnh sữa và xóa
		if(isset($_GET['action'])&&$_GET['action']!="addtable"){
			$Result_chitiet = mysqli_query($Conn,'SELECT * FROM orderchitiet WHERE orderchitiet_id='.$_GET['iddetail']) or die(mysqli_connect_error($Conn));
			$Row_chitiet=mysqli_fetch_array($Result_chitiet,MYSQLI_ASSOC);
			if($Row_chitiet['orderchitiet_status']!=0&&$Row_chitiet['orderchitiet_status']!=1){echo '<script>alert("Món này đã được chế biến.\nKhông thể thay đổi!");</script>';echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';}
			else if(($_GET['action'])=="edit"&&isset($_POST['btnGoiMon'])){
				mysqli_query($Conn,'UPDATE orderchitiet SET menu_id='.$_POST["RadioGroupMon"].', orderchitiet_yeucau="'.$_POST["txtGhiChu"].'", orderchitiet_soluong='.$_POST["txtSoLuong"].' WHERE orderchitiet_id='.$_GET['iddetail']) or die(mysqli_connect_error());
				echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
			}else if(($_GET['action'])=="delete"){
				mysqli_query($Conn,'DELETE FROM orderchitiet WHERE orderchitiet_id='.$_GET['iddetail']) or die(mysqli_connect_error());
				$Result_chitietdelete = mysqli_query($Conn,'SELECT * FROM orderchitiet WHERE order_id="'.$table.'"') or die(mysqli_connect_error($Conn));
				$Row_chitietdelete=mysqli_fetch_array($Result_chitietdelete,MYSQLI_ASSOC);
				if($Row_chitietdelete['menu_id']=="")mysqli_query($Conn,'UPDATE ordertable SET order_status=0, user_id=null, order_slkh=null, order_giovao=null where order_id="'.$table.'"') or die(mysqli_connect_error());
				echo '<meta http-equiv="refresh" content="0;URL=order.php?table='.$table.'" />';
			}
		}
	}
	if($Row['user_permissoder']==0){}else{//2
?>
<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Order</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="img/icontitle.jpg" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" /> <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body class="body">
	<div class="container">
  	<?php include_once('banner.html')?>
    <div class="row content_order ">
		<div class="col-lg-4 trai text-center">
			<div class="row title_lv1">Theo Dõi Trạng Thái Bàn</div>
			<div class="row title_lv2">Khu A</div>
			<div class="row">
				<table class="DsBan" width="100%" border="0">
        	<tr>
        	<?php
          	$result = mysqli_query($Conn, "SELECT * FROM ordertable where order_khu='A'");
						$stta=1;
						while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
					?>
					<td><input class="form-control btn <?php if($row["order_status"]==1) echo "btn-danger"; else if($row["order_status"]==2)echo "btnRequire"; else echo "btn-success"; ?>"name="btnban_<?php echo $row['order_id']; ?>"type="button"id="btnban_<?php echo $row['order_id']; ?>"value="Bàn <?php echo $row['order_soban']; ?>"onclick="window.location='?table=<?php echo $row['order_id']; ?>'"/></td>
          <?php
						if($stta%3==0) echo '</tr><tr>';
						$stta++;
						}
						if(isset($_GET['action'])&&$_GET['action']=="addtable"&&$_GET['campus']=="A"){
							$orderid=$stta."A";
							mysqli_query($Conn,"INSERT INTO ordertable (order_id, order_khu, order_soban, order_status) VALUES('$orderid', 'A', '$stta',0)") or die(mysqli_connect_error());
							echo '<meta http-equiv="refresh" content="0;URL=order.php?table=1A" />';
						}
          ?>
          <td><input class="form-control btn btn-primary"name="btnbanThemBanA"type="button" id="btnbanThemBanA"value="Thêm" onClick="return addTableAConfirm()"/></td>
					</tr>					
				</table>
			</div>
			<div class="row title_lv2">Khu B</div>
			<div class="row" id="margin-bottom_5">
				<table class="DsBan" width="100%" border="0">
					<tr>
        	<?php
          	$result = mysqli_query($Conn, "SELECT * FROM ordertable where order_khu='B'");
						$sttb=1;
						while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
					?>
					<td><input class="form-control btn <?php if($row["order_status"]==1) echo "btn-danger"; else if($row["order_status"]==2)echo "btnRequire"; else echo "btn-success"; ?>"name="btnban_<?php echo $row['order_id']; ?>"type="button"id="btnban_<?php echo $row['order_id']; ?>"value="Bàn <?php echo $row['order_soban']; ?>"onclick="window.location='?table=<?php echo $row['order_id']; ?>'"/></td>
          <?php
						if($sttb%3==0) echo '</tr><tr>';
						$sttb++;
						}
						if(isset($_GET['action'])&&$_GET['action']=="addtable"&&$_GET['campus']=="B"){
							$orderid=$sttb."B";
							mysqli_query($Conn,"INSERT INTO ordertable (order_id, order_khu, order_soban, order_status) VALUES('$orderid', 'B', '$sttb',0)") or die(mysqli_connect_error());
							echo '<meta http-equiv="refresh" content="0;URL=order.php?table=1A" />';
						}
          ?>
          <td><input class="form-control btn btn-primary"name="btnbanThemBanB"type="button" id="btnbanThemBanB"value="Thêm"onClick="return addTableBConfirm()"/></td>
					</tr>
				</table>
			</div>
      <div class="row"><input class="form-control btn btn-info"name="btnban_6868"type="button"id="btnban_6868"value="Khu vực bán lẻ"onclick="window.location='?table=6868'"/></div>
		</div>
		<div class="col-lg-8 phai">
			<div id="GoiMon">
				<div class="row title_lv1 text-center" id="margin-bottom_5">Gọi Món</div>
				<div class="row input">
					<form action="" method="post" enctype="multipart/form-data" id="formTTKh">
						<div class="col-lg-4"><div class="row"><input name="txtSlKh" type="number" required min="1" class="form-control" id="txtSlKh" placeholder="Nhập số lượng khách hàng" /></div></div>
						<div class="col-lg-5"><div class="row"><input name="txtVip" type="text" class="form-control" id="txtVip" placeholder="Nhập mã số khách hàng VIP nếu có" /></div></div>
						<div class="col-lg-3 text-center"><div class="row">
							<input name="btnLuuKh" type="submit" class="btn btn-success" id="btnLuuKh" value="Lưu" />
							<input name="btnReset" class="btn btn-warning" type="reset" id="btnReset" value="Nhập Lại" />
						</div></div>
					</form>
				</div>
				<div class="row" id="margin-bottom_5">
					<form action="" method="post" enctype="multipart/form-data" name="formGoiMon" id="formGoiMon">
          	<div style="overflow-x:auto; height:200px;">
            	<table width="100%" id="tableMon" class="table table-striped">
                <thead><tr class="TH"><td align="center" bgcolor="#99CC00">STT</td><td align="center" bgcolor="#99CC00">Chọn Món</td></tr></thead>
                <tbody>
                <?php 
								$result_mon = mysqli_query($Conn, "SELECT * FROM menu ORDER BY menu_loai ASC");
								$i=0;
								while($row_mon=mysqli_fetch_array($result_mon, MYSQLI_ASSOC)){
								?>
                	<tr>
                  	<td align="center"><?php echo $i;?></td>
                    <td><?php 
											if(isset($Row_chitiet['menu_id'])&&$Row_chitiet['menu_id']==$row_mon["menu_id"]) echo '<label>'.$row_mon["menu_ten"].' <input type="radio" name="RadioGroupMon" value="'.$row_mon["menu_id"].'" id="RadioGroupMon_'.$i.'" checked></label>';
											else echo '<label>'.$row_mon["menu_ten"].' <input type="radio" name="RadioGroupMon" value="'.$row_mon["menu_id"].'" id="RadioGroupMon_'.$i.'"></label>'; 
										?></td>
                  </tr>									
								<?php $i++;}?>
                </tbody>
              </table>
            </div>
            <div class="input">
              <input name="txtGhiChu"  class="form-control" value="<?php if(isset($Row_chitiet['orderchitiet_yeucau'])) echo $Row_chitiet['orderchitiet_yeucau'];?>" type="text" id="txtGhiChu" placeholder="Yêu cầu thêm của khách hàng" />
              <input name="txtSoLuong" required class="form-control" value="<?php if(isset($Row_chitiet['orderchitiet_soluong'])) echo $Row_chitiet['orderchitiet_soluong'];?>" type="number" min="1" id="txtSoLuong" placeholder="Số lượng"  />
            </div>
            <div class="text-center">
							<input name="btnGoiMon" type="submit" class="btn btn-success" id="btnGoiMon" value="Lưu" />
							<input name="btnReset" class="btn btn-warning" type="reset" id="btnReset" value="Hủy bỏ" />
						</div>
					</form>
				</div>
				<div class="row" id="margin-bottom_5">
        	<div class="title_lv2 text-center">Danh Sách Các Món Đã Gọi</div>
					<div class="table-responsive">
						<table width="100%" id="tableOrder" border="1" class="table table-hover">
							<thead>
              	<tr class="TH">
                  <td><strong>STT</strong></td>
                  <td><strong>Tên Món</strong></td>
                  <td><strong>Ghi Chú</strong></td>
                  <td><strong>Số Lượng</strong></td>
                  <td><strong>Đơn Giá (VNĐ)</strong></td>
                  <td><strong>Thành Tiền (VNĐ)</strong></td>
                  <td>Trạng Thái</td>
                  <td class="edit"><strong>Sửa</strong></td>
                  <td class="delete"><strong>Xóa</strong></td>
                </tr>
              </thead>
							<tbody>
<?php 
	$Result_KM = mysqli_query($Conn, 'SELECT * FROM khuyenmai WHERE khuyenmai_batdau<"'.date("Y-m-d H:i:s").'" and khuyenmai_ketthuc>"'.date("Y-m-d H:i:s").'" ORDER BY khuyenmai_giatri DESC LIMIT 1');
	$Row_KM=mysqli_fetch_array($Result_KM, MYSQLI_ASSOC);
	$result = mysqli_query($Conn, 'SELECT orderchitiet.*, menu.menu_ten,  menu.menu_gia, orderchitiet_soluong*menu_gia AS thanhtien FROM orderchitiet INNER JOIN menu ON orderchitiet.menu_id=menu.menu_id where orderchitiet.order_id="'.$table.'"  ORDER BY orderchitiet_id ASC');
	$stt=1;
	$tongtien=0;
	$KM=0;
	while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$Result_DTKM = mysqli_query($Conn, 'SELECT * FROM doituongkm WHERE khuyenmai_id='.$Row_KM["khuyenmai_id"]);
		while($Row_DTKM=mysqli_fetch_array($Result_DTKM, MYSQLI_ASSOC)){
		 if($Row_DTKM["menu_id"]==$row['menu_id'])$KM+=($Row_KM["khuyenmai_giatri"]*$row['thanhtien'])/100;
		 if($Row_DTKM["menu_id"]==0)$KM+=($row['thanhtien']*$Row_KM["khuyenmai_giatri"])/100;
		}
?>
              	<tr>
                  <td align="center"><?php echo $stt; ?></td>
                  <td><?php echo $row['menu_ten']; ?></td>
                  <td><?php echo $row['orderchitiet_yeucau']; ?></td>
                  <td align="center"><?php echo $row['orderchitiet_soluong']; ?></td>
                  <td align="right"><?php echo number_format($row['menu_gia'],0,",","."); ?></td>
                  <td align="right"><?php echo number_format($row['thanhtien'],0,",","."); ?></td>
                  <td><?php switch($row['orderchitiet_status']){ case 0: echo "Đang chờ order"; break; case 1: echo "Đang chờ chế biến"; break; case 2: echo "Đang chế biến"; break; case 3: echo "Đã phục vụ"; break;}?></td>
                  <td class="edit" align="center"><a href="?table=<?php echo $table; ?>&iddetail=<?php echo $row['orderchitiet_id']; ?>&action=edit" ><img class="" src="icon/icons8-edit-48.png" width="30" height="30" /></a></td>
                  <td class="delete" align="center"><a href="?table=<?php echo $table; ?>&iddetail=<?php echo $row['orderchitiet_id']; ?>&action=delete" onClick="return deleteConfirm()"><img src="icon/icons8-delete-forever-48.png" width="30" height="30" /></a></td>
                </tr>
<?php $stt++;$tongtien+=$row['thanhtien'];}?>
              </tbody>
                <tr>
                  <td colspan="2" bgcolor="#FFFF66"><strong>Cộng khoản:</strong></td>
                  <td align="center" bgcolor="#FFFF66"><strong><?php echo $stt-1; ?></strong></td>
                  <td colspan="2" align="right" bgcolor="#FFFF66"><strong>Tổng Cộng:</strong></td>
                  <td align="right" bgcolor="#FFFF66"><strong><?php echo number_format($tongtien,0,",","."); ?></strong></td>
                  <td colspan="3" bgcolor="#FFFF66"><strong>Khuyến Mãi: <?php echo number_format($KM,0,",",".");?> VNĐ</strong></td>
                </tr>
						</table>
					</div>
				</div>
				<div class="row">
					<form action="" method="post" enctype="multipart/form-data" id="formMoney">
						<table width="100%" border="0">
							<tr>
								<td>Số tiền khách đưa: </td>
								<td><input class="form-control" name="txtMoney" type="number" required min="<?php echo $tongtien?>" placeholder="Nhập vào số tiền khách đưa" id="txtMoney" /></td>
								<td align="left"><strong>&nbsp;VNĐ</strong></td>
								<td><input name="btnYcTT" class="btn btn-success" type="submit" id="btnYcTT" value="Lưu" /></td>
								<td><input name="btnCancel" class="btn btn-warning" type="reset" id="btnCancel" value="Hủy" /></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="col-lg-12 text-right">
        	<form method="post" id="formsubmit">
            <input name="btnGuiOrder" class="btn btn-success" type="button" id="btnGuiOrder" value="Gửi Order" onclick="window.location='order.php?table=<?php echo $table;?>&action=submitorder'"/>
            <input name="btnGoiThem" class="btn btn-primary" type="button" id="btnGoiThem" value="Gọi Thêm" />
            <input name="btnThanhToan" class="btn btn-success" type="button" id="btnThanhToan" value="Thanh Toán" />
           	<input name="btnThanhToanHD" class="btn btn-success" type="submit" id="btnThanhToanHD" value="Thanh Toán Hóa Đơn" />
            <input name="btnExit" class="btn btn-warning" type="button" id="btnExit" value="Trở Về" onclick="window.location='<?php if(isset($_GET['action']))echo "order.php?table=".$table;else echo "htql.php"; ?>'" />
          </form>
				</div>
			</div>
			<div id="ChiTiet">
				<div class="row title_lv1 text-center" id="margin-bottom_5">Chi Tiết Hóa Đơn</div>
				<div class="row" id="margin-bottom_5">
<?php 
	$result = mysqli_query($Conn, 'SELECT ordertable.*, nhanvien.nhanvien_ten FROM ordertable Left JOIN nhanvien ON ordertable.user_id=nhanvien.user_id where ordertable.order_id="'.$table.'"');
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
?>
					<table class="table_tt" id="margin-bottom_5" width="100%" border="0">
						<tr>
							<td width="15%">Khu:</td>
							<td width="10%"> <strong><?php echo $row["order_khu"]; ?></strong></td>
							<td>Số lượng KH:</td>
							<td width="25%"><strong><?php echo $row["order_slkh"]; ?></strong></td>
							<td>Giờ Vào:</td>
							<td><strong><?php if($row["order_giovao"]!="")echo date_format(date_create($row["order_giovao"]),'d/m/Y H:i:s'); ?></strong></td>
						</tr>
						<tr>
							<td>Bàn Số:</td>
							<td><strong><?php echo $row["order_soban"]; ?></strong></td>
							<td>Nhân Viên:</td>
							<td><strong><?php echo $row["nhanvien_ten"]; ?></strong></td>
							<td>Giờ ra:</td>
							<td><strong></strong></td>
						</tr>
					</table>
				</div>
				<div class="row">
					<div class="table-responsive">
						<table width="100%" id="tableReviewOrder" border="1" class="table table-hover">
							<thead>
              	<tr class="TH">
                  <td><strong>STT</strong></td>
                  <td><strong>Tên Món</strong></td>
                  <td><strong>Ghi Chú</strong></td>
                  <td><strong>Số Lượng</strong></td>
                  <td><strong>Đơn Giá (VNĐ)</strong></td>
                  <td><strong>Thành Tiền (VNĐ)</strong></td>
                  <td><strong>Trạng Thái</strong></td>
                </tr>
              </thead>
							<tbody>
<?php 
	$Result_KM = mysqli_query($Conn, 'SELECT * FROM khuyenmai WHERE khuyenmai_batdau<"'.date("Y-m-d H:i:s").'" and khuyenmai_ketthuc>"'.date("Y-m-d H:i:s").'" ORDER BY khuyenmai_giatri DESC LIMIT 1');
	$Row_KM=mysqli_fetch_array($Result_KM, MYSQLI_ASSOC);
	$result = mysqli_query($Conn, 'SELECT orderchitiet.*, menu.menu_ten,  menu.menu_gia, orderchitiet_soluong*menu_gia AS thanhtien FROM orderchitiet INNER JOIN menu ON orderchitiet.menu_id=menu.menu_id where orderchitiet.order_id="'.$table.'" ORDER BY orderchitiet_id ASC');
	$stt=1;
	$KM=0;
	$tongtien=0;
	while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$Result_DTKM = mysqli_query($Conn, 'SELECT * FROM doituongkm WHERE khuyenmai_id='.$Row_KM["khuyenmai_id"]);
		while($Row_DTKM=mysqli_fetch_array($Result_DTKM, MYSQLI_ASSOC)){
		 if($Row_DTKM["menu_id"]==$row['menu_id'])$KM+=($Row_KM["khuyenmai_giatri"]*$row['thanhtien'])/100;
		 if($Row_DTKM["menu_id"]==0)$KM+=($row['thanhtien']*$Row_KM["khuyenmai_giatri"])/100;
		}
?>
              	<tr>
                  <td align="center"><?php echo $stt; ?></td>
                  <td><?php echo $row['menu_ten']; ?></td>
                  <td><?php echo $row['orderchitiet_yeucau']; ?></td>
                  <td align="center"><?php echo $row['orderchitiet_soluong']; ?></td>
                  <td align="right"><?php echo number_format($row['menu_gia'],0,",","."); ?></td>
                  <td align="right"><?php echo number_format($row['thanhtien'],0,",","."); ?></td>
                  <td><?php switch($row['orderchitiet_status']){ case 0: echo "Đang chờ order"; break; case 1: echo "Đang chờ chế biến"; break; case 2: echo "Đang chế biến"; break; case 3: echo "Đã phục vụ"; break;}?></td>
                </tr>
<?php $stt++;$tongtien+=$row['thanhtien'];}?>                
              </tbody>
							<tr>
								<td colspan="2" bgcolor="#FFFF66"><strong>Cộng khoản:</strong></td>
								<td align="center" bgcolor="#FFFF66"><strong><?php echo $stt-1; ?></strong></td>
								<td colspan="2" align="right" bgcolor="#FFFF66"><strong>Tổng Cộng:</strong></td>
								<td align="right" bgcolor="#FFFF66"><strong><?php echo number_format($tongtien,0,",","."); ?></strong></td>
								<td bgcolor="#FFFF66"><strong>Khuyến Mãi: <?php echo number_format($KM,0,",",".");?> VNĐ</strong></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-lg-12 text-right">
						<input name="btnExit" class="btn btn-warning" type="button" id="btnExit" value="Trở Về" onclick="window.location='htql.php'" />
				</div>
			</div>
		</div>
    </div>
    <?php include_once('footer.html')?>
  </div>
</body>
</html>
<script>
	function addTableAConfirm(){
		if(confirm("Thêm bàn vào danh sách bàn!")) window.location='?table=1A&action=addtable&campus=A';	
		else return false;
	}
	function addTableBConfirm(){
		if(confirm("Thêm bàn vào danh sách bàn!")) window.location='?table=1A&action=addtable&campus=B';	
		else return false;
	}
	$(document).ready(function(e) {
		var table = $("#tableOrder, #tableReviewOrder").DataTable({
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
			"lengthMenu":[[-1],["Tất cả"]]	
		});
		var table = $("#tableMon").DataTable({
			responsive:true,
			"language":{
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
			"lengthMenu":[[-1],["tất cả"]]	
		});
		$(".dataTables_length, .dataTables_info, #tableMon_paginate").hide();
	});
	$('#GoiMon').hide();
	$('#ChiTiet').hide();
	$('#formGoiMon').hide();
	$('#btnGuiOrder').hide();
	$('#btnGoiThem').hide();
	$('#btnThanhToan').hide();
	$('#btnThanhToanHD').hide();
	$('#btnGoiThem').click(function(e) {
  	$('#formGoiMon').show();
	});
	$('#formMoney').hide();
	$('#btnThanhToan').click(function(e) {
  	$('#formMoney').show();
	});
	$('#btnCancel').click(function(e) {
    $('#formMoney').hide();
  });
	<?php 
		echo '$("#btnban_'.$table.'").css("transform","scale(1,1.3)");';
		if($Row['user_loai']!=2&&$Row['user_loai']!=3){echo '$("#btnbanThemBanA").hide();$("#btnbanThemBanB").hide();';}
		if($Row['user_permissoder']==2){echo '$("#ChiTiet").show();';}else {echo '$("#GoiMon").show();';}
		if($Row_order["order_status"]!=0){echo '$("#formTTKh").hide();$("#formGoiMon").show();';}
		if($stt>1){echo '$("#btnGuiOrder").show();';}
		if($statusTT!=0){echo '$("#formGoiMon").hide();$("#btnGuiOrder").hide();$("#btnGoiThem").show();';}
		if($statusTT==3){echo '$("#btnThanhToan").show();';}
		if($Row_order['user_id']!=$_SESSION["user_id"]){echo '$("#formGoiMon").hide();$("#btnGuiOrder").hide();$(".edit").hide();$(".delete").hide();';}
		if($Row_order["order_status"]==2&&$Row['user_loai']==3){echo '$("#btnThanhToanHD").show();';}
		if($Row_order["order_status"]==2){echo '$("#formGoiMon").hide();';}
		if(isset($_GET['action'])){echo '$("#formGoiMon").show();$("#btnGuiOrder").hide();';}
		if($_GET['table']=="6868"&&$statusTT==3){echo '$("#formGoiMon").show();$("#btnGoiThem").hide();';}
	?>
	function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
	}
	$(document).ready(function(e) {
		$('#btnGoiMon').click(function(e) {
			var loi="";
			var mon=$('input[name=RadioGroupMon]:checked').val();
			if(!mon)loi+="Vui lòng chọn món trong danh sách!\n";
			if(loi!="") {alert(loi);return false;}
    });
  });
</script>
<?php }}?>