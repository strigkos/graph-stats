<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\QueryFilters;

use Exception;
use GF_Query_Condition;
use GravityKit\GravityCharts\QueryFilters\Condition\ConditionFactory;
use GravityKit\GravityCharts\QueryFilters\Filter\EntryFilterService;
use GravityKit\GravityCharts\QueryFilters\Filter\Filter;
use GravityKit\GravityCharts\QueryFilters\Filter\FilterFactory;
use GravityKit\GravityCharts\QueryFilters\Filter\RandomFilterIdGenerator;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\CurrentUserVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\DisableAdminVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\DisableFiltersVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\EntryAwareFilterVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\FilterVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\ProcessDateVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\ProcessFieldTypeVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\ProcessMergeTagsVisitor;
use GravityKit\GravityCharts\QueryFilters\Filter\Visitor\UserIdVisitor;
use GravityKit\GravityCharts\QueryFilters\Repository\DefaultRepository;
use GravityKit\GravityCharts\QueryFilters\Sql\SqlAdjustmentCallbacks;
use GravityView_Entry_Approval_Status;
use RuntimeException;

class QueryFilters {
	/**
	 * @since 1.0
	 * @var array Assets handle.
	 */
	public const ASSETS_HANDLE = 'gk-query-filters';

	/**
	 * @since 1.0
	 * @var Filter Filters.
	 */
	private $filters;

	/**
	 * @since 1.0
	 * @var array GF Form.
	 */
	private $form = [];

	/**
	 * @since 2.0.0
	 * @var FilterFactory
	 */
	private $filter_factory;

	/**
	 * @since 2.0.0
	 * @var ConditionFactory
	 */
	private $condition_factory;

	/**
	 * @since 2.0.0
	 * @var DefaultRepository
	 */
	private $repository;

	/**
	 * @since 2.0.0
	 * @var EntryFilterService
	 */
	private $entry_filter_service;

	/**
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->filter_factory       = new FilterFactory( new RandomFilterIdGenerator() );
		$this->condition_factory    = new ConditionFactory();
		$this->repository           = new DefaultRepository();
		$this->entry_filter_service = new EntryFilterService( $this->repository );
	}

	/**
	 * Convenience create method.
	 * @return QueryFilters
	 * @since 2.0.0
	 */
	public static function create(): QueryFilters {
		return new QueryFilters();
	}

	/**
	 * Sets form on class instance.
	 *
	 * @param array $form GF Form.
	 *
	 * @return void
	 * @throws \Exception
	 * @internal
	 *
	 * @since 1.0
	 */
	public function set_form( array $form ) {
		if ( ! isset( $form['id'], $form['fields'] ) ) {
			throw new Exception( 'Invalid form object provided.' );
		}

		$this->form = $form;
	}

	/**
	 * Creates immutable instance with form data.
	 *
	 * @param array $form The form object.
	 *
	 * @return QueryFilters
	 * @throws \Exception
	 * @since 2.0.0
	 */
	public function with_form( array $form ): QueryFilters {
		$clone = clone $this;
		$clone->set_form( $form );

		return $clone;
	}

	/**
	 * Sets filters on class instance.
	 *
	 * @param array $filters Field filters.
	 *
	 * @return void
	 * @throws \Exception
	 * @internal
	 *
	 * @since 1.0
	 */
	public function set_filters( array $filters ) {
		$this->filters = $this->filter_factory->from_array( $filters );
	}

	/**
	 * Creates immutable instance with different filters.
	 *
	 * @param array $filters Field filters.
	 *
	 * @return QueryFilters
	 * @throws \Exception
	 * @since 2.0.0
	 */
	public function with_filters( array $filters ): QueryFilters {
		$clone = clone $this;
		$clone->set_filters( $filters );

		return $clone;
	}

	/**
	 * Converts filters and returns GF Query conditions.
	 *
	 * @return GF_Query_Condition|null
	 * @throws RuntimeException
	 * @since 1.0
	 */
	public function get_query_conditions() {
		if ( empty( $this->form ) ) {
			throw new RuntimeException( 'Missing form object.' );
		}

		if ( ! $this->filters instanceof Filter ) {
			return null;
		}

		add_filter( 'gform_gf_query_sql', function ( array $query ): array {
			return SqlAdjustmentCallbacks::sql_empty_date_adjustment( $query );
		} );

		return $this->condition_factory->from_filter( $this->get_filters(), $this->form['id'] );
	}

	/**
	 * The filter visitors that finalize abstract filters.
	 * @return FilterVisitor[] The visitors.
	 * @filter `gk/query-filters/filter/visitors` The filters to be applied to the query.
	 * @since  2.0.0
	 */
	private function get_filter_visitors(): array {
		$visitors = [
			new DisableFiltersVisitor(),
			new CurrentUserVisitor( $this->repository ),
			new DisableAdminVisitor( $this->repository, $this->form ),
			new ProcessMergeTagsVisitor( $this->repository, $this->form ),
			new UserIdVisitor( $this->repository, $this->form ),
			new ProcessDateVisitor( $this->repository, $this->form ),
			new ProcessFieldTypeVisitor( $this->repository, $this->form ),
		];

		$visitors = apply_filters( 'gk/query-filters/filter/visitors', $visitors, $this->form );

		return array_filter( $visitors, function ( $visitor ): bool {
			return $visitor instanceof FilterVisitor;
		} );
	}

	/**
	 * Gets field filter options from Gravity Forms and modify them
	 *
	 * @return array|void
	 * @see \GFCommon::get_field_filter_settings()
	 */
	public function get_field_filters() {
		return $this->repository->get_field_filters( $this->form['id'] );
	}

	/**
	 * Adds Entry Approval Status filter option.
	 *
	 * @param array $filters
	 *
	 * @return array
	 * @since 1.4
	 *
	 */
	private static function add_approval_status_filter( array $filters ): array {
		if ( ! class_exists( 'GravityView_Entry_Approval_Status' ) ) {
			return $filters;
		}

		$approval_choices = GravityView_Entry_Approval_Status::get_all();

		$approval_values = [];

		foreach ( $approval_choices as $choice ) {
			$approval_values[] = [
				'text'  => $choice['label'],
				'value' => $choice['value'],
			];
		}

		$filters[] = [
			'text'      => __( 'Entry Approval Status', 'gk-gravitycharts' ),
			'key'       => 'is_approved',
			'operators' => [ 'is', 'isnot' ],
			'values'    => $approval_values,
		];

		return $filters;
	}

	/**
	 * Creates a filter that should return zero results.
	 *
	 * @return array
	 * @since 1.0
	 *
	 */
	public static function get_zero_results_filter(): array {
		return Filter::locked()->to_array();
	}

	/**
	 * Returns translation strings used in the UI.
	 *
	 * @return array $translations Translation strings.
	 * @since 1.0
	 *
	 */
	private function get_translations(): array {
		/**
		 * @filter `gk/query-filters/translations` Modify default translation strings.
		 *
		 * @param array $translations Translation strings.
		 *
		 * @since  1.0
		 *
		 */
		$translations = apply_filters( 'gk/query-filters/translations', [
			'internet_explorer_notice'      => esc_html__(
				'Internet Explorer is not supported. Please upgrade to another browser.',
				'gk-gravitycharts'
			),
			'fields_not_available'          => esc_html__(
				'Form fields are not available. Please try refreshing the page.',
				'gk-gravitycharts'
			),
			'confirm_remove_group'          => esc_html__(
				'This action will delete the entire group of conditions. Do you want to continue?',
				'gk-gravitycharts'
			),
			'toggle_group_mode'             => esc_html__( 'Click to Toggle the Group Mode', 'gk-gravitycharts' ),
			'add_group_label'               => esc_html__( 'Add a New Condition Group', 'gk-gravitycharts' ),
			'add_condition_label'           => esc_html__( 'Add a New Condition', 'gk-gravitycharts' ),
			'has_any'                       => esc_html__( 'has ANY of', 'gk-gravitycharts' ),
			'has_all'                       => esc_html__( 'has ALL of', 'gk-gravitycharts' ),
			'add_condition'                 => esc_html__( 'Add Condition', 'gk-gravitycharts' ),
			'add_created_by_user_condition' => esc_html__( 'Current User Condition', 'gk-gravitycharts' ),
			'condition'                     => esc_html__( 'Condition', 'gk-gravitycharts' ),
			'group'                         => esc_html__( 'Group ', 'gk-gravitycharts' ),
			'condition_join_operator'       => esc_html__( 'Condition Join Operator', 'gk-gravitycharts' ),
			'join_and'                      => esc_html_x( 'and', 'Join using "and" operator', 'gk-gravitycharts' ),
			'join_or'                       => esc_html_x( 'or', 'Join using "or" operator', 'gk-gravitycharts' ),
			'is'                            => esc_html_x( 'is', 'Filter operator (e.g., A is TRUE)', 'gk-gravitycharts' ),
			'isnot'                         => esc_html_x( 'is not', 'Filter operator (e.g., A is not TRUE)', 'gk-gravitycharts' ),
			'>'                             => esc_html_x( 'greater than', 'Filter operator (e.g., A is greater than B)', 'gk-gravitycharts' ),
			'<'                             => esc_html_x( 'less than', 'Filter operator (e.g., A is less than B)', 'gk-gravitycharts' ),
			'contains'                      => esc_html_x( 'contains', 'Filter operator (e.g., AB contains B)', 'gk-gravitycharts' ),
			'ncontains'                     => esc_html_x( 'does not contain', 'Filter operator (e.g., AB contains B)', 'gk-gravitycharts' ),
			'starts_with'                   => esc_html_x( 'starts with', 'Filter operator (e.g., AB starts with A)', 'gk-gravitycharts' ),
			'ends_with'                     => esc_html_x( 'ends with', 'Filter operator (e.g., AB ends with B)', 'gk-gravitycharts' ),
			'isbefore'                      => esc_html_x( 'is before', 'Filter operator (e.g., A is before date B)', 'gk-gravitycharts' ),
			'isafter'                       => esc_html_x( 'is after', 'Filter operator (e.g., A is after date B)', 'gk-gravitycharts' ),
			'ison'                          => esc_html_x( 'is on', 'Filter operator (e.g., A is on date B)', 'gk-gravitycharts' ),
			'isnoton'                       => esc_html_x( 'is not on', 'Filter operator (e.g., A is not on date B)', 'gk-gravitycharts' ),
			'isempty'                       => esc_html_x( 'is empty', 'Filter operator (e.g., A is empty)', 'gk-gravitycharts' ),
			'isnotempty'                    => esc_html_x( 'is not empty', 'Filter operator (e.g., A is not empty)', 'gk-gravitycharts' ),
			'remove_condition'              => esc_html__( 'Remove Condition', 'gk-gravitycharts' ),
			'remove_group'                  => esc_html__( 'Remove Group', 'gk-gravitycharts' ),
			'available_choices'             => esc_html__( 'Return to Field Choices', 'gk-gravitycharts' ),
			'available_choices_label'       => esc_html__(
				'Return to the list of choices defined by the field.',
				'gk-gravitycharts'
			),
			'custom_is_operator_input'      => esc_html__( 'Custom Choice', 'gk-gravitycharts' ),
			'untitled'                      => esc_html__( 'Untitled', 'gk-gravitycharts' ),
			'field_not_available'           => esc_html__(
				'Form field ID #%d is no longer available. Please remove this condition.',
				'gk-gravitycharts'
			),
		] );

		return $translations;
	}

	/**
	 * Enqueues UI scripts.
	 *
	 * @param array $meta Meta data.
	 *
	 * @return void
	 * @since 1.0
	 *
	 */
	public function enqueue_scripts( array $meta = [] ) {
		$script = 'assets/js/query-filters.js';
		$handle = $meta['handle'] ?? self::ASSETS_HANDLE;
		$ver    = $meta['ver'] ?? filemtime( plugin_dir_path( __DIR__ ) . $script );
		$src    = $meta['src'] ?? plugins_url( $script, __DIR__ );
		$deps   = $meta['deps'] ?? [ 'jquery' ];

		wp_enqueue_script( $handle, $src, $deps, $ver );

		$variable_name = $meta['variable_name'] ?? sprintf( 'gkQueryFilters_%s', uniqid() );
		wp_localize_script(
			$handle,
			$variable_name,
			[
				'fields'                    => $meta['fields'] ?? $this->get_field_filters(),
				'conditions'                => $meta['conditions'] ?? [],
				'targetElementSelector'     => $meta['target_element_selector'] ?? '#gk-query-filters',
				'autoscrollElementSelector' => $meta['autoscroll_element_selector'] ?? '',
				'inputElementName'          => $meta['input_element_name'] ?? 'gk-query-filters',
				'translations'              => $meta['translations'] ?? $this->get_translations(),
				'maxNestingLevel'           => (int) ( $meta['max_nesting_level'] ?? 2 ),
			]
		);
	}

	/**
	 * Enqueues UI styles.
	 *
	 * @param array $meta Meta data.
	 *
	 * @return void
	 * @since 1.0
	 *
	 */
	public static function enqueue_styles( array $meta = [] ) {
		$style  = 'assets/css/query-filters.css';
		$handle = $meta['handle'] ?? self::ASSETS_HANDLE;
		$ver    = $meta['ver'] ?? filemtime( plugin_dir_path( __DIR__ ) . $style );
		$src    = $meta['src'] ?? plugins_url( $style, __DIR__ );
		$deps   = $meta['deps'] ?? [];

		wp_enqueue_style( $handle, $src, $deps, $ver );
	}

	/**
	 * Converts GF conditional logic rules to the object used by Query Filters.
	 *
	 * @param array $gf_conditional_logic GF conditional logic object.
	 *
	 * @return array Original or converted object.
	 * @since 1.0
	 *
	 */
	public function convert_gf_conditional_logic( array $gf_conditional_logic ) {
		if ( ! isset( $gf_conditional_logic['actionType'], $gf_conditional_logic['logicType'], $gf_conditional_logic['rules'] ) ) {
			return $gf_conditional_logic;
		}

		$conditions = [];

		foreach ( $gf_conditional_logic['rules'] as $rule ) {
			$conditions[] = [
				'_id'      => wp_generate_password( 4, false ),
				'key'      => $rule['fieldId'] ?? null,
				'operator' => $rule['operator'] ?? null,
				'value'    => $rule['value'] ?? null,
			];
		}

		$query_filters_conditional_logic = [
			'_id'        => wp_generate_password( 4, false ),
			'mode'       => Filter::MODE_AND,
			'conditions' => [],
		];

		if ( 'all' === $gf_conditional_logic['logicType'] ) {
			foreach ( $conditions as $condition ) {
				$query_filters_conditional_logic['conditions'][] = [
					'_id'        => wp_generate_password( 4, false ),
					'mode'       => Filter::MODE_OR,
					'conditions' => [
						$condition,
					],
				];
			}
		} else {
			$query_filters_conditional_logic['conditions'] = [
				[
					'_id'        => wp_generate_password( 4, false ),
					'mode'       => Filter::MODE_OR,
					'conditions' => $conditions,
				],
			];
		}

		return $query_filters_conditional_logic;
	}

	/**
	 * Whether the provided entry meets the filters.
	 *
	 * @param array $entry The entry object.
	 *
	 * @return bool
	 */
	final public function meets_filters( array $entry ): bool {
		if ( ! $this->filters instanceof Filter ) {
			return false;
		}

		return $this->entry_filter_service->meets_filter( $entry, $this->get_filters( false, $entry ) );
	}

	/**
	 * The filter factory.
	 * @return FilterFactory
	 * @since 2.0.0
	 */
	final public function get_filter_factory(): FilterFactory {
		return $this->filter_factory;
	}

	/**
	 * Retrieves the finalized filters.
	 *
	 * @param bool  $as_unprocessed Whether to return the filters unprocessed.
	 * @param array $entry          An optional entry object used as context.
	 *
	 * @return Filter
	 * @since 2.0.0
	 */
	final public function get_filters( bool $as_unprocessed = false, array $entry = [] ): Filter {
		$clone = clone $this->filters;

		if ( ! $as_unprocessed ) {
			foreach ( $this->get_filter_visitors() as $visitor ) {
				if ( $visitor instanceof EntryAwareFilterVisitor ) {
					$visitor->set_entry( $entry );
				}

				$clone->accept( $visitor );
			}
		}

		return $clone;
	}
}
