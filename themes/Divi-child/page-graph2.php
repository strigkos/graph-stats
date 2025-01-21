<?php
	/*
		Custom page for Eeep
		All types of graphs
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
							///$author_id = get_current_user_id();
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
								<th></th>
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

							/// Actions
							$current_user = wp_get_current_user();
							if ( in_array($current_user->user_login, ['eeep', 'mike', 'uat', 'wpadmin'])) { echo '<th>' . 'Actions' . '</th>'; }

						echo '</tr>';
						echo '</thead>';

						echo '<tbody>';
						$my_pods = pods('indicator_registry', $params);
						$foreing = false;
						if ( get_locale()=='en_GB' ) 
						{
							$foreing =true;
							$slang = 'en';
						}				
						while ( $my_pods->fetch() )
						{
							if(1)
							{
							/// Hops
							$year = $my_pods->field('year');
							$documentation_report = pods('documentation_report', $my_pods->field('documentation_report.id'));
							$institution = pods('institution', $documentation_report->field('institution.id'));
							
							$indicator_pod = pods('indicator', $my_pods->field('indicator.id'));
							$subcategory = pods('indicator_subcategor', $indicator_pod->field('subcategory.id'));
							$category = pods('indicator_category', $subcategory->field('indicator_category.id'));
							
							/// Create row
							echo '<tr>';
							echo '<td style="text-align:center">' . $year . '</td>';
							echo '<td class="">' . ( ( $foreing && $my_pods->field('country')[0]['title_en'] ) ? $my_pods->field('country')[0]['title_en'] : $my_pods->field('country')[0]['name']) . '</td>';
							echo '<td class="">' . ( ( $foreing && $documentation_report->display('title_en') ) ? $documentation_report->display('title_en') : $documentation_report->display('title')) . '</td>';
							echo '<td class="">' . ( ( $foreing && $category->field('indicator_pylon.title_en') ) ? $category->field('indicator_pylon.title_en') : $category->display('indicator_pylon') ) . '</td>';
							echo '<td class="">' . ( ( $foreing && $category->display('title_en') ) ? $category->display('title_en') : $category->display('title') ). '</td>';
							echo '<td class="">' . ( ( $foreing && $subcategory->field('title_en') ) ? $subcategory->field('title_en') : $subcategory->display('title') ) . '</td>';
							echo '<td class="">' . ( ( $foreing && $my_pods->field('indicator.title_en') ) ? $my_pods->field('indicator.title_en') : $my_pods->display('indicator') ) . '</td>';
							echo '<td class="">' . ( ( $foreing && $my_pods->field('metric_unit.title_en') ) ? $my_pods->field('metric_unit.title_en') : $my_pods->display('metric_unit') ) . '</td>';
							echo '<td>' . $my_pods->display('value') . '</td>';
							if ( in_array($current_user->user_login, ['eeep', 'mike', 'uat', 'wpadmin']) ) 
							{
								$post_id = $my_pods->id();
								$edit_link = '<a target="_blank" href="https://demo5.wifins.com/wp-admin/post.php?post='. $post_id . '&action=edit">Edit</a>';
								echo '<td style="text-align:center">' . $edit_link . '</td>';
							}
							echo '</tr>
							';
							}
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

					<form id="graph_types" style="display: flex; border-top:1px solid #ccc;">
						<div style="flex:1; padding:10px; text-align:right; border-right:1px solid #ccc; margin-top:5px">
							<label style="display:inline-block; margin-right:10px;">
								<input type="radio" name="type" value="line"> Γραμμή
							</label>
							<label style="display:inline-block;">
								<input type="radio" name="type" value="spline"> Καμπύλη
							</label>
							<label style="display:inline-block; margin-right:10px;">
								<input type="radio" name="type" value="column"> Μπάρες 
							</label>
						</div>
						
						<div id="graph_pie" style="flex:1; padding:10px; text-align:left; margin-top:5px;">
							<label style="display:inline-block; margin-right:10px;">
								<input type="radio" name="type" value="pie"> Πίτα 
							</label>	
							<!-- <b><a href="/graphs" class="button" style="text-decoration:underline">Πίτα</a></b> -->
						</div>
					</form>
					
					<div id="demo-output" style="margin: 1em auto; width: 80%; " class="chart-display"></div>
					<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
					<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js"></script>
					<script src="https://cdn.jsdelivr.net/npm/select2/dist/js/i18n/el.js"></script>

					<script>

						$(document).ready(function () {

							const table = new DataTable('#second_table', {
								initComplete: function () {
									this.api()
										.columns()
										.every(function () {
											let column = this;			

											// Get select element
											let footer = column.footer();
										
											let select = $(footer).find('select');
											$(select).add(new Option('1'));

											// Add list of options
											column.data().unique().sort().each(function (d, j) { select.append(new Option(d)); });
										});
									}
								});

							// Initialize Select2 for multi-select dropdowns
							$('#filter-year').select2();
							$('#filter-country').select2();
							$('#filter-report').select2();
							$('#filter-pylon').select2();
							$('#filter-category').select2();
							$('#filter-subcategor').select2();
							$('#filter-indicator').select2();
							$('#filter-metric').select2();
							$('#filter-value').select2();

/** ******************************************************* NEW CODE */

							// Function to filter table based on dropdown selections
							function filterTable(input) {

								console.log('filtered_table');

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

								/// New code to filter the filters
								let input_id = input[0].id;
								table.columns().every(function () {
									let column = this;

									// Get the select element from the footer
									let footer = column.footer();
									let select = $(footer).find('select');
									
									// Get data for the column only from rows that are filtered (search: 'applied')
									var filteredData = table.rows({ search: 'applied' }).data();

									// Create a set of unique values for the column from filtered data
									let uniqueValues = new Set();
									filteredData.each(function (rowData) {
										uniqueValues.add(rowData[column.index()]); // Add value from the current column
									});

									// Sort the unique values
									let sortedValues = Array.from(uniqueValues).sort();

									var currentValues = filteredData.toArray();

									//  if filter value added
									select.find('option').each(function() {
										let optionValue = $(this).val();
										// Remove the option if it's not in the filtered data
										if (!sortedValues.includes(optionValue) && input_id != select[0].id) {
											$(this).remove();
										}
									});
								});
							}

							function un_filter_table(input) {
								console.log('un_filtered_table');

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

								table.columns()
									.every(function () {
										let column = this;

										// Get select element
										let footer = column.footer();
									
										let select = $(footer).find('select');
										$(select).empty();

										///$(select).add(new Option('1'));

										// Add list of options
										column.data().unique().sort().each(function (d, j) { select.append(new Option(d)); });
									});								

							}

							const previousSelections = {};	// Store previous selections
							const currentSelections = {};	// Declare the object first
							const addedOptions = {};		// Declare the object first
							const removedOptions = {};		// Declare the object first
							$('#second_table tfoot select').on('change', function (input) {

								const selectId = $(this).attr('id'); // Get the ID using jQuery

								currentSelections[selectId] = $(this).val() || []; // Get the current selected options (array)

								// Determine added options
								if (previousSelections[selectId])
								{
									///console.log(previousSelections[selectId]);
									addedOptions[selectId] = currentSelections[selectId].filter(option => !previousSelections[selectId].includes(option));
								
									console.log(addedOptions[selectId].length);
									if ( addedOptions[selectId].length > 0)
									{
										console.log('Added options:', addedOptions[selectId]);
										// Add functionality for added options
										filterTable($(this));

									}

									// Determine removed options
									removedOptions[selectId] = previousSelections[selectId].filter(option => !currentSelections[selectId].includes(option));
									if (removedOptions[selectId].length > 0) 
									{
										console.log('Removed options:', removedOptions[selectId]);
										// Add functionality for removed options
										un_filter_table($(this));

									}
								}
								else
								{
									console.log('First');
									filterTable($(this));
								}

								// Update previousSelections for the next change
								previousSelections[selectId] = currentSelections[selectId];

							});

							/** ******************************************************* UNTIL HERE */



							// Custom filter for DataTable
							$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {

								var year = data[0];
								var country = data[1];			// Text column
								var report = data[2]; 			// Text column
								var pylon = data[3]; 			// Text column
								var category = data[4]; 		// Text column
								var subcategory = data[5]; 		// Text column
								var indicator = data[6]; 		// Text column
								var metric = data[7];
								var value = data[8];

								var selected_years = $('#filter-year').val() || [];
								var selected_countries = $('#filter-country').val() || [];
								var selected_reports = $('#filter-report').val() || [];
								var selected_pylons = $('#filter-pylon').val() || [];
								var selected_categories = $('#filter-category').val() || [];
								var selected_subcategors = $('#filter-subcategor').val() || [];
								var selected_indicators = $('#filter-indicator').val() || [];
								var selected_metrics = $('#filter-metric').val() || [];
								var selected_values = $('#filter-value').val() || [];

								/// BUG : data are the DataTable data BUT IT BRINGS WITHOUT GREEK PUNCTUATION
								// FIX : Συνάρτηση για αφαίρεση τόνων από ελληνικά γράμματα στο search term
								function removeGreekAccents(text) 
								{
									return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
								}

								// Αφαίρεση τόνων από όλα τα στοιχεία του πίνακα
								selected_countries = selected_countries.map(item => removeGreekAccents(item));
								selected_reports = selected_reports.map(item => removeGreekAccents(item));
								selected_pylons = selected_pylons.map(item => removeGreekAccents(item));
								selected_categories = selected_categories.map(item => removeGreekAccents(item));
								selected_subcategors = selected_subcategors.map(item => removeGreekAccents(item));
								selected_indicators = selected_indicators.map(item => removeGreekAccents(item));
								selected_metrics = selected_metrics.map(item => removeGreekAccents(item));

								// Check if row matches the selected filters
								if 
								(
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
								table.draw();
							});

							/// FOR PIES
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

							// On each draw, update the data in the chart
							table.on('draw', function () {

								let timeData = [];
								let valueData = [];
								let type = $('input[type="radio"][name="type"]:checked').val();

								// Create chart if type is (line, spline, column)
								if ( type == 'line' || type == 'spline' ||  type == 'column' )
								{
									// Loop through the rows of the DataTable
									$('#second_table tbody tr').each(function() {
										let time = $(this).find('td').eq(0).text();  // Get the time value (first column)
										let value = $(this).find('td').eq(8).text();  // Get the value (second column)

										timeData.push(parseInt(time));  // Store time values
										valueData.push(parseInt(value));  // Store value data

									});

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
								}
								else if ( type == 'pie')
								{
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
									chart.series[0].setData(chartData(table));
								}
							});
						});
					</script>
					<style>
						[data-dt-column="0"] .select2-container,
						[data-dt-column="1"] .select2-container,
						[data-dt-column="7"] .select2-container,
						[data-dt-column="8"] .select2-container {
							max-width: 100px;
						}
						[data-dt-column="2"] .select2-container,
						[data-dt-column="3"] .select2-container,
						[data-dt-column="4"] .select2-container {
							max-width: 120px;
						}
					</style>

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
