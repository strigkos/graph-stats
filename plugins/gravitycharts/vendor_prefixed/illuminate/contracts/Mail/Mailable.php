<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Contracts\Mail;

use GravityKit\GravityCharts\Foundation\ThirdParty\Illuminate\Contracts\Queue\Factory as Queue;

interface Mailable
{
    /**
     * Send the message using the given mailer.
     *
     * @param  Mailer  $mailer
     * @return void
     */
    public function send(Mailer $mailer);

    /**
     * Queue the given message.
     *
     * @param  Queue  $queue
     * @return mixed
     */
    public function queue(Queue $queue);

    /**
     * Deliver the queued message after the given delay.
     *
     * @param  \DateTime|int  $delay
     * @param  Queue  $queue
     * @return mixed
     */
    public function later($delay, Queue $queue);
}
