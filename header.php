<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
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
		
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		
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
						<a href="#reserve"><span>Schedule</span></a>
						<a href="#package"><span>Packages</span></a>
					</nav>
					<!-- /nav -->
			</header>
			<nav id="checkout-steps" class="<?php echo !showStepBar() ? 'hidden' : '' ?>">
			<?php foreach (array('package' => 'package', 'reserve' => 'date', 'charge' => 'pay') as $action => $stepName): ?>
				<a href="#<?php echo $action ?>" class="<?php echo $action ?> <?php echo stepCompleted($action) ? 'done' : '' ?>"><?php echo ucfirst($stepName) ?></a>
			<?php endforeach; ?>
			</nav>
			<div class="scrolled reference">&nbsp;</div>
			<!-- /header -->
			