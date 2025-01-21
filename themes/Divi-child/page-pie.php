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
						?>
						<tfoot>
							<tr>
								<th><select id="filter-year" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-country" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-report" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-pylon" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-category" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-subcategor" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-indicator" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-metric" multiple="multiple" style="width: 200px;"></th>
								<th><select id="filter-value" multiple="multiple" style="width: 200px;"></th>
							</tr>
						</tfoot>
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
							$year = $my_pods->field('year');
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
							echo '<td class="">' . $my_pods->display('indicator') . '</td>';
							echo '<td class="">' . $my_pods->display('metric_unit') . '</td>';
							echo '<td class="">' . $my_pods->display('value') . '</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						?>
						<style>
							#second_table tfoot { display: table-header-group; }

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
							/// the_content();
							if ( ! $is_page_builder_used )
							{
								wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
							}					
						?>
					</div>

					<form id="types">
						<label style="display: inline-block; margin-right: 10px;">
							<input type="radio" name="type" value="pie" checked> Πίτα
						</label>
						<!--
						<label style="display: inline-block; margin-right: 10px;">
							<input type="radio" name="type" value="column"> Μπάρες 
						</label>
						<label style="display: inline-block;">
							<input type="radio" name="type" value="spline"> Καμπύλη
						</label>
						-->
					</form>
					<div id="demo-output" style="margin: 1em auto; width: 80%; " class="chart-display"></div>
					<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2/dist/css/select2.min.css">
					<script src="https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js"></script>
					
					<!--
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
					-->

					<script>

						$(document).ready(function () {

							const table = new DataTable('#second_table', {
							initComplete: function () {
								this.api()
									.columns()
									.every(function () {
										let column = this;			
										///console.log(column.data().unique() .sort());

										// Get select element
										let footer = column.footer();
									
										let select = $(footer).find('select');
										$(select).add(new Option('1'));

										// Add list of options
										column
											.data()
											.unique()
											.sort()
											.each(function (d, j) {
												select.append(new Option(d));
												///console.log(1);
											});
									});
								}
							});

							// Initialize Select2 for multi-select dropdowns
							/// $('#filter-status').select2();	/// ex.

							$('#filter-year').select2();
							$('#filter-country').select2();
							$('#filter-report').select2();
							$('#filter-pylon').select2();
							$('#filter-category').select2();
							$('#filter-subcategor').select2();
							$('#filter-indicator').select2();
							$('#filter-metric').select2();
							$('#filter-value').select2();

							// Initialize DataTable
							///var table = $('#second_table').DataTable();

							// Function to filter table based on dropdown selections
							function filterTable() {
								/// var selectedStatuses = $('#filter-status').val() || []; 		///ex.

								var selected_year = $('#filter-year').val() || [];
								var selected_country = $('#filter-country').val() || [];
								var selected_report = $('#filter-report').val() || [];
								var selected_pylon = $('#filter-pylon').val() || [];
								var selected_category = $('#filter-category').val() || [];
								var selected_subcategor = $('#filter-subcategor').val() || [];
								var selected_indicator = $('#filter-indicator').val() || [];
								var selected_metric = $('#filter-metrics').val() || [];
								var selected_value = $('#filter-value').val() || [];

								table.draw();
							}

							// Attach change event to dropdowns
							/// $('#filter-status').on('change', function () { filterTable(); }); 	/// ex. 

							$('#filter-year').on('change', function () { filterTable(); });
							$('#filter-country').on('change', function () { filterTable(); });
							$('#filter-report').on('change', function () { filterTable(); });
							$('#filter-pylon').on('change', function () { filterTable(); });
							$('#filter-category').on('change', function () { filterTable(); });
							$('#filter-subcategor').on('change', function () { filterTable(); });
							$('#filter-indicator').on('change', function () { filterTable(); });
							$('#filter-metric').on('change', function () { filterTable(); });
							$('#filter-value').on('change', function () { filterTable(); });

							// Custom filter for DataTable
							$.fn.dataTable.ext.search.push(function (settings, data) {
								
								/// Data
								/// var status = data[2]; // Status column

								var year = data[0]; // Category column
								var country = data[1]; // Category column
								var report = data[2]; // Category column
								var pylon = data[3]; // Category column
								var category = data[4]; // Category column
								var subcategory = data[5]; // Category column
								var indicator = data[6]; // Category column
								var metric = data[7]; // Category column
								var value = data[8]; // Category column

								/// var selectedStatuses = $('#filter-status').val() || [];		/// ex.
								var selected_years = $('#filter-year').val() || [];
								var selected_countries = $('#filter-country').val() || [];
								var selected_reports = $('#filter-report').val() || [];
								var selected_pylons = $('#filter-pylon').val() || [];
								var selected_categories = $('#filter-category').val() || [];
								var selected_subcategors = $('#filter-subcategor').val() || [];
								var selected_indicators = $('#filter-indicator').val() || [];
								var selected_metrics = $('#filter-metric').val() || [];
								var selected_values = $('#filter-value').val() || [];

								// Check if row matches the selected filters
								if 
								(
									/// (selectedStatuses.length === 0 || selectedStatuses.includes(status)) && 
									(selected_years.length === 0 || selected_years.includes(year)) &&
									(selected_countries.length === 0 || selected_countries.includes(country)) &&
									(selected_reports.length === 0 || selected_reports.includes(report)) &&
									(selected_pylons.length === 0 || selected_pylons.includes(pylon)) && 
									(selected_categories.length === 0 || selected_categories.includes(category)) && 
									(selected_subcategors.length === 0 || selected_subcategors.includes(subcategory)) && 
									(selected_indicators.length === 0 || selected_indicators.includes(indicator)) && 
									(selected_metrics.length === 0 || selected_metrics.includes(metric)) && 
									(selected_values.length === 0 || selected_values.includes(value))
								)
								{
									return true;
								}
								return false;
							});

							// Create DataTable
							/// const table2 = new DataTable('#second_table');

							$('input[name="type"]').on('change', function() {
								console.log(1);
								table.draw();
							});
							// On each draw, update the data in the chart
							table.on('draw', function () {

								let timeData = [];
								let valueData = [];
								let type = $('input[type="radio"][name="type"]:checked').val();
								///console.log(type);

								// Loop through the rows of the DataTable
								$('#second_table tbody tr').each(function() {
									let time = $(this).find('td').eq(0).text();  // Get the time value (first column)
									let value = $(this).find('td').eq(8).text();  // Get the value (second column)

									timeData.push(parseInt(time));  // Store time values
									valueData.push(parseInt(value));  // Store value data

								});

								// Create chart
								const chart = Highcharts.chart('demo-output', {
									chart: {
										type: type,
										styledMode: true
									},
									title: {
										text: 'Γράφημα τιμών'
									},
									xAxis: {
										title: {
											text: 'Έτη'  // Label for the x-axis
										},
										categories: timeData  // Optional: Specify x-axis categories (e.g., [1, 2, 3, 4])
									},
									yAxis: {
										title: {
											text: 'Τιμή'  // Label for the y-axis
										}
									},
									series: [
										{
											name: 'Τιμή',
											data: valueData
										}
									]
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
<style>.highcharts-credits { display:none; }</style>
<?php

get_footer();
