<?php

/**
 * The template for displaying single facility.
 */

get_header(); ?>


<?php while (have_posts()) : the_post(); ?>

  <div id="primary" class="content-area">
    <div id="facility_content">
      <?php
      // get page content if any
      the_content();
      ?>
    </div>
  </div>

<?php endwhile; ?>

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
    <div class="row" style="margin: auto;padding:100px 0px;background-image: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 80%),
                    url(<?php echo $facility_photo; ?>);background-repeat:no-repeat;background-size:cover;background-position:center;">
      <div class="container white">
        <div class="col-md-6" style="padding:0px">
          <?php
          foreach ($facility_departments as $department) :
          ?>
            <div class="" style="text-transform:uppercase;">
              <small>
              <?php echo $department['name']; ?>
              </small>
            </div>
              <?php endforeach;?>
              <?php
              foreach ($facility_campuses as $campus) :
              ?>
            <div class="" style="text-transform:uppercase;">
              <small>
              <?php echo $campus['name']; ?>
              </small>
            </div>
          <?php endforeach;?>
          <?php endwhile; ?>

            <h2 style="margin-bottom:0px;font-size:50px;">
              <?php echo $data['name']; ?>
            </h2>
            <p style="font-size:18px">
              <?php echo $data['short_description']; ?>
            </p>
            <a href="<?php echo $data['homepage']; ?>" target="blank" class="btn btn-primary">Facility Homepage</a>
        </div>
      </div>
    </div>

    <div class="row container" style="margin:auto;padding-top:50px;padding-bottom:50px;">
      <div style="display:flex;justify-content:space-between;align-items:end;">
      <h3>Contacts</h3>
      <p><strong>Facility Phone Number: </strong><a href="tel:<?php echo $data['facility_phone_number']; ?>"><?php echo $data['facility_phone_number']; ?></a></p>
      </div>
      <?php
      // Loop through the contact fields (1 to 6)
      for ($i = 1; $i <= 6; $i++) {
          // Retrieve the contact details from the $data array
          $contact_name = isset($data['contact' . $i . '_name']) ? $data['contact' . $i . '_name'] : '';
          $contact_title = isset($data['contact' . $i . '_title']) ? $data['contact' . $i . '_title'] : '';
          $contact_email = isset($data['contact' . $i . '_email']) ? $data['contact' . $i . '_email'] : ''; 
      ?>
              <div class="col-md-4 card" style="padding-left:0px">
                  <h4 style="margin-bottom:0px"><?php echo $contact_name; ?></h4>
                  <p style="margin-bottom:5px"><?php echo $contact_title; ?></p>
                  <a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>
              </div>
      <?php
      }
      ?>
    </div>

    <div class="row container" style="margin:auto;padding-top:50px;padding-bottom:50px;">
      <div class="col-md-6" style="padding-left:0px">
        <h3>Campus Address</h3>
        <p><?php echo $data['campus_address']; ?></p>
      </div>
      <div class="col-md-6" style="padding-left:0px">
        <h3>Mailing Address</h3>
        <p><?php echo $data['mailing_address']; ?></p>
      </div>
    </div>

    <div class="row container" style="margin:auto;padding-top:50px;padding-bottom:50px;">
      <h3>Resources</h3>
      <?php
      // Loop through 12 resources
      for ($i = 1; $i <= 12; $i++) {
          $resource = isset($data['resource' . $i]) ? $data['resource' . $i] : '';
          $description = isset($data['description' . $i]) ? $data['description' . $i] : '';
          $image = isset($data['image' . $i]) ? $data['image' . $i] : '';

          // Only display if resource is not empty
          if ($resource) {
      ?>
              <div class="col-md-12" style="padding-left:0px;margin-bottom:20px;">
                  <div class="col-md-9" style="padding-left:0px">
                      <h4><?php echo $resource; ?></h4>
                      <p><?php echo strip_tags($description); ?></p>
                  </div>
                  <?php if (!empty($image)): ?>
                      <div class="col-md-3" style="padding-left:0px">
                          <img class="story-photo gallery-photo" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                      </div>
                  <?php endif; ?>
              </div>
        <?php
            }
        }
        ?>
    </div>
      

  </section>

  <?php get_footer(); ?>

  <!--get rid of default container-->
  <script>
    document.getElementById('content').firstElementChild.classList.remove('container');
  </script>