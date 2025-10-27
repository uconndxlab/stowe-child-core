<?php
/** Facilities Template, page-facilities.php */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Get terms in the order you want for the department
$department_terms = get_terms(array(
    'taxonomy' => 'department',
    'hide_empty' => false,
    'orderby' => 'name',  // Order alphabetically or adjust as needed
    'order' => 'ASC',     // Ascending order
));

// Robust input validation and sanitization
function validate_and_sanitize_search_input($input) {
    if (empty($input)) {
        return '';
    }
    
    // Remove potentially dangerous characters and limit length
    $input = sanitize_text_field($input);
    $input = trim($input);
    
    // Remove SQL wildcards and special characters that could be used maliciously
    $input = str_replace(array('%', '_', '\\', '"', "'", '<', '>', '&'), '', $input);
    
    // Limit search query length to prevent potential DoS attacks
    if (strlen($input) > 100) {
        $input = substr($input, 0, 100);
    }
    
    // Only allow alphanumeric characters, spaces, hyphens, and periods
    $input = preg_replace('/[^a-zA-Z0-9\s\-\.\@]/', '', $input);
    
    return $input;
}

function validate_taxonomy_filter($filter_value, $taxonomy) {
    if (empty($filter_value) || $filter_value === 'all-departments' || $filter_value === 'all-campuses') {
        return $filter_value;
    }
    
    // Validate that the term actually exists in the taxonomy
    $term = get_term_by('slug', $filter_value, $taxonomy);
    if (!$term) {
        return '';  // Invalid term, ignore the filter
    }
    
    // Additional validation: ensure slug format
    if (!preg_match('/^[a-z0-9\-]+$/', $filter_value)) {
        return '';
    }
    
    return sanitize_key($filter_value);
}

$search_query = validate_and_sanitize_search_input(isset($_GET['search']) ? $_GET['search'] : '');
$department_filter = validate_taxonomy_filter(isset($_GET['department_filter']) ? $_GET['department_filter'] : '', 'department');
$campus_filter = validate_taxonomy_filter(isset($_GET['campus_filter']) ? $_GET['campus_filter'] : '', 'campus');

// First query to search titles and content
$args1 = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
    's' => $search_query
);

// Safer meta query construction with allowed fields validation
function build_safe_meta_query($search_query) {
    if (empty($search_query)) {
        return array();
    }
    
    // Define allowed meta fields to prevent injection attacks
    $allowed_meta_fields = array(
        'name',
        'short_description',
        'homepage',
        'facility_phone_number',
        'contact1_name',
        'contact1_email',
        'contact1_title',
        'contact2_name',
        'contact2_email',
        'contact2_title',
        'contact3_name',
        'contact3_email',
        'contact3_title',
        'contact4_name',
        'contact4_email',
        'contact4_title',
        'contact5_name',
        'contact5_email',
        'contact5_title',
        'contact6_name',
        'contact6_email',
        'contact6_title',
        'campus_address',
        'mailing_address',
        'resource1',
        'description1',
        'resource2',
        'description2',
        'resource3',
        'description3',
        'resource4',
        'description4',
        'resource5',
        'description5',
        'resource6',
        'description6',
        'resource7',
        'description7',
        'resource8',
        'description8',
        'resource9',
        'description9',
        'resource10',
        'description10'
    );
    
    $meta_query = array('relation' => 'OR');
    
    foreach ($allowed_meta_fields as $field) {
        $meta_query[] = array(
            'key' => $field,
            'value' => $search_query,
            'compare' => 'LIKE'
        );
    }
    
    return $meta_query;
}

// Second query to search meta fields with validated fields
$args2 = array(
    'post_type' => 'facility',
    'posts_per_page' => -1,
    'paged' => get_query_var('paged'),
    'meta_query' => build_safe_meta_query($search_query)
);

// Initialize the tax_query
$tax_query = array('relation' => 'AND');

// Validate and apply department filter
if (!empty($department_filter) && $department_filter !== 'all-departments') {
    $tax_query[] = array(
        'taxonomy' => 'department',
        'field' => 'slug',
        'terms' => $department_filter,
        'operator' => 'IN',
    );
}

// Validate and apply campus filter
if (!empty($campus_filter) && $campus_filter !== 'all-campuses') {
    $tax_query[] = array(
        'taxonomy' => 'campus',
        'field' => 'slug',
        'terms' => $campus_filter,
        'operator' => 'IN',
    );
}

// Add tax_query to both args1 and args2
$args1['tax_query'] = $tax_query;
$args2['tax_query'] = $tax_query;

// Perform the queries
$query1 = new WP_Query($args1);
$query2 = new WP_Query($args2);

// --- Third query: Search by tags (post_tag) ---
$query3 = null;
if (!empty($search_query)) {
    // Find matching tags first (looser match)
    $matching_tags = get_terms(array(
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
        'name__like' => $search_query,
    ));

    $tag_slugs = wp_list_pluck($matching_tags, 'slug');

    if (!empty($tag_slugs)) {
        $args3 = array(
            'post_type'      => 'facility',
            'posts_per_page' => -1,
            'paged'          => get_query_var('paged'),
            'tax_query'      => array(
                'relation' => 'AND',
                // Match tag names (slugs)
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $tag_slugs,
                    'operator' => 'IN',
                ),
            ),
        );

        // Include department and campus filters if they’re active
        if (!empty($tax_query) && count($tax_query) > 1) {
            foreach ($tax_query as $tx) {
                // Avoid re-adding the relation key
                if (is_array($tx) && isset($tx['taxonomy'])) {
                    $args3['tax_query'][] = $tx;
                }
            }
        }

        $query3 = new WP_Query($args3);
    }
}

// --- Merge all results together ---
$merged_posts = array_merge(
    $query1->posts,
    $query2->posts,
    $query3 ? $query3->posts : array()
);


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
                        <option value="all-departments" <?php echo (empty($department_filter) || $department_filter == 'all-departments') ? 'selected' : ''; ?>>All Departments</option>
                        <?php
                        foreach ($department_terms as $term) {
                            $term_slug = $term->slug;
                            $term_name = $term->name;
                            $is_selected = ($department_filter == $term_slug) ? 'selected' : '';
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
                        <option value="all-campuses" <?php echo (empty($campus_filter) || $campus_filter == 'all-campuses') ? 'selected' : ''; ?>>All Campuses</option>
                        <?php
                        $args = array(
                            'taxonomy' => 'campus',
                            'hide_empty' => false,
                        );
                        $terms = get_terms($args);
                        foreach ($terms as $term) {
                            $term_slug = $term->slug;
                            $term_name = $term->name;
                            $is_selected = ($campus_filter == $term_slug) ? 'selected' : '';
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
                            echo "<a class='facilities-element' href='" . esc_url($facility['link']) . "'>
                                    <div class='facility-photo-wrap'>
                                        <img class='story-photo' src='" . esc_url($facility['photo']) . "' width='100%' height='100px' alt='" . esc_attr($facility['alt']) . "'>
                                        <div class='facility-details white'>
                                            <h3>" . esc_html($facility['name']) . "</h3>
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