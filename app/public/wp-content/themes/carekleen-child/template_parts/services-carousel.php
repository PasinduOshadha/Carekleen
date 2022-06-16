<?php
// template for the services post carousel

$args = array(
    'post_type' => 'services',
    'post__not_in' => $data['post_id'],
);

$services_query = new WP_Query($args);

?>

<?php if ($services_query->have_posts()) : ?>

    <div class="owl-carousel services-post-carousel-wrapper">

        <?php while ($services_query->have_posts()) : $services_query->the_post() ?>

            <div class="services-post-carousel-card">
                <h4 class="services-post-carousel-item-title"><?php the_title(); ?></h4>
                <div class="services-post-carousel-description"><?php the_field('service_description'); ?></div>
                <a class="services-post-carousel-readmore" href="<?php echo esc_html(the_permalink()); ?>">Read More</a>
            </div>

        <?php endwhile; ?>

    </div>

<?php endif; ?>