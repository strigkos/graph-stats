<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Contracts\Bus;

interface QueueingDispatcher extends Dispatcher
{
    /**
     * Dispatch a command to its appropriate handler behind a queue.
     *
     * @param  mixed  $command
     * @return mixed
     */
    public function dispatchToQueue($command);
}
