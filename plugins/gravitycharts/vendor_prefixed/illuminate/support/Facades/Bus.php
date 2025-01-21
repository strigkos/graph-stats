<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Support\Facades;

use GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Support\Testing\Fakes\BusFake;
use GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Contracts\Bus\Dispatcher as BusDispatcherContract;

/**
 * @see \GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Contracts\Bus\Dispatcher
 */
class Bus extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return void
     */
    public static function fake()
    {
        static::swap(new BusFake);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BusDispatcherContract::class;
    }
}
