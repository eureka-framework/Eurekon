<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Table;

use Eureka\Eurekon\Style\Style;

class Row
{
    /** @var Cell[] $cells */
    private $cells = [];

    /** @var bool  */
    private $isBar = false;

    /** @var bool  */
    private $isHeader = false;

    /** @var Style $style */
    private $style;

    /**
     * Row constructor.
     *
     * @param array $cells
     * @param bool $isHeader
     * @param bool $isBar
     * @param Style|null $style
     */
    public function __construct(array $cells, bool $isHeader = false, bool $isBar = false, Style $style = null)
    {
        $this->cells    = $cells;
        $this->isBar    = $isBar;
        $this->isHeader = $isHeader;
        $this->style    = $style;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $cells = [];

        foreach ($this->cells as $cell) {
            $cells[] = (string) $cell;
        }

        $glue = $this->isBar ? '+' : '|';

        $line = '|' . implode($glue, $cells) . '|';
        if ($this->style instanceof Style) {
            $line = (string) $this->style->setText($line);
        }

        return $line . PHP_EOL;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
