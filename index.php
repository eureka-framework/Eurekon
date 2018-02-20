<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

use Eureka\Eurekon\Argument;
use Eureka\Eurekon\Style;

require_once 'src/ScriptInterface.php';
require_once 'src/AbstractScript.php';
require_once 'src/Console.php';
require_once 'src/Help.php';
require_once 'src/Argument/ArgumentIterator.php';
require_once 'src/Argument/Argument.php';
require_once 'src/IO/Out.php';
require_once 'src/Style/Color.php';
require_once 'src/Style/Style.php';
require_once 'src/Table/Cell.php';
require_once 'src/Table/Table.php';

try {

    $argv = isset($argv) ? $argv : [];

    $console = new Console($argv);
    $console->before();
    $console->run();
    $console->after();
    $console->terminate();

} catch (\Exception $exception) {

    $style = new Style\Style('Exception: ' . $exception->getMessage());
    echo $style->colorBackground(Style\Color::RED);

    if (Argument\Argument::getInstance()->has('debug')) {
        echo 'Code: ' . $exception->getCode() . PHP_EOL;
        echo 'Details: ' . $exception->getTraceAsString() . PHP_EOL;
    }
}
