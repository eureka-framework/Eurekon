<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Class console_argumentiterator used in console_argument::parse()
 * 1.0.0: Initial class
 * 2.1.0: PSR-2
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class ArgumentIterator implements \Iterator
{

    /**
     *
     * @var integer $index Current index
     */
    protected $index = 0;

    /**
     *
     * @var array $arguments List of arguments
     */
    protected $arguments = array();

    /**
     * Class constructor
     *
     * @param array $args array of arguments.
     * @return ArgumentIterator Instance of current class.
     */
    public function __construct(Array $args)
    {
        $this->index = 0;
        $this->arguments = $args;
    }

    /**
     * Overriden Iterator method.
     * Return current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->arguments[$this->index];
    }

    /**
     * Overriden Iterator method.
     * Return key element.
     *
     * @return integer Current index
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Overriden Iterator method.
     * Increase internal index
     *
     * @return void
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * Overriden Iterator method.
     * Decrease internal index
     *
     * @return void
     */
    public function prev()
    {
        --$this->index;
    }

    /**
     * Overriden Iterator method.
     * Reset internal index.
     *
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Overriden Iterator method.
     * Check if iterator current element is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->arguments[$this->index]);
    }
}