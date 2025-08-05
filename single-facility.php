<?php
/**
 * The template for displaying single facility.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<?php
        while (have_posts()) : the_post();
        // get the featured image and caption
        $facility_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $facility_photo_alt = get_post(get_post_thumbnail_id())->post_excerpt;

        $facility_campuses = array();
        // the tags are custom taxonomy campus
        $terms = get_the_terms($post->ID, 'campus');
        foreach ($terms as $term) {
            $facility_campuses[$term->slug] = array(
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        $facility_departments = array();
        $terms = get_the_terms($post->ID, 'department');
        foreach ($terms as $term) {
            $facility_departments[$term->slug] = array(
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }

        // Get general post data
$facility_link = get_the_permalink();

// Custom fields: Group similar fields into an array and fetch them at once
$contact_fields = [
    'contact1_name', 'contact1_title', 'contact1_email',
    'contact2_name', 'contact2_title', 'contact2_email',
    'contact3_name', 'contact3_title', 'contact3_email',
    'contact4_name', 'contact4_title', 'contact4_email',
    'contact5_name', 'contact5_title', 'contact5_email',
    'contact6_name', 'contact6_title', 'contact6_email'
];

$resource_fields = [];
for ($i = 1; $i <= 12; $i++) {
    $resource_fields[] = "resource{$i}";
    $resource_fields[] = "description{$i}";
    $resource_fields[] = "image{$i}";
}

$fields = get_fields(); // Use ACF's get_fields() to retrieve all custom fields in one call
$data = [];

// Store custom field values
foreach ($contact_fields as $field) {
    $data[$field] = isset($fields[$field]) ? $fields[$field] : '';
}

foreach ($resource_fields as $field) {
    $data[$field] = isset($fields[$field]) ? $fields[$field] : '';
}

// Additional custom fields
$data['name'] = isset($fields['name']) ? $fields['name'] : '';
$data['short_description'] = isset($fields['short_description']) ? $fields['short_description'] : '';
$data['homepage'] = isset($fields['homepage']) ? $fields['homepage'] : '';
$data['facility_phone_number'] = isset($fields['facility_phone_number']) ? $fields['facility_phone_number'] : '';
$data['campus_address'] = isset($fields['campus_address']) ? $fields['campus_address'] : '';
$data['mailing_address'] = isset($fields['mailing_address']) ? $fields['mailing_address'] : '';
$data['services'] = isset($fields['services']) ? $fields['services'] : '';
?>

<!-- start custom stuff -->

  <section id="facility-area">
    <div class="row" style="margin: auto;padding:100px 0px;background-image: linear-gradient(90deg, rgba(0, 14, 47,0.95) 0%, rgba(0, 14, 47,0.4) 100%),
                    url(<?php echo esc_url($facility_photo); ?>);background-repeat:no-repeat;background-size:cover;background-position:center;margin-bottom:50px;">
      <div class="container white">
        <div class="col-md-8" style="padding:0px">
            <h2 style="margin-bottom:10px;font-size:50px;line-height:54px;">
              <?php echo esc_html($data['name']); ?>
            </h2>
            <div style="display:flex;flex-wrap:wrap">
                <?php
                  foreach ($facility_campuses as $campus) :
                  ?>
                <div class="" style="text-transform:uppercase;width:max-content;padding-right:10px;margin-right:10px;border-right:1px solid #fff;">
                  <small>
                  <svg xmlns="http://www.w3.org/2000/svg" height="10" width="7.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#ffffff" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                  <?php echo esc_html($campus['name']); ?>
                  </small>
                </div>
              <?php endforeach;?>
              <?php
                foreach ($facility_departments as $index => $department) :
                    // Check if the current department is not the last one
                    $is_last = ($index === array_key_last($facility_departments));
                ?>
                    <div class="" style="text-transform:uppercase;width:max-content;<?php echo !$is_last ? 'border-right:1px solid #fff;padding-right:10px;margin-right:10px;' : ''; ?>">
                        <small>
                            <svg xmlns="http://www.w3.org/2000/svg" height="10" width="7.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#ffffff" d="M48 0C21.5 0 0 21.5 0 48L0 464c0 26.5 21.5 48 48 48l96 0 0-80c0-26.5 21.5-48 48-48s48 21.5 48 48l0 80 96 0c26.5 0 48-21.5 48-48l0-416c0-26.5-21.5-48-48-48L48 0zM64 240c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zm112-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM80 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM272 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16z"/></svg>
                            <?php echo esc_html($department['name']); ?>
                        </small>
                    </div>
                <?php endforeach; ?>
                <?php endwhile; ?>
            </div>
            <p style="font-size:18px">
                  
                <?php echo wp_kses_post($data['short_description']); ?>
            </p>
            <?php if($data['homepage']){?>
            <a href="<?php echo esc_url($data['homepage']); ?>" target="blank" class="btn btn-primary">Facility Homepage</a>
            <?php } ?>
        </div>
      </div>
    </div>

    <div class=" container" style="margin:auto;">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">
      <?php if($data['contact1_name']){?>
      <h3>Contacts</h3>
      <?php } ?>
      <?php if($data['facility_phone_number']){?>
      <p><strong>Facility Phone Number: </strong><a href="tel:<?php echo $data['facility_phone_number']; ?>"><?php echo $data['facility_phone_number']; ?></a></p>
      <?php
        }
      ?>
      </div>
      <div class="row contacts-row">
      <?php
      // Loop through the contact fields (1 to 6)
      for ($i = 1; $i <= 6; $i++) {
          // Retrieve the contact details from the $data array
          $contact_name = isset($data['contact' . $i . '_name']) ? $data['contact' . $i . '_name'] : '';
          $contact_title = isset($data['contact' . $i . '_title']) ? $data['contact' . $i . '_title'] : '';
          $contact_email = isset($data['contact' . $i . '_email']) ? $data['contact' . $i . '_email'] : ''; 
          if($contact_name){
      ?>
              <div class="col-md-4 card" style="margin-bottom:30px;">
                <div style="padding:20px;background:#f0f2f7;border-left:4px solid #D10026;height:100%;">
                  <h4 style="margin-bottom:0px"><?php echo $contact_name; ?></h4>
                  <p style="margin-bottom:5px"><?php echo $contact_title; ?></p>
                  <a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>
                </div>
              </div>
      <?php
          }}
      ?>
      </div>
    </div>

    <div class="row container" style="margin:auto">
      <?php if ($data['campus_address']){?>
      <div class="col-md-6" style="padding-left:0px;padding-top:0px;padding-bottom:50px;">
        <h3>Campus Address</h3>
        <p><?php echo $data['campus_address']; ?></p>
      </div>
      <?php
      }
      ?>
      <?php if ($data['mailing_address']){?>
      <div class="col-md-6" style="padding-left:0px;padding-top:0px;padding-bottom:50px;">
        <h3>Mailing Address</h3>
        <p><?php echo $data['mailing_address']; ?></p>
      </div>
      <?php
      }
      ?>
    </div>

    <?php if ($data['resource1']){?>
    <div style="background:#1d305e">
    <div class="row container" style="margin:auto;padding-top:0px;padding-bottom:50px;padding-top:50px;">
      <h3 style="color:#fff">Resources</h3>
      <?php
      // Loop through 12 resources
      for ($i = 1; $i <= 12; $i++) {
          $resource = isset($data['resource' . $i]) ? $data['resource' . $i] : '';
          $description = isset($data['description' . $i]) ? $data['description' . $i] : '';
          $image = isset($data['image' . $i]) ? $data['image' . $i] : '';

          // Only display if resource is not empty
          if ($resource) {
      ?>
              <div class="col-md-12" style="margin-bottom:20px;background:#fff;padding:20px;box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.1);">
                  <div class="col-md-9" style="padding-left:0px;">
                      <h4><?php echo $resource; ?></h4>
                      <p><?php echo $description; ?></p>
                  </div>
                  <?php if (!empty($image)): ?>
                      <div class="col-md-3" style="padding-left:0px;padding-right:0px;">
                          <img class="story-photo gallery-photo" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                      </div>
                  <?php endif; ?>
              </div>
        <?php
            }
        }
        ?>
    </div>
    </div>
    <?php
      }
    ?>  
    <?php if ($data['services']){?>
      <div style="background:#000e2f;border-bottom:4px solid #D10026;">
      <div class="white container" style="margin:auto;padding-top:0px;padding-bottom:50px;padding-top:50px;">
      <h3 >Services</h3>
      <p style="color:#fff"><?php echo $data['services']; ?></p>
      <a href="<?php echo $data['homepage']; ?>" target="blank" class="btn btn-primary">Visit Facility Homepage</a>
      </div>
      </div>
    <?php
    }
    ?>

  </section>

  <?php while (have_posts()) : the_post(); ?>

  <div id="primary" class="content-area container">
    <div id="facility_content">
      <?php
      // get page content if any
      the_content();
      ?>
    </div>
  </div>

<?php endwhile; ?>

  <?php get_footer(); ?>

  <!--get rid of default container-->
  <script nonce="<?php echo esc_attr(stowe_child_core_get_nonce()); ?>">
    document.getElementById('content').firstElementChild.classList.remove('container');
  </script>