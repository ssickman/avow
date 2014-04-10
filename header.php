<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700,300|Trocchi' rel='stylesheet' type='text/css'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        /*
conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
*/
        </script>

	</head>
	<body <?php body_class(); ?>>

		<!-- wrapper -->
		<div class="wrapper" id="top">

			<!-- header -->
			<header class="header clear fixed bottom-box-shadow top-header" role="banner">

					<!-- logo -->
					<a href="<?php echo home_url() ?>" id="logo" class="header-float <?php echo is_front_page() ? 'home-scroll' : '' ?>">avow</a>
					
					<!-- /logo -->

					<!-- nav -->
					<nav class="nav" role="navigation">
						<?php //html5blank_nav(); ?>
						<a href="<?php echo home_url() ?>dd" class="home-scroll"><span>Home</span></a>
						<a href="#venue"><span>Venue</span></a>
						<a href="#availability"><span>Schedule</span></a>
						<a href="#packages"><span>Packages</span></a>
					</nav>
					<!-- /nav -->
				<section style="display:none;">
					112 SE 12th&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;St 304.933.9016 
				</section>
			</header>
			<div class="scrolled reference">&nbsp;</div>
			<!-- /header -->
			