<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

use Eureka\Eurekon\Style\Style;

class Table
{
    /** @var Row[] $rows */
    private $rows = [];

    /** @var Column[]  */
    private $columns = [];

    /**
     * Table constructor.
     *
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
        $this->rows = [];

        $this->createHeader();
    }

    /**
     * @param int $index
     * @return Column
     */
    public function getColumn(int $index): Column
    {
        return $this->columns[$index];
    }

    /**
     * @return $this
     */
    public function createHeader(): self
    {
        $cells = [];

        $this->addBar();
        foreach ($this->columns as $column) {
            $cells[] = new Cell($column->getName(), $column->getSize(), $column->getAlign());
        }

        $this->add(new Row($cells, true));
        $this->addBar();

        return $this;
    }

    /**
     * @param  Row $row
     * @return Table
     */
    public function add(Row $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param array $data
     * @param bool $isHeader
     * @param Style|null $style
     * @return Table
     */
    public function addRow(array $data, bool $isHeader = false, Style $style = null): self
    {
        $cells = [];

        if ($isHeader) {
            $this->addBar();
        }

        foreach ($data as $index => $value) {
            $column = $this->getColumn($index);
            $cells[] = new Cell($value, $column->getSize(), $column->getAlign());
        }

        $this->rows[] = new Row($cells, $isHeader, false, $style);

        if ($isHeader) {
            $this->addBar();
        }

        return $this;
    }

    /**
     * @param array $data
     * @param bool $isHeader
     * @return Table
     */
    public function addRowSpan(array $data, bool $isHeader = false): self
    {
        if ($isHeader) {
            $this->addBar();
        }

        $size = count($this->columns) - 1;
        foreach ($this->columns as $column) {
            $size += $column->getSize();
        }
        $this->rows[] = new Row([new Cell(implode(' - ', $data), $size, Cell::ALIGN_CENTER)], $isHeader);


        if ($isHeader) {
            $this->addBar();
        }

        return $this;
    }

    /**
     * @return Table
     */
    public function addBar(): self
    {
        $cells = [];
        foreach ($this->columns as $column) {
            $cells[] = new Cell(str_pad('', $column->getSize(), '-'), $column->getSize(), false, false);
        }
        $this->rows[] = new Row($cells, false, true);

        return $this;
    }

    /**
     * @return Table
     */
    public function display(): self
    {
        $this->addBar();

        foreach ($this->rows as $row) {
            echo (string) $row;
        }

        return $this;
    }
}
