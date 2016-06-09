<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

require_once 'Argument.php';
require_once 'ArgumentIterator.php';
require_once 'Eurekon.php';
require_once 'Help.php';
require_once 'Out.php';
require_once 'Progress.php';
require_once 'Style.php';

try {

    $argv = isset($argv) ? $argv : array();

    $console = new Eurekon($argv);
    $console->before();
    $console->run();
    $console->after();

} catch (\Exception $exception) {

    $style = new Style('Exception: ' . $exception->getMessage());
    echo $style->color('bg', Style::COLOR_RED);

    if (Argument::getInstance()->has('debug')) {
        echo 'Code: ' . $exception->getCode() . PHP_EOL;
        echo 'Details: ' . $exception->getTraceAsString() . PHP_EOL;
    }
}