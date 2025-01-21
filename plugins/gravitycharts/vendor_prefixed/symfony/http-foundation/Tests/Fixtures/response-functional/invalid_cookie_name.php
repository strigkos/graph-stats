<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

use GravityKit\GravityCharts\Symfony\Component\HttpFoundation\Cookie;

$r = require __DIR__.'/common.inc';

try {
    $r->headers->setCookie(new Cookie('Hello + world', 'hodor', 0, null, null, null, false, true));
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
}
