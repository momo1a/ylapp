<!DOCTYPE HTML>
<html>
<head>
<?php $this->load->view('public/header_meta');?>
</head>
<body>
<?php $this->load->view('public/topbar');?>
<div class="container clearfix">
	<div class="main">
		<div class="main-wrap" id="main-wrap">
			<?php $this->load->view('welcome/main');?>
		</div>
	</div><!-- /main -->
	<?php $this->load->view('public/sidebar');?>
</div><!-- /container -->
</body>
</html>