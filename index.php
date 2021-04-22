<?php
	date_default_timezone_set('asia/ho_chi_minh');
	include_once('connect.php');
	$Result_Banner = mysqli_query($Conn,"SELECT * FROM indexslogan WHERE 1") or die(mysqli_connect_error($Conn));
	$Row_Banner=mysqli_fetch_array($Result_Banner,MYSQLI_ASSOC);	
?>
<!DOCTYPE html >
<html  lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="" /><!--Từ khòa tìm kiếm-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trà Sữa Huỳnh Hương</title>
  <meta name="description" content="" /><!--Mô tả bài viết-->
  <link href="img/icontitle.jpg" rel="shortcut icon" type="image/vnd.microsoft.icon" />
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <link rel="stylesheet" href="vendor/nivo-slider/themes/bar/bar.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="vendor/nivo-slider/themes/light/light.css" type="text/css" media="screen" />
  
  <link href="vendor/easyslides/jquery.easy_slides.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="style.css" />
  <script src="myJs.js"></script><!--Rỗng-->
</head>

<body class="body">
	<div class="container"><!--Contaniner-->
  	<div class="row socia"><!--SociaMenu-->
      <div class="col-md-8"></div>
      <div class="col-md-3">
        <a href="https://www.facebook.com/TraSuaMyCayHuynhHuong" target="_blank"><i class="fa fa-facebook"aria-hidden="true"></i></a>
        <a href="" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
        <a href="login.php" target="_self"><i class="fa fa-sign-in" aria-hidden="true"></i></a>        
      </div>
      <div class="col-md-1"></div>
    </div><!--./SociaMenu-->
    <div class="row banner"><!--Banner-->
    	<img class="img-responsive" src="index_img/<?php echo $Row_Banner["indexslogan_banner"]; ?>">
    </div><!--./Banner-->
    <div class=" row MenuNgang"><!--MenuNgang-->
      <nav class="navbar navbar-default Menu" style="margin-bottom:5px;"><!--navbar-->
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button"class="navbar-toggle collapsed" data-toggle="collapse" data-target="#Menu">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">TRANG CHỦ</a>
          </div>
          <div class=" collapse navbar-collapse" id="Menu">
            <ul class="nav navbar-nav ">
              <li><a href="#GT">GIỚI THIỆU</a></li>
              <li><a href="#TD">THỰC ĐƠN</a></li>
              <li><a href="#KM">TIN KHUYẾN MÃI</a></li>
              <li><a href="#TBTD">TUYỂN DỤNG</a></li>
              <li><a href="#fb">THEO DÕI</a></li>
              <li><a href="#map">ĐỊA ĐIỂM</a></li>
            </ul>
          </div>
        </div>
      </nav>
   	</div><!--./MenuNgang-->
    <div class="row ListBanner"><!--ListBanner-->
    	<div class="col-lg-12" id="wrapper">
        <div class="row slider-wrapper theme-light">
          <div id="slider" class="nivoSlider">
						<?php 
							$Result_sildeshow = mysqli_query($Conn, "SELECT * FROM indexbanner");
							while($Row_silde=mysqli_fetch_array($Result_sildeshow, MYSQLI_ASSOC))
							{
            ?>
          	<img src="index_img/<?php echo $Row_silde["indexbanner_banner"]; ?>" alt="" />
            <?php }?>
          </div>
        </div>
      </div>
  	</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
		<script type="text/javascript" src="vendor/nivo-slider/jquery.nivo.slider.js"></script> 
    <script>
    	jQuery(window).load(function() {
       jQuery('#slider').nivoSlider();
      });
			jQuery(window).load(function() {
       jQuery('#slider2').nivoSlider({
					effect: 'random',
					slices: 15,
					boxCols: 8,
					boxRows: 4,
					animSpeed: 600,
					pauseTime: 5000,
					startSlide: 0,
					directionNav: true,
					controlNav: true,
					controlNavThumbs: false,
					pauseOnHover: true,
					manualAdvance: false,
					prevText: '|<<',
					nextText: '>>|',
					randomStart: true,
					beforeChange: function(){},
					afterChange: function(){},
					slideshowEnd: function(){},
					lastSlide: function(){},
					afterLoad: function(){}
				});
      });
    </script>
    <!--./ListBanner-->
    <div class="row content"><!--content-->
      <div class="col-lg-8 trai"><!--trai-->
      	<!--SanPhamMoi-->
        	<div class="row title"><div class="col-lg-12">Món Ăn Mới</div></div>
          <div class="row SPMoi">
            <div class="slider slider_four_in_line slider_four_in_line1">
						<?php 
              $Result_NewMenu = mysqli_query($Conn, "SELECT * FROM menu ORDER BY menu_id DESC LIMIT 5");
              while($Row_NewMenu=mysqli_fetch_array($Result_NewMenu, MYSQLI_ASSOC)){
            ?>
              <div style="height:260px;">
                <img class="img-responsive img-thumbnail" src="imgmenu/<?php if($Row_NewMenu["menu_hinhanh"]!="")echo $Row_NewMenu["menu_hinhanh"]; else echo "no_img.png";  ?>">
                <p class="tenSP"><?php echo $Row_NewMenu["menu_ten"];?></p>
                <p class="giaSP"><span class=""><?php echo number_format($Row_NewMenu["menu_gia"],0,",","."); ?></span> VNĐ</p>
              </div>
            <?php }?>
              <div class="next_button"></div>  
              <div class="prev_button"></div>  
            </div>
						<script src="vendor/easyslides/jquery.easy_slides.js"></script>
						<script>
              $(document).ready(function() {
                $('.slider_four_in_line1').EasySlides({'autoplay': false,'timeout': 5000, 'show': 10})
              });
              $(document).ready(function() {
              	$('.slider_four_in_line2').EasySlides({'autoplay': true,'timeout': 3500, 'show': 10,'stepbystep': false})
              });
            </script>
          </div>
        <!--./SanPhamMoi-->
        <!--Tin Khuyến Mãi-->
        <div class="row title" id="KM"><div class="col-lg-12">Tin Khuyến Mãi</div></div>
        <div class="row">
          <div class="col-lg-12" id="wrapper">
            <div class="row slider-wrapper theme-bar">
              <div id="slider2" class="nivoSlider">
              <?php 
								$Result_KM = mysqli_query($Conn, "SELECT khuyenmai_hinhanh FROM khuyenmai ORDER BY khuyenmai_id DESC");
								while($Row_KM=mysqli_fetch_array($Result_KM, MYSQLI_ASSOC)){
							?>
                <img src="imgkm/<?php echo $Row_KM["khuyenmai_hinhanh"]; ?>" alt="" title="" />
              <?php }?>
              </div>
            </div>
          </div>
       	</div>
        <!--./Tin Khuyến Mãi-->
        <!--Thực Đơn Chính-->
        <div class="row title" id="TD"><div class="col-lg-12">Thực Đơn Chính</div></div>
        <div class="row thucDon">
          <div class="slider slider_four_in_line slider_four_in_line2">
            <?php 
              $Result_Menu = mysqli_query($Conn, "SELECT * FROM menu ORDER BY menu_loai DESC");
              while($Row_Menu=mysqli_fetch_array($Result_Menu, MYSQLI_ASSOC)){
            ?>
              <div style="height:260px;">
                <img class="img-responsive img-thumbnail" src="imgmenu/<?php if($Row_Menu["menu_hinhanh"]!="")echo $Row_Menu["menu_hinhanh"]; else echo "no_img.png";  ?>">
                <p class="tenSP"><?php echo $Row_Menu["menu_ten"];?></p>
                <p class="giaSP"><span class=""><?php echo number_format($Row_Menu["menu_gia"],0,",","."); ?></span> VNĐ</p>
              </div>
            <?php }?>
            <div class="next_button"></div>  
            <div class="prev_button"></div>  
          </div>
        </div>
        <!--./Thực Đơn Chính-->
      </div><!--./trai-->
      <div class="col-lg-4 phai"><!--phai-->
      	<div class="row title" id="GT"><div class="col-lg-12">Giới Thiệu</div></div>
        <div class="row"><p><?php echo $Row_Banner["indexslogan_slogan"]; ?></p></div>
        
        <div class="row title" id="TBTD"><div class="col-lg-12">Thông Báo Tuyển Dụng</div></div>
        <div class="row">
        	<ul>
          <?php 
						$Result_TuyenDung = mysqli_query($Conn, "SELECT * FROM tuyendung ORDER BY tuyendung_id DESC");
						while($Row_TuyenDung=mysqli_fetch_array($Result_TuyenDung, MYSQLI_ASSOC)){
					?>
            <li><a href="files/<?php echo $Row_TuyenDung["tuyendung_file"]; ?>" target="_blank"><?php echo $Row_TuyenDung["tuyendung_ten"]; ?></a></li>
          <?php }?>
          </ul>
        </div>
        
        <div class="row title" id="fb"><div class="col-lg-12">Theo Dõi Chúng Tôi</div></div>
        <div class="row fbpage">
          <div id="fb-root"></div>
          <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2&appId=264505234257058&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));</script>
          	<div class="fb-page" data-href="https://www.facebook.com/TraSuaMyCayHuynhHuong" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/TraSuaMyCayHuynhHuong" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/TraSuaMyCayHuynhHuong">Trà Sữa Huỳnh Hương</a></blockquote></div>
        </div>
        
        <div class="row title" id="map"><div class="col-lg-12">Vị Trí Của Chúng Tôi</div></div>
        <div class="row">
        	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3923.705138999891!2d105.37865476771543!3d10.444956454164497!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310a12eb231acbff%3A0x4e810bfd25c006cf!2zVHLDoCBz4buvYSBIdeG7s25oIEjGsMahbmc!5e0!3m2!1svi!2s!4v1541481007578" width="100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>	
      </div><!--./phai-->
    </div><!--./content-->
    <?php include_once('footer.html')?>
  </div><!--./Contaniner-->
</body>
</html>