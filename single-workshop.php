<?php
/**
 * The template for displaying single workshop.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<?php while (have_posts()) : the_post();

    $workshop_title = get_the_title();
    $date = get_field('date'); // ACF field

    // Get all 'category' terms
    $terms = get_the_terms(get_the_ID(), 'category');
    $category_names = '';

    if ($terms && !is_wp_error($terms)) {
        $term_names = wp_list_pluck($terms, 'name');
        $category_names = implode(', ', $term_names);
    } else {
        $category_names = 'Uncategorized';
    }
?>

<div id="primary" class="content-area" style="margin:50px 0px;">
    <p class='workshop-category' style='margin-bottom:0px'><?php echo esc_html($category_names); ?></p>
    <h1 style='margin-bottom:0px'><?php echo esc_html($workshop_title); ?></h1>
    <p class='workshop-date'><?php echo esc_html($date); ?></p>

    <div id="workshop_content">
      <?php the_content(); ?>
    </div>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>