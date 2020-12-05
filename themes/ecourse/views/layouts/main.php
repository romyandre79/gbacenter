<!DOCTYPE html>
<html>
<head>
<?php display_seo($this->metatag, $this->description, $this->pageTitle); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/animate.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/LineIcons.2.0.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-5.0.5-alpha.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/lazysizes.min.js"></script> 
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/html5shiv.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

	<!--====== PRELOADER PART START ======-->

	<div class="preloader">
		<div class="loader">
			<div class="ytp-spinner">
				<div class="ytp-spinner-container">
					<div class="ytp-spinner-rotator">
						<div class="ytp-spinner-left">
							<div class="ytp-spinner-circle"></div>
						</div>
						<div class="ytp-spinner-right">
							<div class="ytp-spinner-circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--====== PRELOADER PART ENDS ======-->

	<!--====== HEADER PART START ======-->

	<header class="header_area">
		<div id="header_navbar" class="header_navbar">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-xl-12">
						<nav class="navbar navbar-expand-lg">
							<a class="navbar-brand" href="index.html">
								<img id="logo" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.svg" alt="Logo">
							</a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
								aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								<span class="toggler-icon"></span>
								<span class="toggler-icon"></span>
								<span class="toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
								<ul id="nav" class="navbar-nav ml-auto">
									<li class="nav-item">
										<a class="page-scroll" href="#home">Home</a>
									</li>
									<li class="nav-item">
										<a class="page-scroll" href="#courses">Courses</a>
									</li>
									<li class="nav-item">
										<a class="page-scroll" href="#mentors">Mentors</a>
									</li>
									<li class="nav-item">
										<a class="page-scroll" href="#blog">Blog</a>
									</li>
									<li class="nav-item">
										<a class="page-scroll" href="#contact">Contact</a>
									</li>
									<li class="nav-item">
										<a class="header-btn btn-hover" href="#courses">Get Started</a>
									</li>
								</ul>
							</div> <!-- navbar collapse -->
						</nav> <!-- navbar -->
					</div>
				</div> <!-- row -->
			</div> <!-- container -->
		</div> <!-- header navbar -->
	</header>

	<!--====== HEADER PART ENDS ======-->

	<?php echo $content ?>

	<!--====== FOOTER PART START ======-->
	<footer id="footer" class="footer-area pt-170">
		<div class="container">
			<div class="row">
				<div class="col-xl-3 col-lg-3 col-md-6">
					<div class="footer-widget">
						<a href="index.html" class="logo d-blok">
							<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.svg" alt="">
						</a>
						<p>Lorem ipsum dolor sit amco nsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna .</p>
					</div>
				</div>
				<div class="col-xl-2 col-lg-2 offset-xl-1 offset-lg-1 col-md-6">
					<div class="footer-widget">
						<h5>Quick Links</h5>
						<ul>
							<li><a href="javascript:void(0)">Home</a></li>
							<li><a href="javascript:void(0)">Courses</a></li>
							<li><a href="javascript:void(0)">Eventd</a></li>
							<li><a href="javascript:void(0)">Blog</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xl-2 col-lg-2 col-md-6">
					<div class="footer-widget">
						<h5>Our Course</h5>
						<ul>
							<li><a href="javascript:void(0)">Design</a></li>
							<li><a href="javascript:void(0)">Development</a></li>
							<li><a href="javascript:void(0)">Marketing</a></li>
							<li><a href="javascript:void(0)">SEO Design</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-6">
					<div class="footer-widget">
						<h5>Contact Us</h5>
						<ul>
							<li><p>Phone: +884-9273-3867</p></li>
							<li><p>Email: hello@gmail.com</p></li>
							<li><p>Address: Random Road<br> USA</p></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="footer-credit">
				<div class="row">
					<div class="col-md-6">
						<div class="copy-right text-center text-md-left">
							<p>Designed and Developed by <a href="https://uideck.com" rel="nofollow">UIdeck</a></p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="footer-social">
							<ul class="d-flex justify-content-md-end justify-content-center">
								<li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
								<li><a href="javascript:void(0)"><i class="lni lni-twitter-filled"></i></a></li>
								<li><a href="javascript:void(0)"><i class="lni lni-instagram-filled"></i></a></li>
								<li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!--====== FOOTER PART ENDS ======-->

	<a href="#" class="back-to-top btn-hover"><i class="lni lni-chevron-up"></i></a>
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/wow.min.js"></script>
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/main.js"></script>
	<script>
    var pageLink = document.querySelectorAll('.page-scroll');    
    pageLink.forEach(elem => {
        elem.addEventListener('click', e => {
            e.preventDefault();
            document.querySelector(elem.getAttribute('href')).scrollIntoView({
                behavior: 'smooth',
                offsetTop: 1 - 60,
            });
        });
    });

    // section menu active
    function onScroll(event) {
        var sections = document.querySelectorAll('.page-scroll');
        var scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;

        for (var i = 0; i < sections.length; i++) {
            var currLink = sections[i];
            var val = currLink.getAttribute('href');
            var refElement = document.querySelector(val);
            var scrollTopMinus = scrollPos + 73;
            if (refElement.offsetTop <= scrollTopMinus && (refElement.offsetTop + refElement.offsetHeight > scrollTopMinus)) {
                document.querySelector('.page-scroll').classList.remove('active');
                currLink.classList.add('active');
            } else {
                currLink.classList.remove('active');
            }
        }
    };
    window.document.addEventListener('scroll', onScroll);
    let navbarToggler = document.querySelector(".navbar-toggler");    
    var navbarCollapse = document.querySelector(".navbar-collapse");
    document.querySelectorAll(".page-scroll").forEach(e =>
        e.addEventListener("click", () => {
            navbarToggler.classList.remove("active");
            navbarCollapse.classList.remove('show')
        })
    );
    navbarToggler.addEventListener('click', function() {
        navbarToggler.classList.toggle("active");
    });
	</script>
</body>
</html>