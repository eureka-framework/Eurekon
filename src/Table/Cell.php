<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

use Eureka\Eurekon\Style\Style;

/**
 * Cell entity class for Table class.
 *
 * @author Romain Cottard
 */
class Cell
{
    /** @var string $text */
    protected $text = '';

    /** @var Style $style */
    protected $style = null;

    /**
     * Class constructor
     *
     * @param string $text
     * @param Style  $style
     */
    public function __construct($text, Style $style = null)
    {
        $this->setText($text);
        $this->setStyle($style);
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param  string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * Get Style
     *
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set style.
     *
     * @param  Style $style
     * @return $this
     */
    public function setStyle(Style $style = null)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Render cell
     *
     * @param  int $size
     * @return string
     */
    public function render($size = 10)
    {
        return (string) $this->style->pad($size)->setText(substr($this->text, 0, $size));
    }

    /**
     * Render cell.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
