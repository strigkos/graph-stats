<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\QueryFilters\Clock;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Clock that uses the system time.
 * @since 2.0.0
 */
final class SystemClock implements Clock
{
    /**
     * @inheritDoc
     * @since 2.0.0
     */
    public function now() : DateTimeInterface
    {
        return new DateTimeImmutable();
    }
}
