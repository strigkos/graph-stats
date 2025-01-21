<?php
	/*
		Plugin Name: Eeep
		Description: Eeep
		Text Domain: Eeep
		Author: Nomikos Strigkos
		Version: 1.0.0
	*/

    /// Returns Author / Writer id
    function get_visitor_id()
    {
        $viewer_id = 0;
        if ( isset($_GET['org']) )
        {
            $allow_view = false;
            if( current_user_can('editor') || current_user_can('administrator') )
            {
                $editor_id = get_current_user_id();
                $eval_param = [];
                $eval_param['limit'] = -1;
                ///$eval_param['where'] = 'd.auditor=' . $editor_id;
                $eval_pod = pods('evaluation', $eval_param);
                while ( $eval_pod->fetch() )
                {
                    foreach ( $eval_pod->field('auditor') as $auditor )
                    {
                        if ( $auditor['ID'] == $editor_id && $_GET['org'] == $eval_pod->field('application')['ID']) 
                        {
                            /*
                            * echo $editor_id;
                            * echo $auditor['ID'];
                            */
                            $allow_view = true;
                            $viewer_id = $_GET['org'];
                            
                        }
                    }
                }
            }
            
            if ( !$allow_view )
            {
                wp_redirect('/');
                exit;
            }
        }
        else
        {
            ///echo 2;
            $viewer_id = get_current_user_id();
        }
        
        return $viewer_id;
    }
    //add_action('wp_head', 'get_visitor_id');

    function datatables_scripts()
    {
		////wp_enqueue_script('datatables-bootstrap', 'https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js', 'datatables-script');
        wp_enqueue_script('datatables-script', 'https://cdn.datatables.net/2.1.8/js/dataTables.js', array('jquery'));		
		/*
		wp_enqueue_script('dataTables-select', 'https://cdn.datatables.net/select/2.1.0/js/dataTables.select.js');
		wp_enqueue_script('select-dataTables', 'https://cdn.datatables.net/select/2.1.0/js/select.dataTables.js');
		*/
		wp_enqueue_script('dataTables-select', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js');
		wp_enqueue_script('highcharts', '//code.highcharts.com/highcharts.js');

		wp_enqueue_style('datatables-style', 'https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css');
		wp_enqueue_style('select-style', 'https://cdn.datatables.net/select/2.1.0/css/select.dataTables.css');
		wp_enqueue_style('highcharts-style', '//code.highcharts.com/css/highcharts.css');
    }
    add_action( 'wp_enqueue_scripts', 'datatables_scripts' );

    function load_datatable()
    {
        ///echo '<script>new DataTable("#first_table");</script>';
        echo '<script>$("#first_table").DataTable();</script>';
    }
    ///add_action('wp_footer', 'load_datatable', PHP_INT_MAX);
	/// Maybe on each page call the add_action

	/// Maybe on each page call the add_action
	function load_highcharts()
    {
        ?>
		<script>
			// Create DataTable
			const table = new DataTable('#first_table');
			
			// Create chart
			const chart = Highcharts.chart('demo-output', {
				chart: {
					type: 'pie',
					styledMode: true
				},
				title: {
					text: 'Pie for Years'
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
					.column(0, { search: 'applied' })
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
    }
    ///add_action('wp_footer', 'load_highcharts', PHP_INT_MAX);

    //// Lista data & filters
    add_action( 'rest_api_init', function () {

		$this_plugin = 'eeep';
		
        ## Get Reports filtered
        /// example : https://eeep.app/wp-json/eeep/v1/doc-reports/2000
		register_rest_route( $this_plugin . '/v1', '/doc-reports/(?P<year>\d+)', 
        array('methods'=>'GET','callback'=>'get_doc_reports'));

		## Get Pylons filtered
        /// example : https://eeep.app/wp-json/eeep/v1/indi-pylons/123
		register_rest_route( $this_plugin . '/v1', '/indi-pylons/(?P<filter>\d+)', 
			array('methods'=>'GET','callback'=>'get_indi_pylons'));
		
		## Get categories filtered
		/// example : https://eeep.app/wp-json/eeep/v1/indi-categories/123
		register_rest_route( $this_plugin . '/v1', '/indi-categories/(?P<pylon>\w+)',
			array('methods'=>'GET','callback'=>'get_indi_categories'));
			
		## Get sub-categories filtered
        /// example : https://eeep.app/wp-json/eeep/v1/indi-subcategors/123
		register_rest_route( $this_plugin . '/v1', '/indi-subcategors/(?P<category>\w+)',
			array('methods'=>'GET','callback'=>'get_indi_subcategories'));

		## Get indicators filtered
        /// example : https://eeep.app/wp-json/eeep/v1/indicators/123
		register_rest_route( $this_plugin . '/v1', '/indicators/(?P<subcategory>\w+)',
        array('methods'=>'GET','callback'=>'get_indicators'));

		## Build the Title
        /// example : https://eeep.app/wp-json/eeep/v1/metrics-units/1
		register_rest_route( $this_plugin . '/v1', '/registry-title/(?P<indicator>\w+)',
        array('methods'=>'GET','callback'=>'build_registry_title'));

        ## Get metrics set
        /// example : https://eeep.app/wp-json/eeep/v1/metrics-units/1
		register_rest_route( $this_plugin . '/v1', '/metrics-units/(?P<indicator>\w+)',
        array('methods'=>'GET','callback'=>'get_metricsunits'));

		## Metric data type
        /// example : https://eeep.app/wp-json/eeep/v1/metric-type
		register_rest_route( $this_plugin . '/v1', '/metric-type/(?P<indicator>\w+)',
        array('methods'=>'GET','callback'=>'get_metric_datatype'));
		
		## Indicator data type
        /// example : https://eeep.app/wp-json/eeep/v1/data-type
		register_rest_route( $this_plugin . '/v1', '/data-type/(?P<indicator>\w+)',
        array('methods'=>'GET','callback'=>'get_indicator_datatype'));

		## Custom Query
        /// example : https://eeep.app/wp-json/eeep/v1/custom-query
		register_rest_route( $this_plugin . '/v1', '/custom-query',
        array('methods'  => ['GET', 'POST'], 'callback'=>'return_rows'));
			
	} );

	function get_symbol($label)
	{
/*
		<option value="equals">Ίσο</option>
		<option value="greater_equal">Μεγαλύτερο ή ίσο</option>
		<option value="greater_than">Μεγαλύτερο από</option>
		<option value="less_equal">Μικρότερο ή ίσο</option>
		<option value="less_than">Μικρότερο από</option>
		<option value="contains">Περιέχει</option>
*/
		if ($label == 'equals')
		{
			$operator = '=';
		}
		else if ($label == 'greater_than')
		{
			$operator = '>';
		}
		else if ($label == 'greater_equal')
		{
			$operator = '>=';
		}
		else if ($label == 'less_equal')
		{
			$operator = '<=';
		}
		else if ($label == 'less_than')
		{
			$operator = '<';
		}
		else if ($label == 'contains')
		{
			$operator = 'LIKE';
		}
		return $operator;
	}

	function return_rows(WP_REST_Request $request) 
	{
		$json_params = $request->get_json_params();
		///$json_params[] = array('field'=>'year', 'operator'=>'equals','value'=>2011);
		$query_params = $request->get_query_params();
		$slang = $query_params['slang'];
		//$pod_params = [];
		$reports = [];
		$indicators = [];
		$metric_units = [];
		$year_query = '';
		$value_query = '';

		foreach ($json_params as $jparam)
		{
			$field = $jparam['field'];
			$operator = $jparam['operator'];
			$symbol = get_symbol($operator);
			$value = $jparam['value'];

			$reports1 = [];
			if ( $symbol == 'LIKE' )
			{
				$value = "'%" . $value . "%'";
			}

			if ( $field == 'country' )  
			{
				/// find all Reports for this Country
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " name " . $symbol . " " . $value;
				}
				$country_pod = pods('country', $params);
				$institutions = [];
				while ( $country_pod->fetch() )
				{
					$institution_pod = pods('institution')->find();
					while ( $institution_pod->fetch() )
					{
			
						if ( $institution_pod->field('country' ,true)['id'] == $country_pod->id() )
						{
							$institutions[] = $institution_pod->id();
						}
					}
				}

				/// Must use $params OR find() to bring records, otherwise I just 'prepare' pod
				$report_pod = pods('documentation_report')->find();
				while ( $report_pod->fetch() )
				{
					if ( in_array($report_pod->field('institution', true)['id'], $institutions ) )
					{
						$reports1[] = $report_pod->id();
					}
				}
				if (empty($reports1)) { return []; }
			}
			elseif ($field == 'report')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$report_pod = pods('documentation_report', $params);
				while ( $report_pod->fetch() )
				{
					$reports1[] = $report_pod->id();
				}
				if (empty($reports1)) { return []; }
			}

			if (empty($reports)) 
			{
				$reports = $reports1;
			}
			else
			{
				$reports = array_intersect($reports, $reports1);
			}

			/// INDICATORS
			$indicators1 = [];
			$pylons = [];
			$categories = [];
			$subcats = [];
			if ( $field == 'pylon')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$pylon_pod = pods('indicator_pylon', $params);
				while ( $pylon_pod->fetch() )
				{
					$pylons[] = $pylon_pod->id();
				}

				$cat_pod = pods('indicator_category')->find();
				while ( $cat_pod->fetch() )
				{
					if (in_array($cat_pod->field('indicator_pylon', true)['id'], $pylons))
					{
						$categories[] = $cat_pod->id();
					}
				}

				$sub_pod = pods('indicator_subcategor')->find();
				while ( $sub_pod->fetch() )
				{
					if (in_array($sub_pod->field('indicator_category', true)['id'], $categories))
					{
						$subcats[] = $sub_pod->id();
					}
				}

				$ind_pod = pods('indicator')->find();
				while ( $ind_pod->fetch() )
				{
					if (in_array($ind_pod->field('subcategory', true)['id'], $subcats))
					{
						$indicators1[] = $ind_pod->id();
					}
				}
			}
			elseif ($field == 'category')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$cat_pod = pods('indicator_category', $params);
				while ( $cat_pod->fetch() )
				{
					$categories[] = $cat_pod->id();
				}

				$sub_pod = pods('indicator_subcategor')->find();
				while ( $sub_pod->fetch() )
				{
					if (in_array($sub_pod->field('indicator_category', true)['id'], $categories))
					{
						$subcats[] = $sub_pod->id();
					}
				}

				$ind_pod = pods('indicator')->find();
				while ( $ind_pod->fetch() )
				{
					if (in_array($ind_pod->field('subcategory', true)['id'], $subcats))
					{
						$indicators1[] = $ind_pod->id();
					}
				}
			}
			elseif ($field == 'subcategor')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$sub_pod = pods('indicator_subcategor', $params);
				while ( $sub_pod->fetch() )
				{
					$subcats[] = $sub_pod->id();
				}

				$ind_pod = pods('indicator')->find();
				while ( $ind_pod->fetch() )
				{
					if (in_array($ind_pod->field('subcategory', true)['id'], $subcats))
					{
						$indicators1[] = $ind_pod->id();
					}
				}
			}
			elseif ($field == 'indicator')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$ind_pod = pods('indicator', $params);
				while ( $ind_pod->fetch() )
				{
					$indicators1[] = $ind_pod->id();
				}
			}

			if (!empty($indicators1))
			{
				if (empty($indicators)) 
				{
					$indicators = $indicators1;
				}
				else
				{
					$indicators = array_intersect($indicators, $indicators1);
				}
			}

			/// METRICS
			$metrics1 = [];
			if ($field == 'metric')  
			{
				if ( $slang == 'en' )
				{
					$params['where'] = " title_en " . $symbol . " " . $value;
				}
				else
				{
					$params['where'] = " post_title " . $symbol . " " . $value;
				}
				$metric_pod = pods('metric_unit', $params);
				while ( $metric_pod->fetch() )
				{
					$metrics1[] = $metric_pod->id();
				}
			}
			if (empty($metric_units)) 
			{
				$metric_units = $metrics1;
			}
			else
			{
				$metric_units = array_intersect($metric_units, $metrics1);
			}

			/// YEAR QUERY
			if ($field == 'year')  
			{
				if (empty($year_query))
				{
					$year_query = 'year ' . $symbol . " " . $value;
				}
				else
				{
					$year_query .= ' AND ' .  'year ' . $symbol . " " . $value;
				}
			}

			/// VALUE QUERY
			if ($field == 'value')  
			{
				if (empty($value_query))
				{
					$value_query = 'value ' . $symbol . " " . $value;
				}
				else
				{
					$value_query .= ' AND ' .  'value ' . $symbol . " " . $value;
				}
			}
		}

		$reg_pod = pods('indicator_registry');
		if (!empty($year_query) && !empty($value_query)) 
		{
			$sql_query = $year_query . ' AND ' . $value_query;
		}
		else
		{
			$sql_query = $year_query . $value_query;
		}
		$all_params['where'] = $sql_query;
		$reg_pod->find($all_params);

		$reg_ids = [];
		while ( $reg_pod->fetch() )
		{
			if (
				( empty($reports) || in_array($reg_pod->field('documentation_report', true)['id'], $reports) )
				&& ( empty($indicators) || in_array($reg_pod->field('indicator', true)['id'], $indicators ) )
				&& ( empty($metric_units) || in_array($reg_pod->field('metric_unit', true)['id'], $metric_units ) )
			)
			{
				$reg_ids[] = $reg_pod->id();
			}
		}
		return new WP_REST_Response($reg_ids, 200);

	}

    /* 
        Better use "ID" instead of "id"
        Some times "id" is empty!!!
        "pod_item_id" has also the value of "ID"
    */
    function get_doc_reports(WP_REST_Request $request) 
	{
		$this_podname = 'documentation_report ';

		$year = $request['year'] ;
		
		try
		{
			$params['limit'] = -1;
			
			$my_pods = pods($this_podname, $params);
							
			$reports = '<option>' . '-- Επιλέξτε' . '</option>';
			
			while ( $my_pods->fetch() )
			{
				$reports .= '<option value="' . $my_pods->id() . '">' . $my_pods->display('post_title') . '</option>';
                /*
					$min_date = $year . '-01-01';
					$max_date = $year . '-12-31';
					if ( $min_date <= $my_pods->field('issue_date') && $my_pods->field('issue_date') <= $max_date )
					{
						$reports .= '<option value="' . $my_pods->id() . '">' . $my_pods->display('post_title') . '</option>';
					}
				*/
			}
			return $reports;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}

	function get_indi_pylons(WP_REST_Request $request) 
	{
		try
		{
			$pod_parmams = [];
			$pod_parmams['limit'] = -1;
			$resources_pod = pods('indicator_pylon', $pod_parmams);
			
			$resources = '<option>' . '-- Επιλέξτε' . '</option>';
			while ( $resources_pod->fetch() )
			{
                $resources .= '<option value="' . $resources_pod->id() . '">' . $resources_pod->display('name') . '</option>';
			}
			return $resources;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}
	
	function get_indi_categories(WP_REST_Request $request) 
	{
		$this_podname = 'indicator_category';
		$pylon = $request['pylon'];
		try
		{
			$params['limit'] = -1;
			$my_pods = pods($this_podname, $params);
			$categories = '<option>' . '-- Επιλέξτε' . '</option>';
			while ( $my_pods->fetch() )
			{
                if ( $my_pods->field('indicator_pylon')['ID'] == $pylon)
				{
                    $categories .= '<option value="' . $my_pods->id() . '">' . $my_pods->display('post_title') . '</option>';
                }
			}
			
			return $categories;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}
	
	function get_indi_subcategories(WP_REST_Request $request) 
	{
		$this_podname = 'indicator_subcategor';
		$catgory_ID = $request['category'];	
		try
		{
			$params['limit'] = -1;			
			$my_pods = pods($this_podname, $params);
			$sub_categories = '<option value="0">' . '-- Επιλέξτε' . '</option>';
			while ( $my_pods->fetch() )
			{
                if ( $my_pods->field('indicator_category')['ID'] == $catgory_ID )
				{
                    $sub_categories .= '<option value="' . $my_pods->id() . '">' . $my_pods->display('post_title') . '</option>';
                }
			}
			return $sub_categories;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}

    function get_indicators(WP_REST_Request $request) 
	{
		$this_podname = 'indicator';
		$subcategory_ID = $request['subcategory'];
		try
		{
			$params['limit'] = -1;			
			$my_pods = pods($this_podname, $params);
							
			$indicators = '<option value="0">' . '-- Επιλέξτε' . '</option>';

			while ( $my_pods->fetch() )
			{
				if ( $my_pods->field('subcategory')['ID'] == $subcategory_ID )
				{ 
                    $indicators .= '<option value="' . $my_pods->id() . '">' . $my_pods->display('post_title') . '</option>';
                }
			}
			return $indicators;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}

	/// Get Metrics Units FROM Metric Set OF THE Indicator
    function get_metricsunits(WP_REST_Request $request) 
	{
		$indicator_ID = $request['indicator'];
		try
		{
			$indicator_pod = pods('indicator', $indicator_ID);
			$metric_set_id = $indicator_pod->field('metric_set')['ID'];
			$params['limit'] = -1;
			$metric_unit_pod = pods('metric_unit', $params);
			$metrics_units = '<option value="0">' . '-- Επιλέξτε' . '</option>';
			while ( $metric_unit_pod->fetch() )
			{
				if ($metric_unit_pod->field('metric_set.id') == $metric_set_id )
				{
					$metrics_units .= '<option value="' . $metric_unit_pod->id() . '">' . $metric_unit_pod->display('post_title') . '</option>';
				}
			}
			return $metrics_units;
		}
		catch(Exception $exc)
		{
			return '<option value="0">FALSE</option>';
		}
	}
	
	function build_registry_title(WP_REST_Request $request)
	{	
		$indicator_ID = $request['indicator'];
		$indicator_pod = pods('indicator', $indicator_ID);
		$subcat_pod = pods('indicator_subcategor', $indicator_pod->field('subcategory'));
		$category_pod = pods('indicator_category', $subcat_pod->field('indicator_category'));
		$pylon_pod = pods('indicator_pylon', $category_pod->field('indicator_pylon'));
		$the_title = $pylon_pod->field('code') . ', ' . $category_pod->field('code') . ', ' . $subcat_pod->field('code') . ', ' . $indicator_pod->field('code')  . ', ' . $indicator_pod->field('title');
		return $the_title;
	}

	/// Get Metrics Units FROM Metric Set OF THE Indicator
	/// returns value input
	function get_indicator_datatype(WP_REST_Request $request)
	{
		$indicator_ID = $request['indicator'];
		try
		{
			$indicator_pod = pods('indicator', $indicator_ID);
			$data_type = $indicator_pod->field('data_type');
			if ($data_type == 'yes_no')
			{
				$input_control = '<fieldset id="field_1_23" class="gfield gfield--type-radio gfield--type-choice gfield--input-type-radio gfield--width-full field_sublabel_below gfield--no-description field_description_below field_validation_below gfield_visibility_visible" data-js-reload="field_1_23">
					<!-- <legend class="gfield_label gform-field-label">Επιλέξτε</legend> -->
					<div class="ginput_container ginput_container_radio" bis_skin_checked="1">
						<div class="gfield_radio" id="input_1_23" bis_skin_checked="1">
							<div class="gchoice gchoice_1_23_0" bis_skin_checked="1">
								<input class="gfield-choice-input" name="input_23" type="radio" value="yes" id="choice_1_23_0" onchange="gformToggleRadioOther( this )">
								<label for="choice_1_23_0" id="label_1_23_0" class="gform-field-label gform-field-label--type-inline">ΝΑΙ</label>
							</div>
							<div class="gchoice gchoice_1_23_1" bis_skin_checked="1">
								<input class="gfield-choice-input" name="input_23" type="radio" value="no" id="choice_1_23_1" onchange="gformToggleRadioOther( this )">
								<label for="choice_1_23_1" id="label_1_23_1" class="gform-field-label gform-field-label--type-inline">ΟΧΙ</label>
							</div>
						</div>
					</div>
				</fieldset>';
				$input_control .= '<style>
						#input_1_23 {
							display: flex;
							flex-direction: row;
							gap: 10px;
						}

						.gchoice {
							width: auto!important;
							float: left!important;
						}
					</style>';
			}
			else
			{
				$input_control = '<input id="input_1_23" name="input_23" class="medium gfield_select" type="' . $data_type . '" />';
			}			
		}
		catch(Exception $exc)
		{
			$input_control = '<input id="input_1_23" name="input_23" class="medium gfield_select" type="text" />';
		}
		$input_control .= '<br />Πληκτρολογήστε ή επιλέξτε από τη λίστα';

		return $input_control;

	}

	/// Get Metrics Units FROM Metric Set OF THE Indicator
	/// returns value input
	/*
	function get_metric_datatype(WP_REST_Request $request)
	{
		$indicator = $request['indicator'];
		try
		{
			
			$indicator_pod = pods('indicator', $indicator);
			$metric_set_id = $indicator_pod->field('metric_set.id');
			$metric_set_pod = pods('metric_set', $metric_set_id);
			$metric_type = $metric_set_pod->field('data_type');

			if ($metric_type == 'yes_no')
			{
				$input_control = '<input id="input_1_23" name="input_23" class="medium gfield_select" type="text" list="values" /> ';
				$input_control .= '<datalist id="values"> <option value="ΝΑΙ"> <option value="ΟΧΙ"> </datalist>';
				<style>
					#the_value {
						--gf-local-padding-x: var(--gf-ctrl-select-padding-x);
						background-image: var(--gf-ctrl-select-icon);
						background-position: var(--gf-ctrl-select-icon-position);
						background-repeat: no-repeat;
						background-size: var(--gf-ctrl-select-icon-size);
					}

					#the_value::-webkit-calendar-picker-indicator {
						display: none !important;
					}
				</style>
			}
			else
			{
				$input_control = '<input id="input_1_23" name="input_23" class="medium gfield_select" type="' . $metric_type . '" />';
			}			
		}
		catch(Exception $exc)
		{
			$input_control = '<input id="input_1_23" name="input_23" class="medium gfield_select" type="text" />';
		}
		$input_control .= '<br />Πληκτρολογήστε ή επιλέξτε από τη λίστα';
		return $input_control;
	}
	*/

?>
