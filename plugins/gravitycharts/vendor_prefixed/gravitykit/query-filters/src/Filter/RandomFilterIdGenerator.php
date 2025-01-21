<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\QueryFilters\Filter;

/**
 * Filter id generator that returns a random id.
 * @since 2.0.0
 */
final class RandomFilterIdGenerator implements FilterIdGenerator {
	/**
	 * @inheritDoc
	 * @since 2.0.0
	 */
	public function get_id(): string {
		return wp_generate_password( 9, false );
	}
}
