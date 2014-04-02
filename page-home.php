<?php
/*
Template Name: Homepage Template
*/
?>
<?php get_header(); ?>

	<main role="main">
		<section id="banner" class="fixed">
			<h3 class="banner-text">
				<span>Get hitched</span>
				<span>on the quick</span>
			</h3>
			<a class="button banner-button button-shadow" href="/book">book now ></a>
			<img id="home-banner" src="wp-content/themes/avow/img/homepage.jpg">
		</section>
		
		<section id="content" class="top-box-shadow">
			<ul id="packages">
				<li>
					<h2>The Hawthorne</h2>
					<ul class="features">
						<li>60 minutes of chapel time</li>
						<li>Officiant</li>
						<li>Farm fresh flowers</li>
						<li>Professional photographer</li>
						<li>20 prints</li>
					</ul>
				</li>	
				<li>
					<h2>The Morrison</h2>
					<ul class="features">
						<li>60 minutes of chapel time</li>
						<li>Officiant</li>
						<li>Farm fresh flowers</li>
						<li>Professional photographer</li>
						<li>40 prints</li>
						<li class="extra">Champagne toast</li>
					</ul>
				</li>	
				<li>
					<h2>The Marquam</h2>
					<ul class="features">
						<li>90 minutes of chapel time</li>
						<li>Officiant</li>
						<li>Farm fresh flowers</li>
						<li>Professional photographer</li>
						<li>60 prints</li>
						<li class="extra">Champagne toast</li>
					</ul>
				</li>	
			</ul>		
		</section>
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
