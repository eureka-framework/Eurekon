<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

/**
 * Class Cell
 *
 * @author Romain Cottard
 */
class Cell
{
    const ALIGN_CENTER = STR_PAD_BOTH;
    const ALIGN_LEFT   = STR_PAD_RIGHT;
    const ALIGN_RIGHT  = STR_PAD_LEFT;

    /** @var string  */
    private $content = '';

    /** @var int  */
    private $size = 10;

    /** @var int  */
    private $align = Cell::ALIGN_CENTER;

    /** @var bool $paddingSpace */
    private $paddingSpace;

    /**
     * Cell constructor.
     *
     * @param string $content
     * @param int $size
     * @param int $align
     * @param bool $paddingSpace
     */
    public function __construct(string $content, int $size = 13, int $align = Cell::ALIGN_CENTER, bool $paddingSpace = true)
    {
        $this->content      = $content;
        $this->size         = $size;
        $this->align        = $align;
        $this->paddingSpace = $paddingSpace;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $content = $this->paddingSpace ? ' ' . $this->content . ' ' : $this->content;

        return (string) str_pad($content, $this->size, ' ', $this->align);

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
