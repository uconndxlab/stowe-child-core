<?php

/** Department Template, taxonomy-department.php */
get_header();

// Get the current term object from the URL (this is specific to the taxonomy page)
$term = get_queried_object(); // This automatically retrieves the current term object for taxonomy 'department'

// Start building the query arguments
$args = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
);

// Filter the query by the current department term
$args['tax_query'] = array(
    array(
        'taxonomy' => 'department',
        'field' => 'slug',
        'terms' => $term->slug, // Use the current term slug to filter posts
        'operator' => 'IN',
    ),
);

$facilities = new WP_Query($args);

?>

<section style="margin:20px 0px">
    <h2 style="margin-bottom:0px"><?php echo $term->name; ?></h2>
    <?php echo term_description(); ?>

    <div class="row container">
        <div class="facilities-wrap">
            <?php
            if ($facilities->have_posts()) {
                while ($facilities->have_posts()) {

                    $facilities->the_post();
                    // Get the featured image and caption
                    $facility_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
                    $facility_photo_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

                    $facility_link = get_the_permalink();
                    // Custom fields
                    $name = get_field('name');
            ?>

                    <a class="facilities-element" href="<?php echo $facility_link; ?>">
                        <div class="facility-photo-wrap">
                            <img class="story-photo" src="<?php echo $facility_photo; ?>" width="100%" height="100px" 
                            alt="<?php echo $facility_photo_alt; ?>">
                            <div class="facility-details white">
                                <h3><?php echo $name; ?></h3>
                            </div>
                        </div>
                    </a>

            <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p>No facilities found for this department.</p>';
            }
            ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>
