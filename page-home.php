<?php
/*
Template Name: Homepage Template
*/
?>
<?php get_header(); ?>

	<main role="main" class="homepage">
		<section id="banner" class="fixed">
			<h3 class="banner-text">
				<span>Get hitched on the quick</span>
				<a class="button banner-button button-shadow" href="/book">book now ></a>
			</h3>
			<img id="home-banner" src="wp-content/themes/avow/img/homepage.jpg">
		</section>
		<section id="content-wrap">
			<section id="venue">
				<h1>Venue</h1>
				heyo				 
				 
				 <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
				
			</section>
			
			<section id="packages" class="top-box-shadow">
				
				
				<h1>Packages</h1>
				<?php
					$packages = get_posts(array(
						'post_type' => 'package',
						'meta_key'  => 'package_price',
						'orderby'   => 'meta_value_num',
						'order'     => 'ASC',
					));				
				?>
				
				<ul id="">
				<?php 
					foreach ($packages as $p): 
					$m = get_post_meta($p->ID); //print_r($meta); die();
				?>
					<li>
						<h2><?php echo $p->post_title ?></h2>
						<h3><?php echo $m['package_price'][0] ?></h3>
						
						<p>
							Our standard package. Includes everything you need to ensure your special day is one to remember.
						</p>
						
						<ul class="features">
							<?php echo package_format_features($m['package_features'][0]) ?>
						</ul>
						<form method="post" action="/backend" class="select-package">
							<input type="submit" name="package_title" value="Select <?php echo $p->post_title ?>" class="button-1 select-package">
							<input type="hidden" name="package_id" value="<?php echo $post->ID ?>" >
							<input type="hidden" name="nonce" value="<?php wp_create_nonce('package') ?>" >
							<input type="hidden" name="action" value="<?php echo $startingAction ?>" >
						</form>	
					</li>	
						
				<?php endforeach; ?>
				</ul>
			</section>
			<section id="payment">
				<h1>Payment</h1>
				<?php //wp_stripe_form(); ?>
				
				<form action="/charge" method="POST">
					<script
						src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="<?php echo get_stripe_key("stripe_{$siteEnvironment}_public_key") ?>"
						data-amount="2000"
						data-name="Demo Site"
						data-description="2 widgets ($20.00)"
						data-image="/wp-content/themes/avow/img/avow.png">
						</script>
					</form>
			</section>
		</section>
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
