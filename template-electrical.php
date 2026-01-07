<?php
/**
 * Template Name: Electrical Page
 * Description: Electrical pages pulling header from ACF page ID 468 with SVGs and fail-safe ACF logic.
 */

get_header();

/**
 * Facility ACF page ID
 */
$facility_page_id = 468;

/**
 * Get ACF fields safely
 */
$fields = function_exists('get_fields') ? get_fields($facility_page_id) : [];
$fields = $fields ?: [];

$data = [
    'name' => $fields['name'] ?? 'Facility Name',
    'short_description' => $fields['short_description'] ?? 'Description not available.',
    'homepage' => $fields['homepage'] ?? '',
    'facility_phone_number' => $fields['facility_phone_number'] ?? '',
    'campus_address' => $fields['campus_address'] ?? '',
    'mailing_address' => $fields['mailing_address'] ?? '',
    'services' => $fields['services'] ?? '',
];

/**
 * Featured image safely
 */
$facility_photo = get_the_post_thumbnail_url($facility_page_id, 'full') ?: '';
$facility_photo_alt = get_post(get_post_thumbnail_id($facility_page_id))->post_excerpt ?? '';

/**
 * Taxonomies safely
 */
$facility_campuses = get_the_terms($facility_page_id, 'campus') ?: [];
$facility_departments = get_the_terms($facility_page_id, 'department') ?: [];
?>

<!-- Facility Header Section -->
<section id="facility-area">
    <div class="row" style="margin:auto;padding:100px 0;background-image:linear-gradient(90deg,rgba(0,14,47,0.95) 0%,rgba(0,14,47,0.4) 100%),url(<?php echo esc_url($facility_photo); ?>);background-repeat:no-repeat;background-size:cover;background-position:center;margin-bottom:50px;">
        <div class="container white">
            <div class="col-md-8" style="padding:0;">
                <h2 style="margin-bottom:10px;font-size:50px;line-height:54px;">
                    <?php echo esc_html($data['name']); ?>
                </h2>

                <div style="display:flex;flex-wrap:wrap">
                    <!-- Campuses -->
                    <?php foreach ($facility_campuses as $campus) : ?>
                        <div style="text-transform:uppercase;width:max-content;padding-right:10px;margin-right:10px;border-right:1px solid #fff;">
                            <small>
                                <svg xmlns="http://www.w3.org/2000/svg" height="10" width="7.5" viewBox="0 0 384 512"><path fill="#ffffff" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                                <?php echo esc_html($campus->name); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>

                    <!-- Departments -->
                    <?php foreach ($facility_departments as $index => $department) :
                        $is_last = ($index === array_key_last($facility_departments));
                    ?>
                        <div style="text-transform:uppercase;width:max-content;<?php echo !$is_last ? 'border-right:1px solid #fff;padding-right:10px;margin-right:10px;' : ''; ?>">
                            <small>
                                <svg xmlns="http://www.w3.org/2000/svg" height="10" width="7.5" viewBox="0 0 384 512"><path fill="#ffffff" d="M48 0C21.5 0 0 21.5 0 48L0 464c0 26.5 21.5 48 48 48l96 0 0-80c0-26.5 21.5-48 48-48s48 21.5 48 48l0 80 96 0c26.5 0 48-21.5 48-48l0-416c0-26.5-21.5-48-48-48L48 0zM64 240c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zm112-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM80 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM272 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16z"/></svg>
                                <?php echo esc_html($department->name); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p style="font-size:18px">
                    <?php echo wp_kses_post($data['short_description']); ?>
                </p>

                <?php if ($data['homepage']) : ?>
                    <a href="<?php echo esc_url($data['homepage']); ?>" target="_blank" class="btn btn-primary">
                        Facility Homepage
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Page Content Section -->
<div id="primary" class="content-area container">
    <?php while (have_posts()) : the_post(); ?>
        <div id="page-content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>

<script>
    const contentEl = document.getElementById('content');
    if (contentEl && contentEl.firstElementChild) {
        contentEl.firstElementChild.classList.remove('container');
    }
</script>
