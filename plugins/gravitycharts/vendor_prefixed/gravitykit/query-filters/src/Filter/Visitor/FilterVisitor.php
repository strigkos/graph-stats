<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\QueryFilters\Filter\Visitor;

use GravityKit\GravityCharts\QueryFilters\Filter\Filter;

/**
 * A filter visitor that visits all {@see Filter} entities from the root filter.
 * Note: visitors are applied in pre-order by default {@link https://en.wikipedia.org/wiki/Tree_traversal#Pre-order,_NLR}.
 * @since 2.0.0
 */
interface FilterVisitor {
	/**
	 * Order of operations.
	 * @since 2.0.0
	 */
	public const PRE_ORDER = 'pre-order';
	public const POST_ORDER = 'post-order';

	/**
	 * The callback that gets called on the visitor.
	 *
	 * @param Filter $filter The filter to visit.
	 * @param string $level The visitation level.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function visit_filter( Filter $filter, string $level = '0' );
}
