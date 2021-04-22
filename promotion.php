<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permisskhuyenmai  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permisskhuyenmai']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else{/*2*/ if($Row_permiss['user_permisskhuyenmai']==1){
		if(isset($_GET["action"])&&isset($_GET["id"])){
			if(($_GET["action"]=="update"||$_GET["action"]=="delete")&&is_numeric($_GET["id"])){
				//CapNhat
				if($_GET["action"]=="update"){
					$Result_km = mysqli_query($Conn,"Select * From khuyenmai Where khuyenmai_id=".$_GET["id"]);
					$Row_km=mysqli_fetch_array($Result_km,MYSQLI_ASSOC);
					$Result_dtkm = mysqli_query($Conn,"Select * From doituongkm Where khuyenmai_id=".$_GET["id"]);
					while($Row_dtkm=mysqli_fetch_array($Result_dtkm,MYSQLI_ASSOC)){
						$dtkm[]=$Row_dtkm["menu_id"];
					};
					if(isset($_POST['btnLuu'])){
						$Ten=$_POST["txtTenKm"];
						$MoTa=$_POST["txtMoTa"];
						$GiaTri=$_POST["txtGiaTri"];
						$HinhAnh=$_FILES["fileHinhAnh"];
						$TenAnh =$HinhAnh['name'];
						$TimeIn=date_format(date_create($_POST["txtTimeIn"]),"Y-m-d H:i:s");
						$TimeOut=date_format(date_create($_POST["txtTimeOut"]),"Y-m-d H:i:s");
						if($TenAnh==""){ 
						mysqli_query($Conn,'UPDATE khuyenmai SET khuyenmai_ten="'.$Ten.'", khuyenmai_mota="'.$MoTa.'", khuyenmai_batdau="'.$TimeIn.'", khuyenmai_ketthuc="'.$TimeOut.'", khuyenmai_giatri='.$GiaTri.' WHERE khuyenmai_id='.$_GET['id']) or die(mysqli_connect_error());
						echo '<script>alert("Chỉnh sửa khuyến mãi thành công!");</script>';
						echo '<meta	http-equiv="refresh" content="0;URL=promotion.php" />';
						}else{
							if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
								copy ($HinhAnh['tmp_name'],"imgkm/".$TenAnh);
								mysqli_query($Conn,'UPDATE khuyenmai SET khuyenmai_ten="'.$Ten.'", khuyenmai_mota="'.$MoTa.'",khuyenmai_hinhanh="'.$TenAnh.'", khuyenmai_batdau="'.$TimeIn.'", khuyenmai_ketthuc="'.$TimeOut.'", khuyenmai_giatri='.$GiaTri.' WHERE khuyenmai_id='.$_GET['id']) or die(mysqli_connect_error());
								echo '<script>alert("Chỉnh sửa khuyến mãi thành công!");</script>';
								echo '<meta	http-equiv="refresh" content="0;URL=promotion.php" />';
							}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
						}
						if($_POST['RadioDTKM']==0){
							mysqli_query($Conn,"Delete From doituongkm where khuyenmai_id=".$_GET["id"]);	
							mysqli_query($Conn,'INSERT INTO doituongkm(khuyenmai_id, menu_id) VALUES ('.$_GET["id"].',0)')or die(mysqli_connect_error());
						}else {
							mysqli_query($Conn,"Delete From doituongkm where khuyenmai_id=".$_GET["id"]);
							for ($i=0;$i< count($_POST["checkbox"]);$i++){
								$Menu_id=$_POST["checkbox"][$i];
								mysqli_query($Conn,'INSERT INTO doituongkm(khuyenmai_id, menu_id) VALUES ('.$_GET["id"].','.$Menu_id.')')or die(mysqli_connect_error());
							}
						}
					}
				}
				//Xóa
				else {
					$Result = mysqli_query($Conn,"Select khuyenmai_hinhanh From khuyenmai Where khuyenmai_id=".$_GET["id"]);
					$Row=mysqli_fetch_array($Result,MYSQLI_ASSOC);
					if($Row['khuyenmai_hinhanh']!=""){
						$tenXoa="imgkm/".$Row['khuyenmai_hinhanh'];
						unlink("$tenXoa");
					}
					mysqli_query($Conn,"Delete From khuyenmai where khuyenmai_id=".$_GET["id"]);
					mysqli_query($Conn,"Delete From doituongkm where khuyenmai_id=".$_GET["id"]);
					echo '<script>alert("Xóa menu thành công!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=promotion.php" />';
				}
			}else echo '<meta http-equiv="refresh" content="0;URL=promotion.php" />';
		}
		//Them
		else {
			if(isset($_POST['btnLuu'])){
				$Ten=$_POST["txtTenKm"];
				$MoTa=$_POST["txtMoTa"];
				$GiaTri=$_POST["txtGiaTri"];
				$HinhAnh=$_FILES["fileHinhAnh"];
				$TenAnh =$HinhAnh['name'];
				$TimeIn=date_format(date_create($_POST["txtTimeIn"]),"Y-m-d H:i:s");
				$TimeOut=date_format(date_create($_POST["txtTimeOut"]),"Y-m-d H:i:s");
				$Result_bodem= mysqli_query($Conn,'SELECT * FROM bodem WHERE bodem_id=1') or die(mysqli_connect_error($Conn));
				$Row_bodem=mysqli_fetch_array($Result_bodem,MYSQLI_ASSOC);
				if($TenAnh==""){ 
					mysqli_query($Conn,'INSERT INTO khuyenmai (khuyenmai_id, khuyenmai_ten, khuyenmai_mota, khuyenmai_batdau, khuyenmai_ketthuc, khuyenmai_giatri) VALUES ('.$Row_bodem["bodem_sokm"].',"'.$Ten.'","'.$MoTa.'","'.$TimeIn.'","'.$TimeOut.'",'.$GiaTri.')') or die(mysqli_connect_error());
					echo '<script>alert("Thêm khuyến mãi thành công!");</script>';
					echo '<meta	http-equiv="refresh" content="0;URL=promotion.php" />';
				}else{
					if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
						copy ($HinhAnh['tmp_name'],"imgkm/".$TenAnh);
						mysqli_query($Conn,'INSERT INTO khuyenmai (khuyenmai_id, khuyenmai_ten, khuyenmai_mota, khuyenmai_batdau, khuyenmai_ketthuc, khuyenmai_hinhanh, khuyenmai_giatri) VALUES ('.$Row_bodem["bodem_sokm"].',"'.$Ten.'","'.$MoTa.'","'.$TimeIn.'","'.$TimeOut.'","'.$TenAnh.'",'.$GiaTri.')') or die(mysqli_connect_error());
						echo '<script>alert("Thêm khuyến mãi thành công!");</script>';
						echo '<meta	http-equiv="refresh" content="0;URL=promotion.php" />';
					}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
				}
				if($_POST['RadioDTKM']==0){
					mysqli_query($Conn,'INSERT INTO doituongkm(khuyenmai_id, menu_id) VALUES ('.$Row_bodem["bodem_sokm"].',0)')or die(mysqli_connect_error());
				}else {
					for ($i=0;$i< count($_POST["checkbox"]);$i++){
						$Menu_id=$_POST["checkbox"][$i];
						mysqli_query($Conn,'INSERT INTO doituongkm(khuyenmai_id, menu_id) VALUES ('.$Row_bodem["bodem_sokm"].','.$Menu_id.')')or die(mysqli_connect_error());
					}
				}
				mysqli_query($Conn,'UPDATE bodem SET bodem_sokm='.($Row_bodem["bodem_sokm"]+1).' WHERE bodem_id=1')or die(mysqli_connect_error());			
			}
		}
	}
?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trà Sữa Huỳnh Hương - Khuyến Mãi</title>
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
	<div class="row content_promotion">
		<div class="col-lg	-12 title">Chương Trình Khuyến Mãi</div>
		<div class="col-lg-12 title_lv2" id="ThemMenu">Thêm Mới</div>
		<div class="col-lg-12 title_lv2" id="CapNhat">Cập Nhật</div>
		<div class="col-lg-12" id="margin-bottom_5">
			<div class="row input">
				<form action="" method="post" enctype="multipart/form-data" id="formKM">
        	<div class="col-lg-3">Tên khuyến mãi:</div>
          <div class="col-lg-9"><div class="row"><input name="txtTenKm" type="text" required class="form-control" id="txtTenKm" placeholder="Nhập tên chương trình khuyến mãi" value="<?php if(isset($Row_km["khuyenmai_ten"]))echo $Row_km["khuyenmai_ten"]; ?>"></div></div>
          <div class="col-lg-3">Mô tả khuyến mãi:</div>
          <div class="col-lg-9"><div class="row"><input name="txtMoTa" type="text" required class="form-control" id="txtMoTa" placeholder="Nhập mô tả" value="<?php if(isset($Row_km["khuyenmai_mota"]))echo $Row_km["khuyenmai_mota"]; ?>"></div></div>
           <div class="col-lg-3">Giá trị KM (%):</div>
          <div class="col-lg-9"><div class="row"><input name="txtGiaTri" type="number" required class="form-control" id="txtGiaTri" placeholder="Nhập vào số % giảm giá" max="100" min="1" value="<?php if(isset($Row_km["khuyenmai_giatri"]))echo $Row_km["khuyenmai_giatri"]; ?>"></div></div>
          <div class="col-lg-3">Banner khuyến mãi:</div>
          <div class="col-lg-9"><div class="row"><input name="fileHinhAnh" class="form-control" type="file" id="fileHinhAnh"></div></div>
          <div class="col-lg-3">Thời gian bắt đầu:</div>
          <div class="col-lg-9"><div class="row"><input name="txtTimeIn" type="datetime-local" required class="form-control" id="txtTimeIn" value="<?php if(isset($Row_km["khuyenmai_batdau"]))echo date_format(date_create($Row_km["khuyenmai_batdau"]),"Y-m-d")."T".date_format(date_create($Row_km["khuyenmai_batdau"]),"H:i"); ?>"></div></div>
          <div class="col-lg-3">Thời gian kết thúc:</div>
          <div class="col-lg-9"><div class="row"><input name="txtTimeOut" type="datetime-local" required class="form-control" id="txtTimeOut" value="<?php if(isset($Row_km["khuyenmai_ketthuc"]))echo date_format(date_create($Row_km["khuyenmai_ketthuc"]),"Y-m-d")."T".date_format(date_create($Row_km["khuyenmai_ketthuc"]),"H:i"); ?>"></div></div>
          <div class="col-lg-3">Đối tượng áp dụng:</div>
          <div class="col-lg-9">
          	<div class="row">
            	<label><input name="RadioDTKM" type="radio" id="RadioDTKM_0" value="0" <?php if(isset($dtkm))for($i=0;$i<count($dtkm);$i++)if($dtkm[$i]==0)echo 'checked="CHECKED"';?> > Tổng giá trị hóa đơn</label>
          	  <label><input type="radio" name="RadioDTKM" value="1" id="RadioDTKM_1" <?php if(isset($dtkm))for($i=0;$i<count($dtkm);$i++)if($dtkm[$i]!=0)echo 'checked="CHECKED"';?>> Chọn đối tượng trong menu</label>
          	</div>
            <div class="row DoiTuongKm"><div class="table-responsive">
            	<table width="100%" border="1" class="table table-striped" id="tableDTKM">
                <thead>
                  <tr class="TH">
                    <td bgcolor="#99CC00">STT</td>
                    <td bgcolor="#99CC00">Tên Món</td>
                    <td bgcolor="#99CC00">Chọn</td>
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
                    <td align="center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row["menu_id"] ?>" <?php if(isset($dtkm))for($i=0;$i<count($dtkm);$i++)if($dtkm[$i]==$row["menu_id"])echo 'checked="CHECKED"';?> /></td>
                  </tr>
								<?php
									$stt++;
									}
                ?>
                </tbody>
              </table>
            </div></div>
          </div>
          <div class="col-lg-12 text-center">
          	<input name="btnLuu" type="submit" class="btn btn-success" id="btnLuu" value="Lưu">
						<input name="btnExit" type="button" class="btn btn-warning" id="btnExit" value="Trở Về" onclick="window.location='<?php if(isset($_GET['id'])) echo 'promotion	.php'; else echo 'htql.php'; ?>'">
          </div>
				</form>
			</div>
		</div>
    <div class="col-lg-12 title_lv2">Danh Sách Các Chương Trình Khuyến Mãi</div>
		<div class="col-lg-12">
			<div class="row DSKM">
				<div class="table-responsive" id="margin-bottom_0">
					<table class="table table-hover" id="tableKM" width="100%" border="1">
						<thead>
							<tr class="TH">
								<td>STT</td>
								<td>Tên Khuyến Mãi</td>
								<td>Mô Tả</td>
								<td>Hình Ảnh</td>
								<td>Thời Gian Bắt Đầu</td>
								<td>Thời Gian Kết Thúc</td>
                <td>Giá trị KM</td>
								<td class="edit">Chỉnh Sửa</td>
								<td class="delete">Xóa</td>
							</tr>
						</thead>
						<tbody>
						<?php 
						$result = mysqli_query($Conn, "SELECT * FROM khuyenmai");
						$stt=1;
						while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
            ?>
							<tr>
								<td align="center"><?php echo $stt ?></td>
								<td><?php echo $row["khuyenmai_ten"] ?></td>
								<td><?php echo $row["khuyenmai_mota"] ?></td>
								<td align="center"><img src="imgkm/<?php if($row["khuyenmai_hinhanh"]!="")echo $row["khuyenmai_hinhanh"];else echo "no_img.png"; ?>" width="150px"></td>
								<td align="center"><?php echo date_format(date_create($row["khuyenmai_batdau"]),"d/m/Y H:i:s"); ?></td>
								<td align="center"><?php echo date_format(date_create($row["khuyenmai_ketthuc"]),"d/m/Y H:i:s"); ?></td>
                <td align="center"><?php echo $row["khuyenmai_giatri"]."%"; ?></td>
								<td class="edit" align="center"><a href="promotion.php?id=<?php echo $row["khuyenmai_id"]; ?>&action=update"><img src="icon/icons8-edit-48.png" width="40"></a></td>
								<td class="delete" align="center"><a href="promotion.php?id=<?php echo $row["khuyenmai_id"]; ?>&action=delete"><img src="icon/icons8-delete-forever-48.png" width="40"></a></td>
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
	<?php include_once("footer.html")?>
</div>
</body>
</html>
<script>
	$('#CapNhat, #btnExit2').hide();
	$(".DoiTuongKm").hide();
    <?php 
		if(isset($_GET["action"])&&$_GET["action"]==="update") echo '$("#CapNhat").show(); $("#ThemMenu").hide();';
		if(isset($dtkm)&&$dtkm[0]!=0)echo '$(".DoiTuongKm").show();';
		if($Row_permiss['user_permisskhuyenmai']==2) echo '$("#ThemMenu, #formKM, .edit, .delete").hide();$("#btnExit2").show();';
	?>
	$(document).ready(function(e) {
		var table = $("#tableDTKM").DataTable({
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
		$("#tableDTKM_length, #tableDTKM_info, #tableDTKM_paginate").hide();
		var table = $("#tableKM").DataTable({
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
		$('#btnLuu').click(function(e) {
			var loi="";
			if($('#txtTimeIn').val()>$('#txtTimeOut').val())	loi+="Ngày không hợp lệ, vui lòng kiểm tra lại!\n";
			if(!$(':checkbox:checked').val()&&!$('#RadioDTKM_0:checked').val())	loi+="Vui lòng chọn đối tượng khuyến mãi!\n";
			if(loi!="") {alert(loi);return false;}
    });
		$("#RadioDTKM_1").click(function(e) {
			$(".DoiTuongKm").show();
    });
		$("#RadioDTKM_0").click(function(e) {
			$(".DoiTuongKm").hide();
    });
</script>
<?php }} ?>