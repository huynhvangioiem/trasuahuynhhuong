<?php
session_start();
date_default_timezone_set('asia/ho_chi_minh');
if(!isset($_SESSION["user_id"])) {
	echo '<script>alert("Vui lòng đăng nhập để tiếp tục!");</script>';
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
}else {//1
	include_once('connect.php');
	//Xác thực quyền truy cập
	$Result_permiss = mysqli_query($Conn,"SELECT user_permisstrangchu  FROM user WHERE user_id=".$_SESSION["user_id"]) or die(mysqli_connect_error($Conn));
	$Row_permiss=mysqli_fetch_array($Result_permiss,MYSQLI_ASSOC);
	if($Row_permiss['user_permisstrangchu']==0){
		echo '<script>alert("Bạn không có quyền truy cập trang này.\nVui lòng quay lại!");</script>';
		echo '<meta http-equiv="refresh" content="0;URL=htql.php" />';
	}else if($Row_permiss['user_permisstrangchu']==1){
		$Result_indexslogan = mysqli_query($Conn,"Select * From indexslogan Where indexslogan_id=1");
		$Row_indexslogan=mysqli_fetch_array($Result_indexslogan,MYSQLI_ASSOC);
		if(isset($_POST['btnBanner'])){
			$HinhAnh=$_FILES["fileBanner"];
			$TenAnh =$HinhAnh['name'];
			if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
				if($Row_indexslogan['indexslogan_banner']!=""){
					$tenXoa="index_img/".$Row_indexslogan['indexslogan_banner'];
					unlink("$tenXoa");
			}
			copy ($HinhAnh['tmp_name'],"index_img/".$TenAnh);
			mysqli_query($Conn,"UPDATE indexslogan set indexslogan_banner='$TenAnh' WHERE indexslogan_id=1") or 
			die(mysqli_connect_error());
			echo '<script>alert("Chỉnh sửa trang chủ thành công!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=index_manage.php" />';
			}else echo '<script>alert("Vui lòng chọn một file hình ảnh!");</script>';
		}
		if(isset($_POST['btnLuu'])){
			$TenSlogan=$_POST['txtSlogan'];
			mysqli_query($Conn,"UPDATE indexslogan set indexslogan_slogan='$TenSlogan' WHERE indexslogan_id=1") or 
			die(mysqli_connect_error());
			echo '<script>alert("Chỉnh sửa trang chủ thành công!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=index_manage.php" />';
		}
		if(isset($_POST['btnSlide'])){
			$HinhAnh=$_FILES["fileSlide"];
			$TenAnh =$HinhAnh['name'];
			if($HinhAnh['type']=="image/jpg"||$HinhAnh['type']=="image/jpeg"||$HinhAnh['type']=="image/png"||$HinhAnh['type']=="image/gif"){
			copy ($HinhAnh['tmp_name'],"index_img/".$TenAnh);
			mysqli_query($Conn,"INSERT INTO indexbanner (indexbanner_banner) VALUES('$TenAnh')") or die(mysqli_connect_error());
			echo '<script>alert("Chỉnh sửa trang chủ thành công!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=index_manage.php" />';
			}
		}
		if(isset($_GET["id"])){
			$Result = mysqli_query($Conn,"Select indexbanner_banner From indexbanner Where indexbanner_id=".$_GET["id"]);
			$Row=mysqli_fetch_array($Result,MYSQLI_ASSOC);
					$tenXoa="index_img/".$Row['indexbanner_banner'];
					unlink("$tenXoa");
			$Result = mysqli_query($Conn,"Delete From indexbanner where indexbanner_id=".$_GET["id"]);
			echo '<script>alert("Xóa ảnh thành công!");</script>';
			echo '<meta	http-equiv="refresh" content="0;URL=index_manage.php" />';
		}					
?>
<!DOCTYPE html >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trà Sữa Huỳnh Hương - Quản Trị Trang Chủ</title>
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
    <div class="row content_index_manage">
      <div class="col-lg-12 title">Quản Trị Trang Chủ</div>
      <div class="col-lg-4">
       	<div class="row title_lv2">Banner</div>
        <?php 
			$result = mysqli_query($Conn, "SELECT * FROM indexslogan");
			while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
		?>
          <div class="row"><a href="index_img/<?php echo $row['indexslogan_banner'] ?>" target="_blank"><img src="index_img/<?php echo $row['indexslogan_banner'] ?>" class="img-responsive" /></a></div>
          <div class="row text-center">
          	<form action="" method="post" enctype="multipart/form-data" id="formBanner">
          		<input name="fileBanner" type="file" required id="fileBanner" class="form-control" />
              <input name="btnBanner" class="btn btn-success" type="submit" id="btnBanner" value="Lưu" />
              <input name="btnReset" class="btn btn-warning" type="reset" id="btnReset" value="Bỏ Qua" />
          	</form>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="row title_lv2">Slogan Giới Thiệu</div>
        <div class="row text-center">
          <form action="" method="post" enctype="multipart/form-data" id="formSlogan">
            <textarea name="txtSlogan" class="form-control" cols="" rows="5" id="txtSlogan"><?php echo $row['indexslogan_slogan']
 ?></textarea>
            <input name="btnLuu" class="btn btn-success" type="submit" id="btnLuu" value="Lưu" />
            <input name="btnReset" class="btn btn-warning" type="reset" id="btnReset" value="Bỏ Qua" />
          </form>
        </div>
        <?php } ?>
      </div>
      <div class="col-lg-4 slide_show">
        <div class="row title_lv2">Danh Sách Ảnh Động</div>
        <div class="row">
        	<table width="100%" class="table table-striped" id="margin-bottom_5">
            <tr>
              <td align="center"><strong>STT</strong></td>
              <td align="center"><strong>Hình Ảnh</strong></td>
              <td align="center"><strong>Xóa</strong></td>
            </tr>
             <?php 
			$result = mysqli_query($Conn, "SELECT * FROM indexbanner");
			$stt=1;
			while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
			?>
            <tr>
              <td align="center"><?php echo $stt ?></td>
              <td align="center"><img src="index_img/<?php echo $row['indexbanner_banner'] ?>" class="img-responsive" /></td>
              <td align="center"><a href="?id=<?php echo $row['indexbanner_id'] ?>" onClick="return deleteConfirm()"><img src="icon/icons8-delete-forever-48.png" width="40" /></a></td>
            </tr>
            <?php  
			$stt++;
			} ?>
          </table>
        </div>
        <div class="row text-center">
          <form action="" method="post" enctype="multipart/form-data" id="formSlide">
            <input name="fileSlide" type="file" id="fileSlie" class="form-control" />
            <input name="btnSlide" class="btn btn-success" type="submit" id="btnSlide" value="Thêm" />
            <input name="btnReset" class="btn btn-warning" type="reset" id="btnReset" value="Bỏ Qua" />
          </form>
        </div>
      </div>
      <div class="col-lg-12 title_lv2">
      	<input name="btnIndex" class="btn btn-success" type="button" id="btnIndex" value="Xem Trang Chủ" onclick="window.open('index.php')" />
        <input name="btnExit" class="btn btn-warning" type="button" id="btnExit" value="Trở Về" onclick="window.location='htql.php'" />
      </div>
    </div>
    <?php include_once('footer.html')?>
  </div>
</body>
</html>
<script>
function deleteConfirm(){
		if(confirm("Bạn có chắc chắn muốn xóa!")) return true;	
		else return false;
		}
</script>
<?php }}?>