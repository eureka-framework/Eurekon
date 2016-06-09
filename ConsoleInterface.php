<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Interface for console script launched by Eurekon console.
 *
 * @author  Romain Cottard
 * @version 3.0.0
 */
interface ConsoleInterface
{
    /**
     * Check if script is executable.
     *
     * @return bool
     */
    public function executable();

    /**
     * Display help
     *
     * @return void
     */
    public function help();

    /**
     * Main method for console script.
     *
     * @return void
     */
    public function run();

    /**
     * Method called before run() method.
     *
     * @return void
     */
    public function before();

    /**
     * Method called after run() method.
     *
     * @return void
     */
    public function after();
}