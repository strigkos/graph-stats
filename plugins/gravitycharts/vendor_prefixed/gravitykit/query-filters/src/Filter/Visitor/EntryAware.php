<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\QueryFilters\Filter\Visitor;

/**
 * A trait that satisfies {@see EntryAwareFilterVisitor}.
 *
 * @since 2.1.2
 */
trait EntryAware {
	/**
	 * The entry object.
	 *
	 * @since 2.1.2
	 *
	 * @var array
	 */
	protected $entry = [];

	/**
	 * Records the entry object.
	 *
	 * @since 2.1.2
	 *
	 * @param array $entry The entry object.
	 */
	public function set_entry( array $entry ): void {
		$this->entry = $entry;
	}
}
