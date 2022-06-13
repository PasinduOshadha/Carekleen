<?php
/*
Template Name: Services Page
Template Post Type: services
*/

get_header();

// get service description

if (get_field('service_description')) {
	$description = get_field('service_description');
}

?>

<div id="primary" class="content-area">
	<?php
	// getting page featued image
	if (has_post_thumbnail($post->ID)) :
		$featured_image_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'large');
		print_r($url);
	endif;
	?>
	<div class="services-page-cover" style="background: url(<?php echo esc_html($featured_image_url); ?>);">
		<div class="services-page-text-wrapper">
			<h2 class="title"> <?php the_title(); ?> </h2>
			<p class="description"><?php echo $description; ?></p>
		</div>
	</div>
	<div class="content-container site-container">
		<main id="main" class="site-main" role="main">
			<?php
			/**
			 * Hook for anything before main content
			 */
			do_action('kadence_before_main_content');
			?>

			<div class="content-wrap">
				<div class="services-page-content">
					<?php

					do_action('kadence_single_content');

					?>
				</div>
			</div>
			<?php
			/**
			 * Hook for anything after main content
			 */
			do_action('kadence_after_main_content');
			?>
		</main><!-- #main -->
		<?php
		get_sidebar();
		?>
	</div>
</div><!-- #primary -->

<?php
get_template_part(
	'template_parts/services',
	'carousel',
	$data = array(
		'post_id' => $post->ID,
	)
);
wp_reset_postdata();
get_footer();
