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

$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// First query to search titles and content
$args1 = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
    's' => $search_query
);

// Second query to search meta fields
$args2 = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'short_description',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'homepage',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'facility_phone_number',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact1_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact1_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact1_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact2_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact2_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact2_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact3_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact3_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact3_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact4_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact4_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact4_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact5_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact5_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact5_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact6_name',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact6_email',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'contact6_title',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'campus_address',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'mailing_address',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource1',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description1',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource2',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description2',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource3',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description3',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource4',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description4',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource5',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description5',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource6',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description6',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource7',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description7',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource8',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description8',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource9',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description9',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource10',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description10',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource11',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description11',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'resource12',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'description12',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'services',
            'value' => $search_query,
            'compare' => 'LIKE'
        ),
    )
);

// Initialize the tax_query
$tax_query = array('relation' => 'AND');

// If $_GET['department_filter'] is set, filter by taxonomy terms (department)
if (isset($_GET['department_filter']) && !empty($_GET['department_filter'])) {
    if ($_GET['department_filter'] !== 'all-departments') {
        $tax_query[] = array(
            'taxonomy' => 'department',
            'field' => 'slug',
            'terms' => $_GET['department_filter'],
            'operator' => 'IN',
        );
    }
}

// If $_GET['campus_filter'] is set, filter by taxonomy terms (campus)
if (isset($_GET['campus_filter']) && !empty($_GET['campus_filter'])) {
    if ($_GET['campus_filter'] !== 'all-campuses') {
        $tax_query[] = array(
            'taxonomy' => 'campus',
            'field' => 'slug',
            'terms' => $_GET['campus_filter'],
            'operator' => 'IN',
        );
    }
}

// Add tax_query to both args1 and args2
$args1['tax_query'] = $tax_query;
$args2['tax_query'] = $tax_query;

// Perform the queries
$query1 = new WP_Query($args1);
$query2 = new WP_Query($args2);

// Merge the results
$merged_posts = array_merge($query1->posts, $query2->posts);

// Remove duplicate posts
$merged_posts = array_unique($merged_posts, SORT_REGULAR);
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
                <div class="col-lg-4 stories-filter-item" style="margin-bottom:10px;">
                    <label for="facility-search">Search</label>
                    <input class="form-control" style="min-height:40px" type="text" placeholder="Search by name, service, etc." aria-label=".form-control" id="facility-search" name="search" value="<?php echo esc_attr($search_query); ?>">
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
        if (empty($merged_posts)) {
            // If no facilities were found after filtering
            echo "<div class='no-facilities-message'>
                    <p>No facilities match your search or filter criteria. Please try broadening your search or selecting different filters.</p>
                  </div>";
        } else {
            // Create a new WP_Query object with the merged posts
            $facilities = new WP_Query(array(
                'post_type' => 'facility',
                'posts_per_page' => -1,
                'post__in' => wp_list_pluck($merged_posts, 'ID'),
                'orderby' => 'post__in'
            ));

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
                        $no_facilities = false; // if there are facilities to display
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

                // If no facilities were found after filtering
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
        }
        ?>
    </div>
</section>

<?php get_footer(); ?>