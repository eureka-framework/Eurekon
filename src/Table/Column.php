<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

/**
 * Class Column
 *
 * @author Romain Cottard
 */
class Column
{
    /** @var string $name */
    private $name = '';

    /** @var int $size */
    private $size = 10;

    /** @var int $align */
    private $align = Cell::ALIGN_CENTER;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param int $size
     * @param int $align
     */
    public function __construct(string $name, int $size = 10, int $align = Cell::ALIGN_CENTER)
    {
        $this->name  = $name;
        $this->size  = $size;
        $this->align = $align;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAlign(): int
    {
        return $this->align;
    }
}
