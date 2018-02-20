<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

use Eureka\Eurekon\Argument\Argument;
use Eureka\Eurekon\Style\Style;
use Eureka\Eurekon\Style\Color;

$root = realpath(__DIR__ . '/../');

require_once $root . '/src/Argument/Argument.php';
require_once $root . '/src/Argument/ArgumentIterator.php';
require_once $root . '/src/Help.php';
require_once $root . '/src/IO/Out.php';
require_once $root . '/src/Style/Style.php';
require_once $root . '/src/Style/Color.php';
require_once $root . '/compiler/Utils/DirectoryFilterIterator.php';

$argument = Argument::getInstance();
$argument->parse(isset($argv) ? $argv : array());

$style = new Style('*** Eurekon - Compiler ***');
echo $style->color('fg', Color::GREEN)->highlight('fg')->get() . PHP_EOL;
echo $style->reset()->setText(' Build phar executive installer for Eureka System.')->color('fg', Color::BLACK)->highlight('fg')->get() . PHP_EOL;

if ($argument->has('help', 'h')) {

    $help = new Help(basename(__FILE__));
    $help->addArgument('d', 'destination', 'Destination for eureka.phar installer archive', true, true);
    $help->display();
    exit(0);
}

try {

    $excluded = [
        '.idea',
        'Utils',
        'tests',
        'compiler.php',
        'composer.json',
        '.gitignore',
    ];

    $path = $argument->get('d', 'destination', '/tmp/');
    $name = 'eurekon.phar';
    $from = $root;
    $path = rtrim($path, '/') . '/';
    $phar = $path . $name;

    //~ Start compilation
    echo $style->reset()->setText(' > Starting compilation...')->colorForeground(Color::WHITE)->highlightForeground()->get() . PHP_EOL;

    $phar = new \Phar($phar, 0, $name);
    //$phar->compressFiles(\Phar::NONE);

    DirectoryFilterIterator::exclude($excluded);
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
