<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Display progression with percent & time.
 * 1.0.0: Initial Class
 * 2.1.0: Refactor & PSR-2 Norms
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class Progress
{

    /**
     *
     * @var integer $currentIndex Current index
     */
    protected $currentIndex = 0;

    /**
     *
     * @var float $percentStep Percent step
     */
    protected $percentStep = 0.0;

    /**
     *
     * @var integer $globalName Name for progress
     */
    protected $globalName = '';

    /**
     *
     * @var integer $initialTime Initial time
     */
    protected $initialTime = 0;

    /**
     *
     * @var integer $elapsedTime Time elapsed
     */
    protected $elapsedTime = 0;

    /**
     * Class constructor
     *
     * @param string $globalName Name for progress
     * @param integer $nbElements Number total of elements.
     * @return Progress class instance
     */
    public function __construct($globalName, $nbElements)
    {
        if ($nbElements === 0) {
            $nbElements = 1;
        }

        $this->percentStep = 100 / $nbElements;
        $style = new Style($globalName);
        $this->globalName = $style->highlight('fg')->get();
    }

    /**
     * Display progress
     *
     * @param string $elementName Processing element name (for display)
     * @param integer $increment Incremental step.
     * @return void
     */
    public function display($elementName, $increment = 1)
    {
        if ($this->initialTime == 0) {
            $this->initialTime = time();
        } else {
            $this->initialTime = time() - $this->initialTime;
        }

        $this->currentIndex += $increment;

        $percent = floor($this->currentIndex * $this->percentStep);
        $percent_exact = $this->currentIndex * $this->percentStep;

        $style = new Style(str_pad($percent, 3, ' ', STR_PAD_LEFT) . '%');
        $percent_txt = $style->color('fg', Style::COLOR_GREEN)
            ->highlight('fg')
            ->get();

        $elapsed_time_txt = '';
        $time_to_go_txt = '';
        if ($this->elapsedTime != 0) {
            $elapsed_time_txt = $style->reset()
                ->setText(str_pad($this->elapsedTime, 5, ' ', STR_PAD_LEFT) . ' sec. elapsed')
                ->color('fg', Style::COLOR_GREEN)
                ->highlight('fg')
                ->get();

            $time_to_go = round((($this->elapsedTime * 100) / $percent_exact), 0) - $this->elapsedTime;
            $time_to_go_txt = $style->reset()
                ->setText(str_pad($time_to_go, 5, ' ', STR_PAD_LEFT) . ' sec. to go')
                ->color('fg', Style::COLOR_GREEN)
                ->highlight('fg')
                ->get();
        }

        Out::std('  > ' . $this->globalName . ' - [' . $percent_txt . ']' . ' [' . $elapsed_time_txt . ']' . ' [' . $time_to_go_txt . '] - ' . str_pad($elementName, 200), "\r");

        if ($percent >= 100) {
            Out::std('  > ' . $this->globalName . ' - [' . $percent_txt . '] - ' . str_pad('done !' . 200));
        }
    }
}