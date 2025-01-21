<?php
	/*
		Custom page for Eeep
	*/

get_header();

$post_id              = get_the_ID();
$is_page_builder_used = et_pb_is_pagebuilder_used( $post_id );
$container_tag        = 'product' === get_post_type( $post_id ) ? 'div' : 'article'; ?>


    <div id="main-content">


<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<<?php echo $container_tag; ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$alttext = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
					$thumbnail = get_thumbnail( $width, $height, $classtext, $alttext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content" style="margin-bottom:10px">

						<!-- Coding -->
						<?php
							$form_id = 1;
							echo do_shortcode('[gravityform id="' . $form_id . '" title="false" description="false" ajax="true" ]');
						?>

						<?php
							/// the_content();
							if ( ! $is_page_builder_used )
							{
								wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
							}					
						?>
					</div>

					<script>
						/* Show Reports filtered by Year */
						jQuery('#input_1_4').on('input', function () {
								jQuery.ajax({
									url: '/wp-json/eeep/v1/doc-reports/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										//jQuery('#input_1_3').html(result);
									},
									error: function(result) {
										alert('Ξαναπροσπαθήστε αργότερα!');
									},
								});
							});

						/* Show Pylons */
						jQuery(document).ready(function() {
							jQuery.ajax({
								url: '/wp-json/eeep/v1/indi-pylons/' + 1,
								type: 'GET',
								success: function(result) {
									jQuery('#input_1_9').html(result);
									jQuery('#input_1_9').attr('disabled', false);
								},
								error: function(result) {
									alert('Ξαναπροσπαθήστε αργότερα!');
								},
							});

							/* Show Categories */
							jQuery('#input_1_9').change(function () {
								jQuery.ajax({
									url: '/wp-json/eeep/v1/indi-categories/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										jQuery('#input_1_8').html(result);
										/// Un-Lock drop down list
										jQuery('#input_1_8').attr('readonly', false);
										jQuery('#input_1_8').css('pointer-events', 'auto');
									},
									error: function(result) {
										alert('Ξαναπροσπαθήστε αργότερα!');
									},
								});
							});

							/* Show Sub-Categories */
							jQuery('#input_1_8').change(function () {
								jQuery.ajax({
									url: '/wp-json/eeep/v1/indi-subcategors/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										jQuery('#input_1_10').html(result);
										/// Un-Lock drop down list
										jQuery('#input_1_10').attr('readonly', false);
										jQuery('#input_1_10').css('pointer-events', 'auto');
									},
									error: function(result) {
										alert('Ξαναπροσπαθήστε αργότερα!');
									},
								});
							});

							/* Show Indicators */
							jQuery('#input_1_10').change(function () {
								jQuery.ajax({
									url: '/wp-json/eeep/v1/indicators/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										jQuery('#input_1_5').html(result);
										/// Un-Lock drop down list
										jQuery('#input_1_5').attr('readonly', false);
										jQuery('#input_1_5').css('pointer-events', 'auto');
										},
									error: function(result) {
										alert('Ξαναπροσπαθήστε αργότερα!');
									},
								});
							});

							jQuery('#input_1_1').attr('readonly', true);
							console.log(0);
							/* Show Metrics (MATCH Indicator with Metric-set) */
							jQuery('#input_1_5').change(function () {

								jQuery.ajax({
									url: '/wp-json/eeep/v1/registry-title/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										jQuery('#input_1_1').val(result);
										jQuery('#input_1_1').attr('readonly', true);
										jQuery('#input_1_1').css('pointer-events', 'false');
									},
									error: function(result) {
										console.log('error');
										alert('Ξαναπροσπαθήστε αργότερα!!');
									},
								});
								
								jQuery.ajax({
									url: '/wp-json/eeep/v1/metrics-units/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										jQuery('#input_1_16').html(result);
										/// Un-Lock drop down list
										jQuery('#input_1_16').attr('readonly', false);
										jQuery('#input_1_16').css('pointer-events', 'auto');
									},
									error: function(result) {
										console.log('error');
										alert('Ξαναπροσπαθήστε αργότερα!!');
									},
								});

								jQuery.ajax({
									url: '/wp-json/eeep/v1/data-type/' + jQuery(this).val(),
									type: 'GET',
									success: function(result) {
										console.log(result);
										jQuery('#field_1_23 .ginput_container').html(result);
										///$("#the_value").attr("type", result);
									},
									error: function(result) {
										///alert('Ξαναπροσπαθήστε αργότερα!');
									},
								});

							});
						});
				</script>

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</<?php echo et_core_intentionally_unescaped( $container_tag, 'fixed_string' ); ?>>

			<?php endwhile; ?>

		<?php if ( ! $is_page_builder_used ) : ?>

			</div>

			<?php 
				///get_sidebar();
			?>

		</div>
	</div>

<?php endif; ?>

</div>

<?php

get_footer();
