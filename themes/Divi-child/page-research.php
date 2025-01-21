<?php
	/*
		Custom page for Eeep / Custom search
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
							$foreing = false; 
							$slang = '';
							if (get_locale()=='en_GB') 
							{
								$foreing =true;
								$slang = 'en';
							}
							else
							{
								///$slang = 'el';
							}

							$author_id = get_visitor_id();

							echo '<h3>Έρευνες</h3>';

							$params = [];
							$params['limit'] = -1;
							///$params['where'] = 'post_author=' . $author_id;
							/// why 0?
						
						?>
						 <div class="form-container">
							<form id="query-form">
								<input type="hidden" id="slang" name="slang" value="<?php echo $slang; ?>" />
								<div id="dynamic-fields">
									<!-- Dynamic fields will be added here -->
								</div>
								<div class="buttons">
									<button type="button" class="btn" id="add-field">+ Προσθήκη πεδίου</button>
									<button type="submit" class="btn">Υποβολή</button>
									<!-- <button type="button" class="btn" id="submit-button">Υποβολή</button> -->
								</div>
							</form>
						</div>

						<style>
							body {
							font-family: Arial, sans-serif;
							padding: 20px;
							}
							.form-container {
							max-width: 600px;
							margin: 0 auto;
							}
							.field-group {
							margin-bottom: 15px;
							display: flex;
							align-items: center;
							}
							.field-group label {
							margin-right: 10px;
							}
							.field-group select, .field-group input {
							padding: 5px;
							margin-right: 10px;
							width: 200px;
							}
							.buttons {
							margin-top: 20px;
							}
							.btn {
							padding: 10px 20px;
							margin: 5px;
							cursor: pointer;
							background-color: #007BFF;
							color: white;
							border: none;
							border-radius: 4px;
							}
							.btn-danger {
							background-color: #dc3545;
							}
						</style>
						
						<script>
							var field_group_id = 0;
							// Predefined fields for selection
							const availableFields = [
							'Έτος',
							'Χώρα',
							'Έκθεση',
							'Πυλώνας',
							'Πυλώνας',
							'Κατηγορία',
							'Υποκατηγορία',
							'Δείκτης',
							'Ένδειξη',
							'Τιμή'
							];

							// Function to add a new field
							function addField() {
								const dynamicFieldsContainer = document.getElementById('dynamic-fields');
								const fieldGroup = document.createElement('div');
								fieldGroup.classList.add('field-group');
								field_group_id = field_group_id + 1;
								fieldGroup.id = field_group_id;
								
								const fieldLabel = document.createElement('label');
								fieldLabel.textContent = 'Επιλογή πεδίου:';
								
								// Field selection dropdown
								const fieldSelect = document.createElement('select');
								fieldSelect.classList.add('field-title');
								fieldSelect.innerHTML = `
									<option value="year">Έτος</option>
									<option value="country">Χώρα</option>
									<option value="report">Έκθεση</option>
									<option value="pylon">Πυλώνας</option>
									<option value="category">Κατηγορία</option>
									<option value="subcategor">Υποκατηγορία</option>
									<option value="indicator">Δείκτης</option>
									<option value="metric">Ένδειξη</option>
									<option value="value">Τιμή</option>`;
								/*
								availableFields.forEach(field => {
									const option = document.createElement('option');
									option.value = field;
									option.textContent = field;
									fieldSelect.appendChild(option);
								});
								*/

								const operatorLabel = document.createElement('label');
								operatorLabel.textContent = 'Τελεστής:';

								// Operator selection dropdown
								const operatorSelect = document.createElement('select');
								operatorSelect.innerHTML = `
									<option value="equals">Ίσο</option>
									<option value="greater_equal">Μεγαλύτερο ή ίσο</option>
									<option value="greater_than">Μεγαλύτερο από</option>
									<option value="less_equal">Μικρότερο ή ίσο</option>
									<option value="less_than">Μικρότερο από</option>
									<option value="contains">Περιέχει</option>

								`;
							
								const fieldInput = document.createElement('input');
								fieldInput.type = 'number';
								fieldInput.placeholder = 'Τιμή';
								fieldInput.classList.add('the-value');
							
								// Button to remove this field group
								const removeButton = document.createElement('button');
								removeButton.type = 'button';
								removeButton.classList.add('btn', 'btn-danger');
								removeButton.textContent = 'Κατάργηση';
								removeButton.onclick = function() {
									dynamicFieldsContainer.removeChild(fieldGroup);
								};

								// Append elements to the field group
								fieldGroup.appendChild(fieldLabel);
								fieldGroup.appendChild(fieldSelect);
								fieldGroup.appendChild(operatorLabel);
								fieldGroup.appendChild(operatorSelect);
								fieldGroup.appendChild(fieldInput);
								fieldGroup.appendChild(removeButton);

								// Append the new field group to the form container
								dynamicFieldsContainer.appendChild(fieldGroup);

								jQuery(".field-title").change(function () 
								{
									const parent = $(this).parent();
									const brother = $(parent).find('.the-value');
									if ( $(this).val() == 'year' || $(this).val() == 'value' )
									{
										brother.attr("type", 'number');
										console.log('number');
									}
									else
									{
										brother.attr("type", 'text');
										console.log('text');

									}
								});								
							}

							// Add the first field on page load
							window.onload = addField;

							// Add field button click handler
							document.getElementById('add-field').addEventListener('click', addField);

							/// Handle form submission
							document.getElementById('query-form').addEventListener('submit', function(event) {
								event.preventDefault();

								const formData = new FormData(event.target);
								const queryData = {};

								// Collect all the data from the dynamic fields
								const dynamicFields = document.querySelectorAll('.field-group');
								dynamicFields.forEach((fieldGroup, index) => {
									const field = fieldGroup.querySelector('select').value;
									const operator = fieldGroup.querySelectorAll('select')[1].value;
									const value = fieldGroup.querySelector('input').value;
									queryData[`field_${index + 1}`] = { field, operator, value };
								});

								console.log('Form Data:', queryData);

								// Here you would normally send the form data to your server, for example:
								fetch('/wp-json/eeep/v1/custom-query?slang=<?php echo $slang; ?>', {
								   method: 'POST',
								   body: JSON.stringify(queryData),
								   headers: { 'Content-Type': 'application/json' },
								})
								.then(response => response.json())
								.then(data => {
									console.log('Success:', data.toString());
									// Redirect to a new page with data passed as query parameters
        							///window.location.href = `/result/?reg_ids=`+data.toString();
        							window.open(`<?php echo ($slang ? '/' . $slang : ''); ?>/result/?reg_ids=`+data.toString(), '_blank');
									}
								)
								.catch(error => console.error('Error:', error));

							});

						</script>

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
