<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="keyword" content="<?php echo $page_data['meta_keyword']; ?>">
		<meta name="description" content="<?php echo $page_data['meta_description']; ?>">
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo base_url('uploads/frontend/images/' . $cms_setting['fav_icon'] . img_reload()); ?>">
		<title><?php echo $page_data['page_title'] . " - " . $cms_setting['application_title']; ?></title>
		<!-- Bootstrap -->
		<link href="<?php echo base_url() ?>assets/frontend/css/bootstrap.min.css" rel="stylesheet">
		<!-- Template CSS Files  -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/plugins/animate.min.css?v=' . version_combine()); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/responsive.css?v=' . version_combine()); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/owl.carousel.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css?v=' . version_combine());?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/plugins/magnific-popup/magnific-popup.css?v=' . version_combine()); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style.css?v=' . version_combine()); ?>">
		<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
		<!-- If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF -->
		<script type="text/javascript">
			var base_url = "<?php echo base_url(); ?>";
			var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
			$(function($) {
				$.ajaxSetup({
					data: csrfData
				});
			});
		</script>
		<!-- Google Analytics --> 
		<?php echo $cms_setting['google_analytics']; ?>

	    <?php 
	    $getSettings = $this->db->select('pwa_enable')->where('id', 1)->get('saas_settings')->row();
	    if ($getSettings->pwa_enable == 1) {
	    	?>
	    <!-- Web Application Manifest -->
	    <link rel="manifest" href="./manifest.json">
	    <!-- Chrome for Android theme color -->
	    <meta name="theme-color" content="#6e8fd4">
	    
	    <!-- Add to homescreen for Chrome on Android -->
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="application-name" content="Ramom School">
	    <link rel="icon" sizes="512x512" href="<?php echo base_url('uploads/appIcons/icon-512x512.png')?>">
	    
	    <!-- Add to homescreen for Safari on iOS -->
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="apple-mobile-web-app-title" content="Ramom School">
	    <link rel="apple-touch-icon" href="<?php echo base_url('uploads/appIcons/icon-512x512.png')?>">

	    <script type="text/javascript">
	        // Initialize the service worker
	        if ('serviceWorker' in navigator) {
	            navigator.serviceWorker.register('/serviceworker.js', {
	                scope: '/'
	            }).then(function (registration) {
	                // Registration was successful
	            }, function (err) {
	                // registration failed :(
	                console.log('Service Worker registration failed: ', err);
	            });
	        }
	    </script>
	    <?php } ?>
		
		<!-- Theme Color Options -->
		<script type="text/javascript">
			document.documentElement.style.setProperty('--thm-primary', '<?php echo $cms_setting["primary_color"] ?>');
			document.documentElement.style.setProperty('--thm-hover', '<?php echo $cms_setting["hover_color"] ?>');
			document.documentElement.style.setProperty('--thm-text', '<?php echo $cms_setting["text_color"] ?>');
			document.documentElement.style.setProperty('--thm-secondary-text', '<?php echo $cms_setting["text_secondary_color"] ?>');
			document.documentElement.style.setProperty('--thm-footer-text', '<?php echo $cms_setting["footer_text_color"] ?>');
			document.documentElement.style.setProperty('--thm-radius', '<?php echo $cms_setting["border_radius"] ?>');
		</script>
	</head>
	<body>
		<!-- Preloader -->
		<div class="loader-container">
			<div class="lds-dual-ring"></div>
		</div>
		<?php $this->load->view('home/layout/header'); ?>
		<?php echo $main_contents; ?>
		<?php $this->load->view('home/layout/footer'); ?>
	</body>
</html>
