<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_loai  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_loai']!=5){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else {
		echo '<meta http-equiv="refresh" content="20;URL=bartender.php" />';
		if(isset($_GET["id"])&&is_numeric($_GET["id"])){
			$Result_status= mysqli_query($Conn, 'SELECT orderchitiet_status FROM orderchitiet WHERE orderchitiet_id ='.$_GET["id"]);
			$Row_status=mysqli_fetch_array($Result_status, MYSQLI_ASSOC);
			mysqli_query($Conn, 'UPDATE orderchitiet SET orderchitiet_status='.($Row_status["orderchitiet_status"]+1).'  WHERE orderchitiet_id ='.$_GET["id"]);
			echo '<meta http-equiv="refresh" content="0;URL=bartender.php" />';
		}
	
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
<body class="body" style="font-size:20px;">
<div class="container content_order">
	<?php include_once("banner.html")?>
	<div class="row title_lv2 text-center">DANH SÁCH CÁC MÓN ĐANG CHỜ</div>
  <div class="row">
  	<div class="table-responsive">
      <table width="100%" id="tableOrder" border="1" class="table table-striped">
        <thead>
        <tr class="TH">
          <td>Bàn</td>
          <td>Tên Món</td>
          <td>Số Lượng</td>
          <td>Trạng Thái</td>
          </tr>
        </thead>
      	<tbody>
        <?php
					$result = mysqli_query($Conn, 'SELECT orderchitiet.*, menu.menu_ten FROM orderchitiet INNER JOIN menu ON orderchitiet.menu_id=menu.menu_id WHERE orderchitiet.orderchitiet_status=3 AND menu.menu_loai=0 ORDER BY orderchitiet_id DESC LIMIT 2');
					while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        ?>
          <tr>
            <td align="center"><?php echo $row["order_id"]; ?></td>
            <td><?php echo $row["menu_ten"]." ".$row["orderchitiet_yeucau"]; ?></td>
            <td align="center"><?php echo $row["orderchitiet_soluong"]; ?></td>
            <td align="center"><input name="btnCooking_<?php echo $row["orderchitiet_id"]; ?>" type="submit" id="btnCooking_<?php echo $row["orderchitiet_id"]?>" <?php if($row["orderchitiet_status"]==1)echo 'class="form-control btn-info" value="Nhận chế biến"'; else if($row["orderchitiet_status"]==2)echo 'class="form-control btn-success" value="Chế biến xong"'; else echo 'class="form-control btn-warning" value="Đã phục vụ" disabled'; ?> onClick="window.location='?id=<?php echo $row["orderchitiet_id"]; ?>'"></td>
          </tr>
        <?php }?>
				<?php
					$result = mysqli_query($Conn, 'SELECT orderchitiet.*, menu.menu_ten FROM orderchitiet INNER JOIN menu ON orderchitiet.menu_id=menu.menu_id WHERE orderchitiet.orderchitiet_status!=3 AND menu.menu_loai=0 ORDER BY orderchitiet_id ASC');
					while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        ?>
          <tr">
            <td align="center"><?php echo $row["order_id"]; ?></td>
            <td><?php echo $row["menu_ten"]." ".$row["orderchitiet_yeucau"]; ?></td>
            <td align="center"><?php echo $row["orderchitiet_soluong"]; ?></td>
            <td align="center"><input name="btnCooking_<?php echo $row["orderchitiet_id"]; ?>" type="submit" id="btnCooking_<?php echo $row["orderchitiet_id"]?>" <?php if($row["orderchitiet_status"]==1)echo 'class="form-control btn-info" value="Nhận pha chế"'; else if($row["orderchitiet_status"]==2)echo 'class="form-control btn-success" value="Pha chế xong"'; else echo 'class="form-control btn-warning" value="Đã phục vụ" disabled'; ?> onClick="window.location='?id=<?php echo $row["orderchitiet_id"]; ?>'"></td>
          </tr>
        <?php }?>
      	</tbody>
      </table>
    </div>
    <div class="text-center"><input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='htql.php'"></div>	
  </div>
  <?php include_once("footer.html")?>
</div>
</body>
</html>
<?php }}?>