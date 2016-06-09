<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Console Abstraction class.
 * Must be parent class for every console script class.
 *
 * @author  Romain Cottard
 * @version 2.1.0
 */
abstract class Console implements ConsoleInterface
{

    /**
     * Set to true to set class as an executable script
     *
     * @var boolean $executable
     */
    protected $executable = false;

    /**
     * Console script description.
     *
     * @var boolean $executable
     */
    protected $description = 'Script description for Help !';

    /**
     * Return executable status about class.
     *
     * @return boolean
     */
    public function executable()
    {
        return $this->executable;
    }

    /**
     * Return console script description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Method called before run() method.
     * Can be overriden.
     *
     * @return void
     */
    public function before()
    {}

    /**
     * Method called after run() method.
     * Can be overriden.
     *
     * @return void
     */
    public function after()
    {}

    /**
     * Help method.
     * Must be overriden.
     *
     * @return void
     */
    abstract public function help();

    /**
     * Run method.
     * Must be overriden.
     *
     * @return void
     */
    abstract public function run();
}