<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

require_once __DIR__ . '/../src/Argument/Argument.php';
require_once __DIR__ . '/../src/Argument/ArgumentIterator.php';
require_once __DIR__ . '/../src/Help.php';
require_once __DIR__ . '/../src/Style/Style.php';
require_once __DIR__ . '/Utils/DirectoryFilterIterator.php';

$argument = Argument::getInstance();
$argument->parse(isset($argv) ? $argv : array());

$style = new Style('*** Eurekon - Compiler ***');
echo $style->color('fg', Style::COLOR_GREEN)->highlight('fg')->get() . PHP_EOL;
echo $style->reset()->setText(' Build phar executive installer for Eureka System.')->color('fg', Style::COLOR_BLACK)->highlight('fg')->get() . PHP_EOL;

if ($argument->has('help', 'h')) {

    $help = new Help(basename(__FILE__));
    $help->addArgument('d', 'destination', 'Destination for eureka.phar installer archive', true, true);
    $help->display();
    exit(0);
}

try {

    $path = $argument->get('d', 'destination', '/tmp/');
    $name = 'eurekon.phar';
    $from = realpath('.') . '/';
    $path = rtrim($path, '/') . '/';
    $phar = $path . $name;

    //~ Start compilation
    echo $style->reset()->setText(' > Starting compilation...')->color('fg', Style::COLOR_WHITE)->highlight('fg')->get() . PHP_EOL;

    $phar = new \Phar($phar, 0, $name);
    //$phar->compressFiles(\Phar::NONE);

    DirectoryFilterIterator::exclude(array('compiler.php', 'eurekon.phar', 'Tests', 'DirectoryFilterIterator.php', 'composer.json'));
    $dirIterator  = new \RecursiveDirectoryIterator($from, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS);
    $fDirIterator = new DirectoryFilterIterator($dirIterator);
    $iIterator    = new \RecursiveIteratorIterator($fDirIterator);
    $phar->buildFromIterator($iIterator, $from);

    echo $style->setText(' > Compilation done !') . PHP_EOL;

    //~ Set eureka.phar executable
    echo $style->setText(' > Make installer executable...') . PHP_EOL;

    if (false === chmod($path . $name, 0755)) {
        throw new \RuntimeException('Unable to set executable rights on "' . $path . $name . '" !');
    }
    echo $style->setText(' > done !') . PHP_EOL;

    echo PHP_EOL;

} catch (\Exception $exception) {
    if ($argument->has('debug')) {
        echo $exception->getTraceAsString(), PHP_EOL;
    }

    throw $exception;
}
