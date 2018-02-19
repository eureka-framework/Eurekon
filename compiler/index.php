<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/*
require_once 'Exception/AlreadyLoggedException.php';
require_once 'Argument/Argument.php';
require_once 'Argument/ArgumentIterator.php';
require_once 'IO/Out.php';
require_once 'Progress/Progress.php';
require_once 'Style/Style.php';
require_once 'Style/Color.php';
require_once 'Table/Cell.php';
require_once 'Table/Table.php';
require_once 'ScriptInterface.php';
require_once 'AbstractScript.php';
require_once 'Help.php';
require_once 'Eurekon.php';
*/

/*
$console = (new \Eureka\Eurekon\Console\Symfony\ConsoleBridge($argv, false))
    ->setBaseNamespace('\Eureka\Eurekon')
    ->setKernel($kernel);

//~ Add logger for exception caught by the console.
$console->setLogger($console->getContainer()->get('logger_unified'));

// Add the container for Legacy usage (to be removed when all code in Legacy will be removed)
\static_container::setContainer($console->getContainer());

$console->before();
$console->run();
$console->after();
$console->terminate();
*/

try {

    $argv = isset($argv) ? $argv : array();

    $console = new Console($argv);
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
