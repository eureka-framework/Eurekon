<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

use Eureka\Eurekon\Style\Style;
use Eureka\Eurekon\Style\Color;
use Eureka\Eurekon\IO\Out;

/**
 * Render table in console mode.
 *
 * @author Romain Cottard
 */
class Table
{
    /** @var int[] $columnsSize */
    protected $columnsSize = [];

    /** @var string[][] $lines Columns contents */
    protected $lines = [];

    /**
     * Class constructor
     *
     * @param  int[] List of size by columns.
     */
    public function __construct($columns = [])
    {
        if (!empty($columns)) {
            foreach ($columns as $size) {
                $this->addColumn((int) $size);
            }
        }
    }

    /**
     * Add new column to the array
     *
     * @param  int $size
     * @return $this
     */
    public function addColumn($size)
    {
        $this->columnsSize[] = $size;

        return $this;
    }

    /**
     * Line content.
     *
     * @param  Cell[] $line
     * @param  Style  $lineStyle
     * @return $this
     */
    public function addLine($line, Style $lineStyle = null)
    {
        if (!($lineStyle instanceof Style)) {
            $lineStyle = (new Style())->colorForeground(Color::WHITE)->bold();
        }

        foreach ($this->columnsSize as $index => $size) {
            $cell = $line[$index];

            //~ Override cell content if necessary.
            if (!($cell->getStyle() instanceof Style)) {
                $cell->setStyle(clone $lineStyle);
            }

            $line[$index] = $cell->render($size);
        }
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Line content.
     *
     * @param  Cell[] $line
     * @param  bool   $hasLineBar
     * @param  Style  $lineStyle
     * @return $this
     */
    public function addLineHeader($line, $hasLineBar = true, Style $lineStyle = null)
    {
        if ($hasLineBar) {
            $this->addLineBar();
        }

        if (!($lineStyle instanceof Style)) {
            $lineStyle = (new Style())->colorForeground(Color::GREEN)->bold();
        }

        foreach ($this->columnsSize as $index => $size) {
            $cell = $line[$index];

            //~ Override cell content if necessary.
            if (!($cell->getStyle() instanceof Style)) {
                $cell->setStyle(clone $lineStyle);
            }

            $line[$index] = $cell->render($size);
        }

        $this->lines[] = $line;

        if ($hasLineBar) {
            $this->addLineBar();
        }

        return $this;
    }

    /**
     * Line content.
     *
     * @return $this
     */
    public function addLineBar()
    {
        $line = [];
        foreach ($this->columnsSize as $index => $size) {
            $line[$index] = new Cell(str_pad('-', $size, '-'));
        }

        return $this->addLine($line);
    }

    /**
     * Line content.
     *
     * @return $this
     */
    public function addEmptyLine()
    {
        $line = [];
        for ($index = 0, $max = count($this->columnsSize); $index < $max; $index++) {
            $line[$index] = new Cell(' ');
        }

        return $this->addLine($line);
    }

    /**
     * Render
     *
     * @return $this
     */
    public function render()
    {
        $this->addLineBar();

        foreach ($this->lines as $line) {
            Out::std('|', '');
            foreach ($line as $cell) {
                Out::std($cell . '|', '');
            }

            Out::std('');
        }

        return $this;
    }
}
