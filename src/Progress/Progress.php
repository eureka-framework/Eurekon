<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Progress;

use Eureka\Eurekon\Argument;
use Eureka\Eurekon\Style\Style;
use Eureka\Eurekon\IO\Out;


/**
 * Display progression with percent & time.
 *
 * @author Romain Cottard
 */
class Progress
{
    /** @var int TYPE_BAR */
    const TYPE_BAR = 1;

    /** @var int TYPE_PERCENT */
    const TYPE_PERCENT = 2;

    /** @var int TYPE_TIME */
    const TYPE_TIME = 3;

    /** @var array $typesAllowed */
    protected static $typesAllowed = [
        self::TYPE_BAR => true,
        self::TYPE_PERCENT => true,
        self::TYPE_TIME => true,
    ];

    /** @var int $type Type of display. */
    protected $typeDisplay = self::TYPE_BAR;

    /** @var int $currentIndex Current index */
    protected $currentIndex = 0;

    /** @var float $percentStep Percent step */
    protected $percentStep = 0.0;

    /** @var int $globalName Name for progress */
    protected $globalName = '';

    /** @var int $initialTime Initial time */
    protected $initialTime = 0;

    /** @var int $elapsedTime Time elapsed */
    protected $elapsedTime = 0;

    /** @var bool $active Whether progress bars are activated or not */
    protected $active = false;

    /** @var bool $completed Whether the progress bar is complete */
    protected $completed = false;

    /** @var bool $capped Whether the progress should display at most 100% or not */
    protected $capped = false;

    /**
     * Class constructor
     *
     * @param  string $globalName Name for progress
     * @param  int $nbElements Number total of elements.
     * @param  bool $capped Whether the progress should display at most 100% or not
     */
    public function __construct($globalName, $nbElements, $capped = false)
    {
        if ($nbElements === 0) {
            $nbElements = 1;
        }

        $this->percentStep = 100 / $nbElements;
        $this->globalName  = (string) (new Style($globalName))->highlightForeground();
        $this->active      = Argument::getInstance()->has('progress');
        $this->capped      = (bool) $capped;
    }

    /**
     * Set type display.
     *
     * @param  int $typeDisplay
     * @return self
     */
    public function setTypeDisplay($typeDisplay)
    {
        $typeDisplay = (int) $typeDisplay;

        if (!isset(self::$typesAllowed[$typeDisplay])) {
            throw new \DomainException('Display type is not allowed. (type: ' . $typeDisplay . ')');
        }

        $this->typeDisplay = $typeDisplay;

        return $this;
    }

    /**
     * Display progress
     *
     * @param string $label
     * @param int $increment
     * @return self
     */
    public function display($label, $increment = 1)
    {
        if (!$this->active || $this->completed) {
            return $this;
        }

        $this->currentIndex += $increment;

        $percent      = floor($this->currentIndex * $this->percentStep);
        $percent      = $this->capped ? min(100, $percent) : $percent;
        $percentExact = $this->currentIndex * $this->percentStep;

        switch ($this->typeDisplay) {
            case self::TYPE_BAR:
                $this->displayBar($label, $percent);
                break;
            case self::TYPE_PERCENT:
                $this->displayPercent($label, $percentExact);
                break;
                break;
            case self::TYPE_TIME:
                $this->displayTime($label, $percent, $percentExact);
                break;
        }

        return $this;
    }

    /**
     * Display complete progress.
     *
     * @param  string $label
     * @return void
     */
    public function displayComplete($label)
    {
        $this->currentIndex = (100 / $this->percentStep);
        $this->display($label, 1);
        Out::std('');
    }

    /**
     * Display progress as bar.
     *
     * @param  string $label
     * @param  int $percent
     * @return void
     */
    private function displayBar($label, $percent)
    {
        $bar = ' [' . str_pad(str_repeat('#', floor($percent / 2)), 50, ' ') . '] ' . str_pad($label, 50, ' ');
        Out::std((string) (new Style())->color('fg', Style::COLOR_GREEN)->bold()->setText($bar), "\r");
    }

    /**
     * Display progress as percent.
     *
     * @param  string $label
     * @param  float $percentExact
     * @return void
     */
    private function displayPercent($label, $percentExact)
    {
        $bar = ' [' . str_pad(number_format($percentExact, 2, '.', ' '), 6, ' ', STR_PAD_LEFT) . '%] ' . str_pad($label, 50, ' ');
        Out::std((string) (new Style())->color('fg', Style::COLOR_GREEN)->bold()->setText($bar), "\r");
    }

    /**
     * Display progress
     *
     * @param  string $label Processing element name (for display)
     * @param  int $percent
     * @param  float $percentExact
     * @return void
     */
    private function displayTime($label, $percent, $percentExact)
    {
        if ($this->initialTime == 0) {
            $this->initialTime = time();
        } else {
            $this->elapsedTime = time() - $this->initialTime;
        }

        $style       = new Style(str_pad($percent, 3, ' ', STR_PAD_LEFT) . '%');
        $percentText = (string) $style->color('fg', Style::COLOR_GREEN)->highlight('fg');

        $timeDoneText = '';
        $timeLeftText = '';
        if ($this->elapsedTime != 0) {
            $timeDoneText = (string) $style->reset()
                ->setText(str_pad($this->elapsedTime, 5, ' ', STR_PAD_LEFT))
                ->color('fg', Style::COLOR_GREEN)
                ->highlight('fg');
            $timeLeft     = round((($this->elapsedTime * 100) / $percentExact), 0) - $this->elapsedTime;
            $timeLeftText = (string) $style->reset()
                ->setText(str_pad($timeLeft, 5, ' ', STR_PAD_LEFT))
                ->color('fg', Style::COLOR_GREEN)
                ->highlight('fg');
        }

        Out::std('  > ' . $this->globalName . ' - [' . $percentText . ']' . ' [' . $timeDoneText . '/' . $timeLeftText . ' sec] - ' . str_pad($label, 80), "\r");

        if ($percent >= 100) {
            $this->completed = true;
            Out::std('  > ' . $this->globalName . ' - [' . $percentText . '] - ' . str_pad('done !', 80));
        }
    }

    /**
     * Interrupt progress
     */
    public function interrupt()
    {
        if (!$this->active || $this->completed) {
            return;
        }

        $percent     = floor($this->currentIndex * $this->percentStep);
        $style       = new Style(str_pad($percent, 3, ' ', STR_PAD_LEFT) . '%');
        $percentText = (string) $style->color('fg', Style::COLOR_GREEN)->highlight('fg');

        $this->completed = true;
        Out::std('  > ' . $this->globalName . ' - [' . $percentText . '] - ' . str_pad('stopped !', 80));
    }
}
