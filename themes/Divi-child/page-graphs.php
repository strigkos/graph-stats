<?php
	/*
		Custom page for Eeep
		Page for "pie" diagram
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

						///$author_id = get_current_user_id();
						$author_id = get_visitor_id();
						/// echo intval($author_id);
						/// why 0?

						echo '<h3>Καταχωρημένα</h3>';

						$params = [];
						$params['limit'] = -1;
						///$params['where'] = 'post_author=' . $author_id;
						/// why 0?
						
						echo '<table id="second_table" class="display" style="width:100%">';
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
							echo '<td class="">' . $year . '</td>';
							echo '<td class="">' . $institution->display('country') . '</td>';
							echo '<td class="">' . $documentation_report->display('title') . '</td>';
							echo '<td class="">' . $pylon_title . '</td>';
							echo '<td class="">' . $category->display('title') . '</td>';
							echo '<td class="">' . $subcategory->display('title') . '</td>';
							echo '<td class="">' . str_replace('(', '<br />(', $my_pods->display('indicator')) . '</td>';
							echo '<td class="">' . $my_pods->display('metric_unit') . '</td>';
							echo '<td class="">' . $my_pods->display('value') . '</td>';
							echo '</tr>';
						}
						echo '</tbody>';
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
						<?php
							echo '</table>';
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
						new DataTable('#second_table', {
							initComplete: function () {
								this.api()
									.columns()
									.every(function () {
										let column = this;
						
										// Create select element
										let select = document.createElement('select');
										select.add(new Option(''));
										column.footer().replaceChildren(select);
						
										// Apply listener for user change in value
										select.addEventListener('change', function () {
											column
												.search(select.value, {exact: true})
												.draw();
										});
						
										// Add list of options
										column
											.data()
											.unique()
											.sort()
											.each(function (d, j) {
												select.add(new Option(d));
											});
									});
							}
						});
					</script>
					<style>
						select {
							max-width:360px;
						}
					</style>
					<style>.highcharts-credits { display:none; }</style>
					<form id="graph_types" style="display: flex; border-top:1px solid #ccc;" onclick="redirect_to_istogram()">
						
						<div style="flex:1; padding:10px; text-align:right; border-right:1px solid #ccc; margin-top:5px" >
							<label style="display:inline-block; margin-right:10px;">
								<input type="radio" name="type" value="line" onclick="redirect_to_istogram()"> Γραμμή
							</label>
							<label style="display:inline-block; margin-right:10px;">
								<input type="radio" name="type" value="column" onclick="redirect_to_istogram()"> Μπάρες 
							</label>
							<label style="display:inline-block;">
								<input type="radio" name="type" value="spline" onclick="redirect_to_istogram()"> Καμπύλη
							</label>
						</div>
						
						<div id="graph_pie" style="flex:1; padding:10px; text-align:left; margin-top:5px;">
							<b><a href="/graphs" class="button" style="text-decoration:underline">Πίτα</a></b>
						</div>
					</form>
					<script>
						function redirect_to_istogram() {
							window.location.href = '/graph2';
						}
					</script>
					<div id="demo-output" style="margin: 1em auto; width: 80%; " class="chart-display"></div>
					<script>
						// Create DataTable
						const table = new DataTable('#second_table');
						
						// Create chart
						const chart = Highcharts.chart('demo-output', {
							chart: {
								type: 'pie',
								styledMode: true
							},
							title: {
								text: 'Γράφημα'
							},
							series: [
								{
									data: chartData(table)
								}
							]
						});
						
						// On each draw, update the data in the chart
						table.on('draw', function () {
							chart.series[0].setData(chartData(table));
						});
						
						function chartData(table) {
							var counts = {};
						
							// Count the number of entries for each position
							table
								/// select whcih column
								.column(8, { search: 'applied' })
								.data()
								.each(function (val) {
									if (counts[val]) {
										counts[val] += 1;
									}
									else {
										counts[val] = 1;
									}
								});
						
							return Object.entries(counts).map((e) => ({
								name: e[0],
								y: e[1]
							}));
						}
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
