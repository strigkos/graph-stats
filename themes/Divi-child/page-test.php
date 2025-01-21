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

					<div class="entry-content" style="margin-left:10px; margin-right:10px">

						<!-- Coding -->
						<?php
							
							$indicator = 615;
							$indicator_pod = pods('indicator', $indicator);
							$metric_set_id = $indicator_pod->field('metric_set.id');
							$metric_set_pod = pods('metric_set', $metric_set_id);
							$metric_unit_pod = pods('metric_unit')->find();
							$metrics_units = '<option>' . '-- Επιλέξτε' . '</option>';
							while ( $metric_unit_pod->fetch() )
							{
								if ($metric_unit_pod->field('metric_set.id') == $metric_set_pod->id() )
								{
									$metrics_units .= '<option value="' . $metric_unit_pod->id() . '">' . $metric_unit_pod->display('post_title') . '</option>';
								}
							}
							echo '<select>';
							echo $metrics_units;
							echo '</select>';
						?>

					</div>

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
