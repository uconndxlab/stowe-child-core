<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php
/** Facilities Template, page-facilities.php */
get_header();

// Get terms in the order you want for the department
$department_terms = get_terms(array(
    'taxonomy' => 'department',
    'hide_empty' => false,
    'orderby' => 'name',  // Order alphabetically or adjust as needed
    'order' => 'ASC',     // Ascending order
));

$args = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
);

// Initialize the tax_query
$args['tax_query'] = array('relation' => 'AND');

// If $_GET['aof'] is set, filter by taxonomy terms (department or campus)
if (isset($_GET['department_filter']) && !empty($_GET['department_filter'])) {
    // Handle the department filter
    if ($_GET['department_filter'] !== 'all-departments') {
        $args['tax_query'][] = array(
            'taxonomy' => 'department',
            'field' => 'slug',
            'terms' => $_GET['department_filter'],
            'operator' => 'IN',
        );
    }
}

if (isset($_GET['campus_filter']) && !empty($_GET['campus_filter'])) {
    // Handle the campus filter
    if ($_GET['campus_filter'] !== 'all-campuses') {
        $args['tax_query'][] = array(
            'taxonomy' => 'campus',
            'field' => 'slug',
            'terms' => $_GET['campus_filter'],
            'operator' => 'IN',
        );
    }
}

$facilities = new WP_Query($args);
?>

<div class="row">
    <div class="col-md-12" style="margin-top: 50px;">
        <!-- get the content of the page -->
        <?php if (have_posts()) : while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
    </div>
</div>

<section id="stories-app">
    <div class="row">
        <div class="stories-filter" style="padding:0px">
            <!-- Combined Filter Form -->
            <form method="GET" action="" id="category-filter-form" hx-get="" hx-trigger="change" hx-target=".facilities-wrap" hx-select=".facilities-wrap" hx-swap="outerHTML" hx-indicator=".facilities-wrap">
                <div class="col-lg-4 stories-filter-item" style="margin-bottom:10px">
                    <label for="facility-search">Search</label>
                    <input class="form-control" type="text" placeholder="Search by name, service, etc." aria-label=".form-control" id="facility-search" name="search">
                </div>

                <div class="col-lg-4 stories-filter-item" style="margin-bottom:10px">
                    <label for="department-select">Department</label>
                    <select name="department_filter" id="department-select" class="form-select form-select-lg category-filter-dropdown">
                        <option value="all-departments" <?php echo (empty($_GET['department_filter']) || $_GET['department_filter'] == 'all-departments') ? 'selected' : ''; ?>>All Departments</option>
                        <?php
                        foreach ($department_terms as $term) {
                            $term_slug = $term->slug;
                            $term_name = $term->name;
                            $is_selected = (isset($_GET['department_filter']) && $_GET['department_filter'] == $term_slug) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_attr($term_slug); ?>" <?php echo $is_selected; ?>>
                                <?php echo esc_html($term_name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-lg-4 stories-filter-item" style="margin-bottom:10px">
                    <label for="campus-select">Campus</label>
                    <select name="campus_filter" id="campus-select" class="form-select form-select-lg category-filter-dropdown">
                        <option value="all-campuses" <?php echo (empty($_GET['campus_filter']) || $_GET['campus_filter'] == 'all-campuses') ? 'selected' : ''; ?>>All Campuses</option>
                        <?php
                        $args = array(
                            'taxonomy' => 'campus',
                            'hide_empty' => false,
                        );
                        $terms = get_terms($args);
                        foreach ($terms as $term) {
                            $term_slug = $term->slug;
                            $term_name = $term->name;
                            $is_selected = (isset($_GET['campus_filter']) && $_GET['campus_filter'] == $term_slug) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_attr($term_slug); ?>" <?php echo $is_selected; ?>>
                                <?php echo esc_html($term_name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>
        <small class="container" style="margin-bottom:15px;">* Facilities typically serve faculty at all campuses, regardless of their location. If you don't see what you're looking for, try browsing all facilities or select another campus to narrow down your search results.</small>
    </div>

    <div class="facilities-wrap">
        <?php
        if ($facilities->have_posts()) {
            // Array to hold facilities grouped by department
            $departments = [];

            while ($facilities->have_posts()) {
                $facilities->the_post();
                $facility_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $thumbnail_id = get_post_thumbnail_id($post->ID);
                $facility_photo_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                $facility_link = get_the_permalink();
                $name = get_field('name');

                // Get department terms
                $departments_terms = wp_get_post_terms(get_the_ID(), 'department');
                foreach ($departments_terms as $department) {
                    $departments[$department->slug]['name'] = $department->name;
                    $departments[$department->slug]['description'] = $department->description;
                    $departments[$department->slug]['facilities'][] = [
                        'link' => $facility_link,
                        'photo' => $facility_photo,
                        'alt' => $facility_photo_alt,
                        'name' => $name
                    ];
                }
            }
            wp_reset_postdata();

            // follow order of the dropdown
            $no_facilities = true; // check if there are facilities to display
            foreach ($department_terms as $department_term) {
                $slug = $department_term->slug;
                if (!empty($departments[$slug]['facilities'])) {
                    $no_facilities = false; // if are facilities to display
                    echo "<div class='department-section' style='margin-bottom:20px;'>";
                    echo "<h3 style='margin-bottom:0px'>{$departments[$slug]['name']}</h3>";
                    echo "<p>{$departments[$slug]['description']}</p>";
                    echo "<div class='facilities-wrap-element'>";

                    // Sort facilities alphabetically by 'name'
                    usort($departments[$slug]['facilities'], function($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });

                    foreach ($departments[$slug]['facilities'] as $facility) {
                        echo "<a class='facilities-element' href='{$facility['link']}'>
                                <div class='facility-photo-wrap'>
                                    <img class='story-photo' src='{$facility['photo']}' width='100%' height='100px' alt='{$facility['alt']}'>
                                    <div class='facility-details white'>
                                        <h3>{$facility['name']}</h3>
                                    </div>
                                </div>
                            </a>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            }

            // If no facilities were found
            if ($no_facilities) {
                echo "<div class='no-facilities-message'>
                        <p>No facilities match your search or filter criteria. Please try broadening your search or selecting different filters.</p>
                      </div>";
            }
        } else {
            // In case there are no facilities at all
            echo "<div class='no-facilities-message'>
                    <p>No facilities found. Please try adjusting your search and filters.</p>
                  </div>";
        }
        ?>
    </div>
</section>

<?php get_footer(); ?>
