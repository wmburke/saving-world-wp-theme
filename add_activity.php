<?php
/*
Template Name: Add Activity Form
*/


if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post") {

    // Do some minor form validation to make sure there is content
    if (isset ($_POST['title'])) {
        $title =  $_POST['title'];
    } else {
        echo 'Please enter the activity name';
    }
    if (isset ($_POST['description'])) {
        $description = $_POST['description'];
    } else {
        echo 'Please enter some notes';
    }

    $issues = $_POST['issues'];

    // ADD THE FORM INPUT TO $new_post ARRAY
    $new_post = array(
    'post_title'    =>   $title,
    'post_content'  =>   $description,
		'tax_input'			=>	 array(
			'sdw_activity_issues' => array($issues)
		),
//    'meta_input' => array(
//      'success' => $_POST['success']
//    )
    'post_status'   =>   'publish',           // Choose: publish, preview, future, draft, etc.
    'post_type' =>   'sdw_activity'  //'post',page' or use a custom post type if you want to
    );

    //SAVE THE POST
    $pid = wp_insert_post($new_post);

    //SET THE TYPE ON THE ACTIVITY
    $type_ids = intval($_POST['cat']); // have to make sure it's a number, not a string
    wp_set_object_terms($pid, $type_ids, 'sdw_activity_type');

    //REDIRECT TO THE NEW POST ON SAVE
    $link = get_permalink( $pid );
//    wp_redirect( $link );

} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM

//POST THE POST YO
do_action('wp_insert_post', 'wp_insert_post');

//GET THE LIST OF ACTIVITY TYPES
$actTypeTerms = get_terms(array(
  'taxonomy' => 'sdw_activity_type',
  'hide_empty' => false,
));

foreach ($actTypeTerms as $key => $actTypeTerm) {
  $actTypeList[$key] = $actTypeTerm->name;
}

get_header();
echo "<pre>";
print_r($_POST);
print_r($actTypeList);
echo "</pre>";
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;

    if ( is_user_logged_in() ) {
		?>

    <!-- ADD ACTIVITY FORM -->

    <div class="sdw-activity entry-content">
    <form id="new_post" name="new_post" method="post" action="" class="sdw-activity-form" enctype="multipart/form-data">

        <fieldset name="details">
            <legend>Activity</legend>

            <!-- Activity Name -->
            <div id="actName">
              <label for="name">Name</label><br>
              <input type="text" value="" tabindex="5" name="name" id="name" required />
            </div>

            <!-- Activity Date -->
            <div>
              <label for="date">Start Date</label><br>
              <input type="date" id="actDate" value="" tabindex="10" name="date" required />
            </div>

            <!-- Activity Type -->
            <div id="actType">
              <label for="cat">Type</label><br>
              <input type="text" value="" tabindex="15" name="cat" id="actType1" required />
              <?php //wp_dropdown_categories( 'tab_index=10&taxonomy=sdw_activity_type&hide_empty=0' ); ?>
            </div>

            <!-- Activity Description -->
            <div>
              <label for="description">Description</label><br>
              <textarea id="actDesc" tabindex="20" name="description" cols="80" rows="5" required ></textarea>
            </div>

          </fieldset>

          <fieldset class="venue">
              <legend>Venue</legend>

              <!-- Activity Venue -->
            <div>
              <label for="venueName">Name</label><br>
              <input type="text" id="actVenue" value="" tabindex="25" name="venueName" />
            </div>

            <!-- Activity Venue Type -->
            <div id="venueType">
              <label for="venueType">Type</label><br>
              <input type="text" value="" tabindex="30" name="venueType" />
            </div>

            <!-- Activity City -->
            <div id="venueCity">
              <label for="city">City</label><br>
              <input type="text" value="" tabindex="35" name="city" required />
            </div>

        </fieldset>

        <fieldset class="achievements">
            <legend>Achievements</legend>

            <!-- Activity Issues -->
            <div id="actIssues">
              <label for="issues">What issues did this activity work on (comma separated if multiples)?</label><br>
              <input type="text" value="" tabindex="40" name="issues" id="actIssues1" required />
            </div>

            <!-- Activity Goal -->
            <div>
              <label for="goal">What were your goals?</label><br>
              <textarea id="actGoals" tabindex="45" name="goal" cols="80" rows="5" required ></textarea>
            </div>

            <!-- Activity Success -->
            <div>
              <label for="success" >How successful would you rate this activity?</label><br>
              <input type="radio" value="1" tabindex="50" name="success" required />1
              <input type="radio" value="2" tabindex="50" name="success" />2
              <input type="radio" value="3" tabindex="50" name="success" />3
              <input type="radio" value="4" tabindex="50" name="success" />4
              <input type="radio" value="5" tabindex="50" name="success" />5<br />
            </div>

            <!-- Activity Lessons Learned -->
            <div>
              <label for="lessonsLearned">Lessons Learned / Advice to Others</label><br>
              <textarea id="actLessonsLearned" tabindex="55" name="lessonsLearned" cols="80" rows="5" ></textarea>
            </div>

        </fieldset>

        <!-- Submit button -->
        <fieldset class="submit">
            <input type="submit" value="Post Your Activity Report" tabindex="100" id="submit" name="submit" />
        </fieldset>

        <input type="hidden" name="action" value="new_post" />
        <?php wp_nonce_field( 'new-post' ); ?>
    </form>
    </div> <!-- END WPCF7 -->

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php } else { ?>
  Please <a href="http://saving.world/aqjf">login</a> or <a href="http://saving.world/register/">register</a>.
<?php } ?>

<?php get_sidebar(); ?>

<!--  load form javascript -->

<script type="text/javascript">

// Datepicker for the Activity Date
  jQuery(document).ready(function() {
    jQuery('#actDate').datepicker({
      dateFormat : 'MM dd, yy'
    });
  });

/* Autocomplete for Activity Type
  var actTypesList = <?php echo json_encode($actTypeList) ?>;
  jQuery(document).ready(function() {
    jQuery('#actType1').autocomplete({
      source : actTypesList
    });
  });
*/
</script>

<script>
jQuery(document).ready(function() {

  // Validation of form
  jQuery("form").validate();

  // auto tags input
  jQuery('#actType1').tagsInput();

});

</script>

<?php get_footer(); ?>
