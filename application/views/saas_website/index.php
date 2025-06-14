<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title><?php echo $getSettings->seo_title; ?></title>
    <meta name="keyword" content="<?php echo $getSettings->seo_keyword; ?>" />
    <meta name="description" content="<?php echo $getSettings->seo_description; ?>" />
    <meta name="author" content="<?php echo $global_config['institute_name'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.png');?>" />

    <!-- CSS here -->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/LineIcons.2.0.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/animate.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/tiny-slider.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/glightbox.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/saas_main.css'); ?>" />

    <!-- Google Analytics --> 
    <?php echo $getSettings->google_analytics; ?>

    <?php if ($getSettings->pwa_enable == 1) {  ?>
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
                // console.log('Service Worker registered successfully: ', registration.scope);
            }, function (err) {
                // registration failed :(
                console.log('Service Worker registration failed: ', err);
            });
        }
    </script>
    <?php } ?>

    <!-- Theme Color Options -->
    <script type="text/javascript">
        document.documentElement.style.setProperty('--thm-primary', '<?php echo $getSettings->primary_color ?>');
        document.documentElement.style.setProperty('--thm-header-text', '<?php echo $getSettings->heading_text_color ?>');
        document.documentElement.style.setProperty('--thm-text', '<?php echo $getSettings->text_color ?>');
        document.documentElement.style.setProperty('--thm-menu-bg', '<?php echo $getSettings->menu_bg_color ?>');
        document.documentElement.style.setProperty('--thm-menu-color', '<?php echo $getSettings->menu_text_color ?>');
        document.documentElement.style.setProperty('--thm-footer-bg', '<?php echo $getSettings->footer_bg_color ?>');
        document.documentElement.style.setProperty('--thm-footer-text', '<?php echo $getSettings->footer_text_color ?>');
    </script>

    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
        var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
        $(function($) {
            $.ajaxSetup({
                cache: false,
                data: csrfData
            });
        });
    </script>

    <!-- Custom Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .navbar-area .nav-inner .navbar .navbar-brand img {
            max-width: 180px;
        }
        .section-title h2 {
            font-weight: 600;
        }
        .btn {
            border-radius: 50px;
            padding: 14px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn.btn-alt {
            background-color: #fff;
            color: var(--thm-primary);
            border: 2px solid var(--thm-primary);
        }
        .btn.btn-alt:hover {
            background-color: var(--thm-primary);
            color: #fff;
        }
        .hero-area {
             padding: 220px 0;
        }
        .hero-content h1 {
            font-size: 48px;
            font-weight: 700;
        }
        .single-feature {
            padding: 40px 30px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        .single-feature:hover {
             transform: translateY(-10px);
             box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .single-feature i {
            font-size: 40px;
            color: var(--thm-primary);
            margin-bottom: 20px;
            display: inline-block;
        }
        .pp-plans {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all .3s ease;
        }
        .pp-plans:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        .pp-plans-featured-label {
             background-color: var(--thm-primary);
        }
        .pp-plans .plans-purchase {
            border-radius: 50px;
            width: 100%;
        }

        /* Testimonials Section */
        .testimonials {
            background-color: #f8f9fa;
        }
        .single-testimonial {
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 10px 30px -15px rgba(0,0,0,0.1);
            margin: 15px;
            text-align: center;
        }
        .single-testimonial .text p {
            font-style: italic;
            color: #555;
            font-size: 16px;
            line-height: 1.7;
        }
        .single-testimonial .author {
            margin-top: 25px;
        }
        .single-testimonial .author img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .single-testimonial .author .name {
            font-weight: 600;
            color: #333;
            font-size: 18px;
        }
        .single-testimonial .author .deg {
            display: block;
            color: var(--thm-primary);
            font-size: 14px;
            margin-top: 5px;
        }
        .contact-form {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        .footer .f-link ul li a i {
            margin-right: 10px;
        }
        .footer .social li a {
            width: 40px;
            height: 40px;
            line-height: 40px;
        }
        /* Brands Section */
        .brands {
            padding-top: 40px;
            padding-bottom: 40px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }
        .brands-logo-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        .brand-logo {
            margin: 15px 25px;
            text-align: center;
            opacity: 0.6;
            transition: all 0.3s ease;
        }
        .brand-logo:hover {
            opacity: 1;
        }
        .brand-logo img {
            max-width: 140px;
            filter: grayscale(100%);
        }
        .brand-logo:hover img {
            filter: grayscale(0%);
        }

        /* Hero Shapes */
        .hero-area {
            position: relative;
            overflow: hidden;
        }
        .hero-area .container {
            position: relative;
            z-index: 2;
        }
        .hero-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            animation: hero-shapes 20s infinite linear;
            bottom: -100px;
        }
        .hero-shapes .shape-1 {
            width: 120px;
            height: 120px;
            left: 5%;
            animation-delay: 0s;
        }
        .hero-shapes .shape-2 {
            width: 50px;
            height: 50px;
            left: 25%;
            animation-delay: 4s;
        }
        .hero-shapes .shape-3 {
            width: 80px;
            height: 80px;
            right: 20%;
            animation-delay: 8s;
        }
        .hero-shapes .shape-4 {
            width: 60px;
            height: 60px;
            right: 5%;
            animation-delay: 12s;
        }
        @keyframes hero-shapes {
            0% {
                transform: translateY(0) scale(1) rotate(0deg);
            }
            100% {
                transform: translateY(-120vh) scale(1.2) rotate(360deg);
            }
        }

        /* Feature section style update */
        .features .single-feature {
            background: transparent !important;
            box-shadow: none !important;
            padding: 25px 5px !important;
            margin-bottom: 0 !important;
            text-align: left !important;
            border-top: 1px solid #f0f0f0;
            transition: none !important;
            border-radius: 0 !important;
        }
        .features .col-lg-4:nth-child(1) .single-feature,
        .features .col-lg-4:nth-child(2) .single-feature,
        .features .col-lg-4:nth-child(3) .single-feature {
            border-top: none;
        }

        .features .single-feature:hover {
             transform: none !important;
             box-shadow: none !important;
        }

        .features .single-feature .icon {
            display: block !important;
            float: left;
            margin-right: 15px !important;
            font-size: 24px !important;
            color: #E91E63 !important;
            width: 55px;
            height: 55px;
            line-height: 55px;
            text-align: center;
            background: rgba(233, 30, 99, 0.1) !important;
            border-radius: 50% !important;
            margin-bottom: 0 !important;
        }
        .feature-content {
             overflow: hidden;
        }
        .features .single-feature h3 {
            font-size: 18px !important;
            font-weight: 600;
            margin-top: 0px;
            margin-bottom: 5px;
        }
        .features .single-feature p {
            color: #666;
            font-size: 14px !important;
            margin-bottom: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <header class="header navbar-area">
        <div class="container-md">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="nav-inner">
                        <!-- Start Navbar -->
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="<?php echo base_url() ?>">
                                <img src="<?=$this->application_model->getBranchImage(get_loggedin_branch_id(), 'logo-small')?>" alt="Logo">
                            </a>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item">
                                        <a href="#home" class="page-scroll active" aria-label="Toggle navigation"><?php echo translate('home'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#features" class="page-scroll" aria-label="Toggle navigation"><?php echo translate('features'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#pricing" class="page-scroll" aria-label="Toggle navigation"><?php echo translate('pricing'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#testimonials" class="page-scroll" aria-label="Toggle navigation">Testimonials</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#faq" class="page-scroll" aria-label="Toggle navigation"><?php echo translate('faq'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#contact" class="page-scroll" aria-label="Toggle navigation"><?php echo translate('contact'); ?></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- navbar collapse -->
                            <div class="header-btn">
                            <div class="button add-list-button">
                                <?php if (!is_loggedin()) { ?>
                                <a href="<?php echo base_url('authentication/index') ?>" class="btn"><?php echo translate('login'); ?></a>
                                <?php } else { ?>
                                    <a href="<?php echo base_url('dashboard/index') ?>" class="btn"><?php echo translate('dashboard'); ?></a>
                                <?php } ?>
                            </div>
                            <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            </div>
                        </nav>
                        <!-- End Navbar -->
                    <?php if ($this->session->flashdata('website_expired_msg')) { ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-2">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $getSettings->expired_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </header>
    <!-- End Header Area -->

    <!-- Start Slider Area -->
    <section id="home" class="hero-area" style="background-image: url(<?php echo base_url('assets/frontend/images/saas/' . $getSettings->slider_bg_image) ?>);">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="hero-content">
                        <h1 class="wow fadeInLeft" data-wow-delay=".4s"><?php echo $getSettings->slider_title; ?></h1>
                        <p class="wow fadeInLeft" data-wow-delay=".6s"><?php echo $getSettings->slider_description; ?></p>
                        <div class="button wow fadeInLeft" data-wow-delay=".8s">
                        <?php if (!empty($getSettings->button_text_1)) { 
                            echo '<a href="' .  $getSettings->button_url_1 . '" class="btn">' . $getSettings->button_text_1 . '</a>';
                        }
                        if (!empty($getSettings->button_text_2)) {
                             echo '<a href="' . $getSettings->button_url_2 . '" class="btn btn-alt">' . $getSettings->button_text_2 . '</a>';
                        }
                        ?></div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <div class="hero-image wow fadeInRight" data-wow-delay=".4s">
                        <img src="<?php echo base_url('assets/frontend/images/saas/' . $getSettings->slider_image) ?>" alt="#">
                    <?php if ($getSettings->overly_image_status == 1) { ?>
                        <img class="overly" src="<?php echo base_url('assets/frontend/images/saas/' . $getSettings->overly_image) ?>" alt="#">
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Slider Area -->

    <!-- Start Brands Area -->
    <section id="brands" class="brands">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4 style="text-transform: uppercase; font-weight: 600; color: #aaa; font-size: 16px; letter-spacing: 1px;">
                        Trusted by Leading Educational Institutions in Bangladesh
                    </h4>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="brands-logo-wrapper">
                        <div class="brand-logo">
                            <img src="https://placehold.co/150x60/f0f0f0/ccc?text=Ideal+School" alt="Ideal School and College">
                        </div>
                        <div class="brand-logo">
                            <img src="https://placehold.co/150x60/f0f0f0/ccc?text=Dhaka+Residential" alt="Dhaka Residential Model College">
                        </div>
                        <div class="brand-logo">
                            <img src="https://placehold.co/150x60/f0f0f0/ccc?text=Viqarunnisa+Noon" alt="Viqarunnisa Noon School">
                        </div>
                        <div class="brand-logo">
                            <img src="https://placehold.co/150x60/f0f0f0/ccc?text=Rajuk+Uttara" alt="Rajuk Uttara Model School & College">
                        </div>
                        <div class="brand-logo">
                            <img src="https://placehold.co/150x60/f0f0f0/ccc?text=St.+Joseph" alt="St. Joseph Higher Secondary School">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Brands Area -->

    <!-- Start Features Area -->
    <section id="features" class="features section" style="background-image: url(<?php echo base_url('assets/frontend/images/saas/features-bg.png') ?>)">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Features</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Countless Key Features</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">We have highlighted some exceptional and unique features of our system. Using our app will create an experience you won't find in any other script. Also our system has many more useful modules and features, for that you can check our demo carefully.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-school-flag"></i></span>
                        <div class="feature-content">
                            <h3>School Subscription (Saas)</h3>
                            <p>The best solution to start your school business
    with powerful Saas modules.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-link"></i></span>
                        <div class="feature-content">
                            <h3>Custom Domains</h3>
                            <p>Allow your users to use their own domain name instead of the default URL.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-qrcode"></i></span>
                        <div class="feature-content">
                            <h3>QR Attendance</h3>
                            <p>Attendance taking facility by scanning the QR code of student or employee ID card.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-shield-halved"></i></span>
                        <div class="feature-content">
                            <h3>Two Factor Authentication</h3>
                            <p>That helps enhance the security of your Ramom school accounts.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-coins"></i></span>
                        <div class="feature-content">
                            <h3>Powerfull Fees Management</h3>
                            <p>You can maintain your institution student fees in very flexible way (Discount, Fine, Fee reminder).</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-laptop-file"></i></span>
                        <div class="feature-content">
                            <h3>Online Exam (Paid/Free)</h3>
                            <p>Admin/teacher can manage online exam and student can participate. Also can offer <strong>free and paid</strong>.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-credit-card"></i></span>
                        <div class="feature-content">
                            <h3>Accept Payments</h3>
                            <p>Our System accept <strong>Online / Offline</strong> Online payments from your bKash, Nagad, Rocket, etc. </p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-globe"></i></span>
                        <div class="feature-content">
                            <h3>Advance Website</h3>
                            <p>This School management system has powerful frontend and highly customizable.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-layer-group"></i></span>
                        <div class="feature-content">
                            <h3>Multi-Type of Exam System</h3>
                            <p>You can set three types of Exam (<strong>Mark, GPA, Mark & GPA</strong>). Multi type of mark distribution.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-money-check-dollar"></i></span>
                        <div class="feature-content">
                            <h3>Online Payment Gateway</h3>
                            <p>Our system has more than 13 popular  payment gateways available.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-calculator"></i></span>
                        <div class="feature-content">
                            <h3>Office Accounting</h3>
                            <p>Record Expenses & Income and Free Collection can be automatically linked to Office accounts.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-video"></i></span>
                        <div class="feature-content">
                            <h3>Online Live Class</h3>
                            <p><strong>Zoom meeting, BigBlueButton And Gmeet</strong> are integrated into our system for live classes.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-user-plus"></i></span>
                        <div class="feature-content">
                            <h3>Online Admission</h3>
                            <p>Students can apply for online  admission and <strong>pay admission fee</strong> through the website.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-square-plus"></i></span>
                        <div class="feature-content">
                            <h3>Custom Field</h3>
                            <p>You can add custom fields in student, employee, online admission form as per your requirement.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-chart-line"></i></span>
                        <div class="feature-content">
                            <h3>Progress Report Card</h3>
                            <p>The results of multiple terms  exam can be combined to make a progress card (<strong>with cumulative average, class Average, subject position</strong>).</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-users-gear"></i></span>
                        <div class="feature-content">
                            <h3>Role Permission</h3>
                            <p>Our system has <strong>8 types of roles</strong> by default and you can add roles according to your needs and manage their permissions.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-calendar-check"></i></span>
                        <div class="feature-content">
                            <h3>Schedule Email / SMS</h3>
                            <p>You can send bulk sms or email to users and you can set the time and date schedule according to your needs.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.4s">
                       <span class="icon"><i class="fas fa-boxes-stacked"></i></span>
                        <div class="feature-content">
                            <h3>Inventory Module</h3>
                            <p>An inventory management modules is a powerful tool that businesses utilize to efficiently monitor, control, and optimize their inventory.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.6s">
                       <span class="icon"><i class="fas fa-id-card"></i></span>
                        <div class="feature-content">
                            <h3>Card Management</h3>
                            <p>With this module you can easily create your own template and use it to create Student ID Card, Admit Card, Certificate and Employee ID Card.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay="0.2s">
                       <span class="icon"><i class="fas fa-mobile-screen-button"></i></span>
                        <div class="feature-content">
                            <h3>Progressive Web App (PWA)</h3>
                            <p>A progressive web app (PWA) is an app that's built using web platform technologies, but that provides a user experience like that of a platform-specific app.</p>
                        </div>
                    </div>
                    <!-- End Single Feature -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Features Area -->

    <!-- Start Pricing Table Area -->
    <section id="pricing" class="pricing-table section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s"><?php echo translate('pricing'); ?></h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s"><?php echo $getSettings->price_plan_title; ?></h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s"><?php echo $getSettings->price_plan_description; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php 
                $delaycount = .2;
                $count = 0;
                $currency_symbol = $global_config['currency_symbol'];
                foreach ($getPackageList as $key => $value) {
                 ?>
                <div class="col-md-6 col-xl-4 col-xxl-3 pp-plans-container wow fadeInUp" data-wow-delay="<?php echo $delaycount ?>s">
                    <div class="pp-plans<?php echo $value->recommended == 1 ? ' pxp-is-featured' : '' ?>">
                        <div class="pp-plans-top">
                        <?php if ($value->recommended == 1) { ?>
                            <div class="pp-plans-featured-label">Recommended</div>
                        <?php } ?>
                            <div class="pp-plans-title">
                                <?php echo $value->name; ?>
                            </div>
                            <div class="pp-plans-price">
                                <div class="pxp-plans-price-monthly">


                        <?php if ($value->discount == 0) { ?>
                                <?php echo $currency_symbol . number_format($value->price, 1, '.', '') ?><span>/ <?php echo ($value->period_value == 0 ? '' : $value->period_value) . " " . $getPeriodType[$value->period_type] ?></span>
                        <?php } else { ?>
                            <div class="discount">
                                <?php echo $currency_symbol . number_format($value->price, 1, '.', '') ?>
                            </div>
                            <?php echo $currency_symbol . number_format(($value->price - $value->discount), 1, '.', ''); ?><span>/ <?php echo ($value->period_value == 0 ? '' : $value->period_value) . " " . $getPeriodType[$value->period_type]?> </span>
                        <?php } ?>
                                </div>
                            </div>
                            <div class="pp-plans-list">
                                <ul class="list-unstyled pricing-feature-list">
                                    <li><i class="lni lni-user"></i>Student Limit : <?php echo $value->student_limit ?></li>
                                    <li><i class="lni lni-user"></i>Parents Limit : <?php echo $value->parents_limit ?></li>
                                    <li><i class="lni lni-user"></i>Staff Limit : <?php echo $value->staff_limit ?></li>
                                    <li><i class="lni lni-user"></i>Teacher Limit : <?php echo $value->teacher_limit ?></li>
                                    <?php
                                    if (empty($value->permission) || $value->permission == 'null' ) {
                                        $permissions = [];
                                    } else {
                                        $permissions = json_decode($value->permission, true);
                                    }
                                    $this->db->select('*');
                                    $this->db->from('permission_modules');
                                    $this->db->where('permission_modules.in_module', 1);
                                    $this->db->order_by('permission_modules.prefix', 'asc');
                                    $modules = $this->db->get()->result();
                                    foreach ($modules as $key => $value2) {
                                        ?>
                                        <li><i class="<?php echo (in_array($value2->id, $permissions) ? 'lni lni-checkmark' : 'lni lni-close'); ?>"></i> <?php echo $value2->name ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="pp-plans-bottom">
                            <div class="pp-plans-cta button">
                                <?php if (is_admin_loggedin()) {
                                    ?>
                                    <a class="btn plans-purchase" href="<?php echo base_url('subscription/renew?id=' . $value->id) ?>"> <?php echo translate('renew'); ?></a>
                                <?php } else { ?>
                                    <button class="btn plans-purchase" data-id="<?php echo $value->id ?>" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"> <?php echo $getSettings->price_plan_button; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                if ($count < 3) {
                    $count++;
                    $delaycount += .2;
                } else {
                    $count = 0;
                    $delaycount = .2;
                } 
        } ?>
            </div>
        </div>
        <div class="bg-ring-right"></div>
    </section>
    <!--/ End Pricing Table Area -->

    <!-- Start Testimonials Area -->
    <section id="testimonials" class="testimonials section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Testimonials</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">What Our Clients Say</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">Discover how schools have streamlined their management, communication, and learning processes.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="single-testimonial">
                        <div class="author">
                            <img src="https://placehold.co/80x80/6e8fd4/fff?text=AR" alt="Abdur Rahman">
                            <h4 class="name">Abdur Rahman
                                <span class="deg">Principal, Ideal School and College</span>
                            </h4>
                        </div>
                        <div class="text">
                            <p>"This software is a game-changer. Our administrative workload has reduced by half, and communication with parents is seamless."</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-testimonial">
                        <div class="author">
                            <img src="https://placehold.co/80x80/6e8fd4/fff?text=FS" alt="Fatima Sultana">
                            <h4 class="name">Fatima Sultana
                                <span class="deg">IT Coordinator, Viqarunnisa Noon School</span>
                            </h4>
                        </div>
                        <div class="text">
                            <p>"Incredibly intuitive and powerful. The support team is always responsive and helpful. Highly recommended for any educational institution in Bangladesh."</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".8s">
                    <div class="single-testimonial">
                        <div class="author">
                            <img src="https://placehold.co/80x80/6e8fd4/fff?text=SM" alt="Shamsul Islam">
                            <h4 class="name">Shamsul Islam
                                <span class="deg">Director, Notre Dame College</span>
                            </h4>
                        </div>
                        <div class="text">
                            <p>"The features for tracking student progress and managing fees are outstanding. It has brought a new level of efficiency to our institution."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Testimonials Area -->

    <!-- Start Faq Area -->
    <section class="faq section" id="faq">
        <div class=container>
            <div class=row>
                <div class=col-12>
                    <div class=section-title>
                        <h3 class="wow zoomIn" data-wow-delay=.2s><?php echo translate('faq'); ?></h3>
                        <h2 class="wow fadeInUp" data-wow-delay=.4s><?php echo $getSettings->faq_title; ?></h2>
                        <p class="wow fadeInUp" data-wow-delay=.6s><?php echo $getSettings->faq_description; ?></p>
                    </div>
                </div>
            </div>
            <div class=row>
                <div class=col-12>
                    <div class=accordion id=accordionExample>
                        <?php
                        $count = 1;
                        foreach ($faqs as $key => $faq) {
                            ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $key ?>">
                                <button class="accordion-button collapsed" type=button data-bs-toggle=collapse data-bs-target="#faq<?php echo $key ?>" aria-expanded="<?php echo $key == 0 ? 'true' : ''; ?>" aria-controls=collapseOne>
                                    <span class=title><span class="serial"><?php echo $count++; ?></span><?php echo $faq->title; ?></span><i class="lni lni-plus"></i>
                                </button>
                            </h2>
                            <div id="faq<?php echo $key ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $key ?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body"><?php echo $faq->description; ?></div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End faq Area -->

    <!-- Start Contact Area -->
    <section class="section call-action" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="contact-form">
                        <?php echo form_open('saas_website/send_email', array('class' => 'contact-frm')); ?>
                        <h3 class="mb-5"><?php echo $getSettings->contact_title; ?></h3>
                        <?php if($this->session->flashdata('msg_success')): ?>
                        <div class="alert alert-success">
                            <i class="icon-text-ml far fa-check-circle"></i> <?php echo $this->session->flashdata('msg_success'); ?>
                        </div>
                        <?php endif; ?>
                        <?php if($this->session->flashdata('msg_error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('msg_error'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group wow fadeInUp" data-wow-delay=".2s">
                            <input type="text" class="form-control" name="name" autocomplete="off" placeholder="Name *" value="">
                            <span class="error"></span>
                        </div>
                        <div class="form-group wow fadeInUp" data-wow-delay=".2s">
                            <input type="text" class="form-control" name="email" autocomplete="off" placeholder="Email *" value="">
                            <span class="error"></span>
                        </div>
                        <div class="form-group wow fadeInUp" data-wow-delay=".4s">
                            <input type="text" class="form-control" name="mobile" autocomplete="off" placeholder="Mobile *" value="">
                            <span class="error"></span>
                        </div>
                        <div class="form-group wow fadeInUp" data-wow-delay=".4s">
                            <input type="text" class="form-control" name="subject" autocomplete="off"  placeholder="Subject  *" value="">
                            <span class="error"></span>
                        </div>
                        <div class="form-group wow fadeInUp" data-wow-delay=".6s">
                            <textarea type="text" rows="5" class="form-control alert_settings" placeholder="Type Message *" name="message"></textarea>
                            <span class="error"></span>
                        </div>
                        <div class="button wow fadeInUp" data-wow-delay=".8s">
                            <button class="btn btn-alt" type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-envelope"></i> <?php echo $getSettings->contact_button; ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <h2 class="contact-title wow fadeInUp" data-wow-delay=".2s"><?php echo $getSettings->contact_description; ?></h2>
                    <div class="contact-item-wrapper">
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-12">
                                <div class="contact-item wow fadeInUp" data-wow-delay=".4s">
                                    <div class="contact-icon">
                                        <i class="lni lni-phone"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4><?php echo translate('phone'); ?></h4>
                                        <p><?php echo $global_config['mobileno'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-12">
                                <div class="contact-item wow fadeInUp" data-wow-delay=".6s">
                                    <div class="contact-icon">
                                        <i class="lni lni-envelope"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4><?php echo translate('email'); ?></h4>
                                        <p><?php echo $global_config['institute_email'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-12">
                                <div class="contact-item wow fadeInUp" data-wow-delay=".8s">
                                    <div class="contact-icon">
                                        <i class="lni lni-map-marker"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4><?php echo translate('address'); ?></h4>
                                        <p><?php echo $global_config['address'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Call To Action Area -->

    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Start Footer Top -->
        <div class="footer-top">
            <div class="container mb-5">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-about">
                            <div class="logo">
                                <a href="<?php echo base_url() ?>">
                                    <img src="<?=$this->application_model->getBranchImage(get_loggedin_branch_id(), 'logo-small')?>" alt="#">
                                </a>
                            </div>
                            <p><?php echo $getSettings->footer_about; ?></p>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12">
                                <!-- Single Widget -->
                                <div class="single-footer f-link">
                                    <h3>Quick Link</h3>
                                    <ul>
                                        <li><a href="#home" class="page-scroll active" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> <?php echo translate('home'); ?></a></li>
                                        <li><a href="#features" class="page-scroll" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> <?php echo translate('features'); ?></a></li>
                                        <li><a href="#pricing" class="page-scroll" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> <?php echo translate('pricing'); ?></a></li>
                                        <li><a href="#testimonials" class="page-scroll" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> Testimonials</a></li>
                                        <li><a href="#faq" class="page-scroll" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> <?php echo translate('faq'); ?></a></li>
                                        <li><a href="#contact" class="page-scroll" aria-label="Toggle navigation"><i class="fas fa-angle-right"></i> <?php echo translate('contact'); ?></a></li>
                                        <?php if ($getSettings->terms_status == 1) { ?>
                                        <a href="javascript:void(0)" id="termsFooter"><i class="fas fa-angle-right"></i> <?php echo translate('terms_&_conditions'); ?></a>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!-- End Single Widget -->
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <!-- Single Widget -->
                                <div class="single-footer f-link address">
                                    <h3>Address</h3>
                                    <ul>
                                        <li class="clearfix"><i class="lni lni-map-marker"></i> <div style="margin-left: 47px;"><?php echo $global_config['address'] ?></div></li>
                                        <li class="clearfix"><i class="lni lni-phone"></i> <?php echo $global_config['mobileno'] ?></li>
                                        <li class="clearfix"><i class="lni lni-envelope"></i> <?php echo $global_config['institute_email'] ?></li>
                                    </ul>
                                </div>
                                <!-- End Single Widget -->
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <!-- Single Widget -->
                                <div class="single-footer f-link">
                                    <h3>Social Link</h3>
                                    <ul class="social">
                                        <li><a href="<?php echo $global_config['facebook_url'] ?>"><i class="lni lni-facebook-filled"></i></a></li>
                                        <li><a href="<?php echo $global_config['twitter_url'] ?>"><i class="lni lni-twitter-original"></i></a></li>
                                        <li><a href="<?php echo $global_config['instagram_url'] ?>"><i class="lni lni-instagram"></i></a></li>
                                        <li><a href="<?php echo $global_config['linkedin_url'] ?>"><i class="lni lni-linkedin-original"></i></a></li>
                                        <li><a href="<?php echo $global_config['youtube_url'] ?>"><i class="lni lni-youtube"></i></a></li>
                                        <li><a href="<?php echo $global_config['google_plus_url'] ?>"><i class="lni lni-google"></i></a></li>
                                    </ul>
                                </div>
                                <!-- End Single Widget -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container d-flex justify-content-between align-items-center">
                    <div class="copyright-text">
                        <div class="footer-copyright__content">
                            <span><?php echo $global_config['footer_text']; ?></span>
                        </div>
                    </div>
                    <div class="payment-logo">
                        <img src="<?php echo base_url('assets/frontend/images/saas/' . $getSettings->payment_logo); ?>" alt="">
                    </div>
                </div>
            </div>

        </div>
        <!--/ End Footer Top -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <script src="<?php echo base_url('assets/frontend/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/wow.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/tiny-slider.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/glightbox.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/saas_main.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/select2/js/select2.full.min.js');?>"></script>
    <script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
</body>
</html>

<!-- Modal -->
<div class="modal fade" id="regModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">School Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('saas_website/register', array('class' => 'school-reg frm-submit-data')); ?>
                <input type="hidden" name="package_id" value="" id="packageID">
                <section class="card pg-fw mb-4 mt-2">
                    <div class="card-body">
                        <h5 class="chart-title mb-xs">Plan Summary</h5>
                        <div class="mt-2">
                            <ul class="sp-summary" id="summary">
                            </ul>
                        </div>
                    </div>
                </section>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="school_name">School Name *</label>
                            <input id="school_name" name="school_name" type="text" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="school_address">School Address *</label>
                            <input id="school_address" name="school_address" type="text" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="photo">School Logo</label>
                            <input class="form-control" type="file" accept="image/*" id="photo" name="logo_file">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="school_info">Message</label>
                            <textarea name="message" id="message" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="admin_name">Admin Name *</label>
                            <input id="admin_name" name="admin_name" type="text" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select class="form-select" id="gender" name="gender" data-minimum-results-for-search='Infinity'>
                                <option value="">Select a gender</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="admin_phone">Contact Number *</label>
                            <input id="admin_phone" name="admin_phone" type="tel" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="admin_email">Contact Email *</label>
                            <input name="admin_email" type="text" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="admin_password">Admin Login Username *</label>
                            <input name="admin_username" type="text" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="admin_password">Admin Login Password *</label>
                            <input name="admin_password" type="password" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="admin_password">Retype Password *</label>
                            <input name="retype_admin_password" type="password" class="form-control" autocomplete="off">
                            <span class="error"></span>
                        </div>
                    <?php if ($getSettings->terms_status == 1) { ?>
                        <div class="form-group">
                            <div class="checkbox-replace">
                                <label class="i-checks"><input type="checkbox" name="terms_cb"><i></i> <?php echo $getSettings->agree_checkbox_text ?></label>
                            </div>
                            <span class="error"></span>
                        </div>
                    <?php } ?>

                    <?php if ($getSettings->captcha_status == 1): ?>
                        <div class="form-group">
                            <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                            <span class="error"></span>
                        </div>
                    <?php endif; ?>
                    <div class="pp-plans-bottom">
                            <div class="pp-plans-cta button">
                                <button class="btn mb-4" data-id="1" type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"> Register & Payment</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($getSettings->terms_status == 1) { ?>
<div class="modal fade" id="termsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: #000;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php
$alertclass = "";
if($this->session->flashdata('alert-message-success')){
    $alertclass = "success";
} else if ($this->session->flashdata('alert-message-error')){
    $alertclass = "error";
} else if ($this->session->flashdata('alert-message-info')){
    $alertclass = "info";
}
if($alertclass != ''):
    $alert_message = $this->session->flashdata('alert-message-'. $alertclass);
?>
    <script type="text/javascript">
        swal({
            toast: true,
            position: 'top-end',
            type: '<?php echo $alertclass?>',
            title: '<?php echo $alert_message?>',
            confirmButtonClass: 'btn btn-1',
            buttonsStyling: false,
            timer: 8000
        })
    </script>
<?php endif; ?>
<script type="text/javascript">
    $("#gender").select2({
        width: "100%"
    });

    $("form.frm-submit-data").each(function(i, el) {
        var $this = $(el);
        $this.on('submit', function(e) {
            e.preventDefault();
            var btn = $this.find('[type="submit"]');
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    btn.button('loading');
                },
                success: function(data) {
                    console.log(data);
                    $('.error').html("");
                    if (data.status == "fail") {
                        $.each(data.error, function(index, value) {
                            $this.find("[name='" + index + "']").parents('.form-group').find('.error').html(value);
                        });
                    } else {
                        if (data.url) {
                            window.location.href = data.url;
                        } else if (data.status == "access_denied") {
                            window.location.href = base_url + "dashboard";
                        } else if (data.status == "error") {
                            $('#regModal').modal('hide');
                            swal({
                                title: data.title,
                                text: data.message,
                                buttonsStyling: false,
                                showCloseButton: true,
                                focusConfirm: false,
                                confirmButtonClass: "btn swal2-btn-default",
                                type: "error"
                            });
                        } else {
                            location.reload(true);
                        }
                    }
                },
                complete: function() {
                    btn.button('reset');
                },
                error: function() {
                    btn.button('reset');
                }
            });
        });
    });
</script>