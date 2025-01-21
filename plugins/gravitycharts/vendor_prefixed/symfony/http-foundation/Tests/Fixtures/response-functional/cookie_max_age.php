<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

use GravityKit\GravityCharts\Symfony\Component\HttpFoundation\Cookie;

$r = require __DIR__.'/common.inc';

$r->headers->setCookie(new Cookie('foo', 'bar', 253402310800, '', null, false, false));
$r->sendHeaders();

setcookie('foo2', 'bar', 253402310800, '/');
