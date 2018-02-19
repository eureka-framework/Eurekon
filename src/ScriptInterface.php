<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Interface for console script launched by Eurekon console.
 *
 * @author  Romain Cottard
 */
interface ScriptInterface
{
    /**
     * Check if script is executable.
     *
     * @return bool
     */
    public function executable();

    /**
     * Get description of the script
     *
     * @return string
     */
    public function getDescription();

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
