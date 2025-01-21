<?php
	/*
		Custom page for Eeep / Table
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

							$author_id = get_visitor_id();

							echo '<h3>Καταχωρημένα</h3>';
							echo get_locale();
							echo '~';

							$params = [];
							$params['limit'] = -1;
							
							echo '<table id="first_table">';
							?>
							<tfoot>
								<tr>
									<th>Έτος</th>
									<th>Χώρα</th>
									<th>Έκθεση</th>
									<th>Πυλώνας</th>
									<th>Κατηγορία</th>
									<th>Υποκατηγορία</th>
									<th>Τίτλος δείκτη</th>
									<th>Ένδειξη δεδομένων</th>
									<th>Τιμή</th>
								</tr>
							</tfoot>
							<style>
							
								#first_table tfoot { display: table-header-group; }

								th
								{
									text-align: center!important;
									font-size:1rem!important;
								}
								td
								{
									max-width:150px;
									font-size: 0.8rem;
									white-space: normal;
									padding: 5px!important;
								}
								tfooter td
								{
									text-align: left;

								}
								select
								{
									max-width:200px;
								}
							</style>

							<?php

							echo '<thead>';
							echo '<tr>';
							echo '<th>' . 'Έτος' . '</th>';
							echo '<th>' . 'Χώρα' . '</th>';
							echo '<th>' . 'Έκθεση' . '</th>';
							echo '<th>' . 'Πυλώνας' . '</th>';
							echo '<th>' . 'Κατηγορία' . '</th>';
							echo '<th>' . 'Υποκατηγορία' . '</th>';
							echo '<th>' . 'Τίτλος δείκτη' . '</th>';
							echo '<th>' . 'Ένδειξη δεδομένων' . '</th>';
							echo '<th>' . 'Τιμή' . '</th>';
							echo '</tr>';
							echo '</thead>';

							echo '<tbody>';
							$my_pods = pods('indicator_registry', $params);
							while ( $my_pods->fetch() )
							{
								/// Hops
								$year = substr($my_pods->field('documentation_report', true)['registration_date'], 0, 4);
								$documentation_report = pods('documentation_report', $my_pods->field('documentation_report.id'));
								$institution = pods('institution', $documentation_report->field('institution.id'));
								
								$indicator_pod = pods('indicator', $my_pods->field('indicator.id'));
								$subcategory = pods('indicator_subcategor', $indicator_pod->field('subcategory.id'));
								$category = pods('indicator_category', $subcategory->field('indicator_category.id'));
								$pylon_title = $category->display('indicator_pylon');

								///
								echo '<tr>';
								echo '<td style="text-align:center">' . $year . '</td>';
								echo '<td class="">' . $institution->display('country') . '</td>';
								echo '<td class="">' . $documentation_report->display('title') . '</td>';
								echo '<td class="">' . $pylon_title . '</td>';
								echo '<td class="">' . $category->display('title') . '</td>';
								echo '<td class="">' . $subcategory->display('title') . '</td>';
								echo '<td class="">' . $my_pods->display('indicator') . '</td>';
								echo '<td class="">' . $my_pods->display('metric_unit') . '</td>';
								echo '<td class="">' . $my_pods->display('value') . '</td>';
								echo '</tr>';
							}
							echo '</tbody>';
							echo '</table>';
						?>
						<div id="demo-output" style="margin-bottom: 1em;" class="chart-display"></div>
						<?php
							/// the_content();
							if ( ! $is_page_builder_used )
							{
								wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
							}					
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
