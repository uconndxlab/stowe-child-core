<?php

function link_parent_theme_style()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'link_parent_theme_style');

define('STOWE_CHILD_CORE', '1.0.0');

function sandc_titlebar_register( $wp_customize ){
    	
	// Add color options
	$wp_customize->add_setting( 'themeColor', //Give it a SERIALIZED name (so all theme settings can live under one db record)
		array(
			'default' => 'bs-color-husky-blue', //Default setting/value to save
			'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
			'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
			'transport' => 'refresh'
			)
		);
	$wp_customize->add_control('themeColor', array(
        'type' => 'radio',
        'label' => 'Accent Color',
        'section' => 'colors',
        'choices' => array(
			'bs-color-husky-blue' => 'Husky Blue',
			//'bs-color-royal-blue' => 'Royal Blue',
			'bs-color-imperial-purple' => 'Imperial Purple',
			'bs-color-pumpkin-orange' => 'Pumpkin Orange',
            'bs-color-emerald-green' => 'Emerald Green',
            'bs-color-ruby-red' => 'Ruby Red'
            
        	)
    	)
	);
	
	 // Add font options
	$wp_customize->add_section( 'fontStyle', array(
	  'title' => __( 'Font Style' ),
	  'description' => __( 'Select from pre-defined font combinations' ),
	  'panel' => '', // Not typically needed.
	  'priority' => 41,
	  'capability' => 'edit_theme_options',
	  'theme_supports' => '', // Rarely needed.
	) );
	
	$wp_customize->add_setting( 'fontSelect', //Give it a SERIALIZED name (so all theme settings can live under one db record)
		array(
			'default' => 'bs-font-sans', //Default setting/value to save
			'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
			'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
			'transport' => 'refresh'
			)
		);
	$wp_customize->add_control('fontSelect', array(
        'type' => 'radio',
        'label' => '',
        'section' => 'fontStyle',
        'choices' => array(
			'bs-font-sans' => 'UConn Brand Standard',
            'bs-font-plex' => 'Plex', 
			'bs-font-serif' => 'Newsworthy',
            'bs-font-book' => 'Book',
            'bs-font-compressed' => 'Compressed' 
        	)
    	)
	);
}
add_action( 'customize_register', 'sandc_titlebar_register' , 34);


function beecher_stowe_scripts() {
	wp_enqueue_script( 'bs-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ));	
	$stylesheet = get_theme_mod('themeColor');
	if( $stylesheet ){
		wp_enqueue_style( $stylesheet, get_stylesheet_directory_uri() . '/css/'.$stylesheet.'.css', array('cs-style') );
	} else {
		wp_enqueue_style( 'bs-husky-blue', get_stylesheet_directory_uri() . '/css/bs-color-husky-blue.css', array('cs-style') );
	}
	
	$stylesheet = get_theme_mod('fontSelect');
	if( $stylesheet ){
		wp_enqueue_style( $stylesheet, get_stylesheet_directory_uri() . '/css/'.$stylesheet.'.css', array('cs-style') );
	} else {
		wp_enqueue_style( 'bs-sans', get_stylesheet_directory_uri() . '/css/bs-font-sans.css', array('cs-style') );
	}
}

add_action( 'wp_enqueue_scripts', 'beecher_stowe_scripts', 50);

// enqueue scripts htmx cdn
add_action('wp_enqueue_scripts', 'stowe_child_core_enqueue_scripts');

function stowe_child_core_enqueue_scripts()
{
    wp_enqueue_script('htmx', 'https://unpkg.com/htmx.org/dist/htmx.min.js', array(), STOWE_CHILD_CORE, true);
}
// change post thumbnail size
function wpdocs_setup_theme() {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 300, 300 );
}
add_action( 'after_setup_theme', 'wpdocs_setup_theme' );

// Register the core facilities shortcode
function display_core_facilities($atts) {
    // Parse the attributes and set default values
    $atts = shortcode_atts(
        array(
            'show_name_description' => 'true', // Default to true (show name & description)
        ),
        $atts,
        'core_facilities'
    );

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
        'orderby' => 'name',  // Order by slug (URL name)
        'order' => 'ASC',     // Ascending order
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

    ob_start(); // Start output buffering

    ?>
    <section style="margin:0px 0px">
        <!-- Conditionally Display the core department name and description -->
        <?php if (isset($department_name) && isset($department_description) && $atts['show_name_description'] === 'true') : ?>
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
                    $thumbnail_id = get_post_thumbnail_id(get_the_ID());
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
    </section>

    <?php
    return ob_get_clean(); // Return the content for the shortcode
}

// Add the shortcode
add_shortcode('core_facilities', 'display_core_facilities');