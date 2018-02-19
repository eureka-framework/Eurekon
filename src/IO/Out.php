<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\IO;

use Eureka\Eurekon\Argument\Argument;

/**
 * Wrapper for display on standard & error channel.
 *
 * @author Romain Cottard
 */
class Out
{
    /** @var bool $allowBuffering Enables/disables output buffering */
    private static $allowBuffering = false;

    /**
     * Indicates if output buffering should be enabled or not
     *
     * @param bool $allow
     */
    public static function allowBuffering($allow)
    {
        self::$allowBuffering = $allow;
    }

    /**
     * Display message on error output
     *
     * @param  string|\Eureka\Eurekon\Style\Style $message
     * @param  string $endLine
     * @return void
     */
    public static function err($message, $endLine = PHP_EOL)
    {
        if (!Argument::getInstance()->has('quiet')) {
            if (self::$allowBuffering) {
                echo $message . $endLine;
            } else {
                fwrite(STDERR, (string) $message . $endLine);
            }
        }
    }

    /**
     * Display message on standard output
     *
     * @param string|\Eureka\Eurekon\Style\Style $message
     * @param string $endLine
     * @return void
     */
    public static function std($message, $endLine = PHP_EOL)
    {
        if (!Argument::getInstance()->has('quiet')) {
            if (self::$allowBuffering) {
                echo $message . $endLine;
            } else {
                fwrite(STDOUT, (string) $message . $endLine);
            }
        }
    }
}
