<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Wrapper for display on standart & error channel.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class Out
{

    /**
     * Display message on error output
     *
     * @param string $message
     * @param string $endLine
     * @return void
     */
    public static function err($message, $endLine = PHP_EOL)
    {
        if (! Argument::getInstance()->has('quiet')) {
            fwrite(STDERR, $message . $endLine);
        }
    }

    /**
     * Display message on standart output
     *
     * @param string $message
     * @param string $endLine
     * @return void
     */
    public static function std($message, $endLine = PHP_EOL)
    {
        if (! Argument::getInstance()->has('quiet')) {
            fwrite(STDOUT, $message . $endLine);
        }
    }
}
