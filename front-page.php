<?php

/** Department Template, front-page.php */
get_header();

// Get the "core" department term
$term = get_term_by('slug', 'core', 'department');

if ($term) {
    $department_name = $term->name;
    $department_description = term_description($term->term_id, 'department');
}

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
        'terms' => 'core', // display core facilities
        'operator' => 'IN',
    ),
);

$facilities = new WP_Query($args);

?>

<section style="margin:0px 0px">
<div class="row" style="margin: auto;padding:100px 0px;background-image: linear-gradient(90deg, rgba(0, 14, 47,1) 0%, rgba(20, 68, 150,1) 100%),
                    url('https://dmd.dev.uconn.edu/cor2e/wp-content/uploads/sites/8/2025/02/062023-JonathanXVFirstShoots-5-scaled-1.jpg');background-repeat:no-repeat;background-size:cover;background-position:center;">
      <div class="container white" style="display:flex;justify-content:center">
        <div class="col-md-8" style="padding:0px;text-align:center;">
            <h1 style="margin-bottom:0px;font-size:50px;">COR²E
            </h1>
            <p style="font-size:18px">
            COR²E is the old Biotechnology/Bioservices Center renamed, transformed, expanded, and just generally better! Fundamentally, we promote and support the growth of research at UConn by serving the University’s world-class research faculty (as well as the rest of the UConn community).</p>
            <a href="/about.html" target="blank" class="btn btn-primary">About COR²E</a>
        </div>
      </div>
    </div>
    <div class="row container" style="padding:0px 30px;margin:60px auto;">
        <!-- Display the core department name and description -->
        <?php if (isset($department_name) && isset($department_description)) : ?>
            <h2 style="margin-bottom:10px"><?php echo esc_html($department_name); ?> Facilities</h2>
            <p style="margin-bottom:20px"><?php echo wp_strip_all_tags($department_description); ?></p>
        <?php endif; ?>

        <div class="facilities-wrap-element">
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

                    <a class="facilities-element" href="<?php echo esc_url($facility_link); ?>">
                        <div class="facility-photo-wrap">
                            <img class="story-photo" src="<?php echo esc_url($facility_photo); ?>" width="100%" height="100px" 
                            alt="<?php echo esc_attr($facility_photo_alt); ?>">
                            <div class="facility-details white">
                                <h3><?php echo esc_html($name); ?></h3>
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

<div class="row container" style="margin:0 auto;margin-top:28px">
    <div class="col-md-12">
        <!-- Get the content of the page -->
        <?php if (have_posts()) : while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
    </div>
</div>

<?php get_footer(); ?>

<script>
document.getElementById('content').firstElementChild.classList.remove('container');
</script>