<?php
/*
Template Name: Homepage Template
*/
?>
<?php get_header(); ?>

	<main role="main">
		<section id="banner" class="fixed">
			<h3 class="banner-text">
				<span>Get hitched on the quick</span>
				<a class="button banner-button button-shadow" href="/book">book now ></a>
			</h3>
			<img id="home-banner" src="wp-content/themes/avow/img/homepage.jpg">
		</section>
		
		<section id="packages" class="top-box-shadow">
			<h1>Packages</h1>
			<ul id="">
				<li>
					<h2>The Hawthorne</h2>
					<h3>600</h3>
					
					<p>
						Our standard package. Includes everything you need to ensure your special day is one to remember.
					</p>
					
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
					<h3>900</h3>
					<p>
						When you need just a little more. Double the amount of prints. And an assload more flowers!
					</p>
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
					<h3>1100</h3>
					<p>
						The bees knees. Seriously, if you got any more, you'd divorce just to do it again.
					</p>
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
