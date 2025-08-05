<?php
/** Workshops Template, page-workshop.php */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// First query to search titles and content
$args1 = array(
    'post_type' => 'workshop',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
);
?>

<div class="row">
    <div class="col-md-12" style="margin-top: 50px;">
        <!-- get the content of the page -->
        <?php if (have_posts()) : while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
    </div>
</div>

<section id="stories-app">

    <h1>Workshops</h1>

    <div class="workshop-wrap">
        <?php
            // Query all workshops ordered by the custom 'date' field
            $workshops = new WP_Query(array(
                'post_type' => 'workshop',
                'posts_per_page' => -1,
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'DESC',
                'meta_type' => 'DATE'
            ));

            if ($workshops->have_posts()) {
                while ($workshops->have_posts()) {
                    $workshops->the_post();
                    $workshop_link = get_the_permalink();
                    $workshop_title = get_the_title();
                    $excerpt = get_field('excerpt');
                    $date = get_field('date');

                    // Get all 'department' terms
                    $terms = get_the_terms(get_the_ID(), 'category');
                    $category_names = '';

                    if ($terms && !is_wp_error($terms)) {
                        $term_names = wp_list_pluck($terms, 'name');
                        $category_names = implode(', ', $term_names);
                    } else {
                        $category_names = 'Uncategorized';
                    }

                    // Display the workshop
                    echo "<a class='workshop-element' href='{$workshop_link}'>
                            <div class='workshop-details'>
                                <p class='workshop-category'>{$category_names}</p>
                                <h2 style='font-size:24px;margin-bottom:0px;'>{$workshop_title}</h2>
                                <p class='workshop-date'>{$date}</p>
                                <p class='workshop-excerpt'>{$excerpt}</p>
                            </div>
                          </a>";
                }
                wp_reset_postdata();
            } else {
                echo "<div class='no-facilities-message'>
                        <p>No workshops found. Please check back later.</p>
                      </div>";
            }
        ?>
    </div>
</section>

<?php get_footer(); ?>