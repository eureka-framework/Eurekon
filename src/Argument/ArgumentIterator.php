<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Argument;

/**
 * Class ArgumentIterator used in Argument::parse
 *
 * @author Romain Cottard
 */
class ArgumentIterator implements \Iterator
{
    /** @var int $index Current index */
    protected $index = 0;

    /** @var array $arguments List of arguments */
    protected $arguments = array();

    /**
     * Class constructor
     *
     * @param array $args array of arguments.
     */
    public function __construct(array $args)
    {
        $this->index     = 0;
        $this->arguments = $args;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->arguments[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function prev()
    {
        --$this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->arguments[$this->index]);
    }
}
