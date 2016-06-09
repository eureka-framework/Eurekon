<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Add color to text for unix display.
 * 1.0.0: Initial class
 * 2.0.0: Static to non static methods
 * 2.1.0: PSR-2
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class Style
{

    /**
     * Index color character for black color.
     *
     * @var string COLOR_BLACK
     */
    const COLOR_BLACK = '0';

    /**
     * Index color character for red color.
     *
     * @var string COLOR_RED
     */
    const COLOR_RED = '1';

    /**
     * Index color character for green color.
     *
     * @var string COLOR_GREEN
     */
    const COLOR_GREEN = '2';

    /**
     * Index color character for yellow color.
     *
     * @var string COLOR_YELLOW
     */
    const COLOR_YELLOW = '3';

    /**
     * Index color character for blue color.
     *
     * @var string COLOR_BLUE
     */
    const COLOR_BLUE = '4';

    /**
     * Index color character for purple color.
     *
     * @var string COLOR_PURPLE
     */
    const COLOR_PURPLE = '5';

    /**
     * Index color character for cyan color.
     *
     * @var string COLOR_CYAN
     */
    const COLOR_CYAN = '6';

    /**
     * Index color character for white color.
     *
     * @var string COLOR_WHITE
     */
    const COLOR_WHITE = '7';

    /**
     * Index color character for no decoration.
     *
     * @var string DECORATION_NONE
     */
    const DECORATION_NONE = '0;';

    /**
     * Index color character for text bold decoration.
     *
     * @var string DECORATION_BOLD
     */
    const DECORATION_BOLD = '1;';

    /**
     * Index color character text underline decoration.
     *
     * @var string DECORATION_UNDERLINE
     */
    const DECORATION_UNDERLINE = '4;';

    /**
     * Index color character normal foreground.
     *
     * @var string REGULAR_FOREGROUND
     */
    const REGULAR_FOREGROUND = '3';

    /**
     * Index color character normal background.
     *
     * @var string REGULAR_BACKGROUND
     */
    const REGULAR_BACKGROUND = '4';

    /**
     * Index color character highlight foreground.
     *
     * @var string HIGH_FOREGROUND
     */
    const HIGH_FOREGROUND = '9';

    /**
     * Index color character highlight background.
     *
     * @var string HIGH_BACKGROUND
     */
    const HIGH_BACKGROUND = '10';

    /**
     * First characters for color text.
     * (internal constant)
     *
     * @var string BEGIN
     */
    const BEGIN = "\033[";

    /**
     * End characters for color text.
     * (internal constant)
     *
     * @var string BEGIN
     */
    const END = 'm';

    /**
     * Last characters for stoping color text.
     * (internal constant)
     *
     * @var string DESACTIVATE
     */
    const DESACTIVATE = "\033[0m";

    /**
     * Foreground color character
     *
     * @var string $foregroundColor
     */
    protected $foregroundColor = Style::COLOR_WHITE;

    /**
     * Foreground color character
     *
     * @var string $foregroundColor
     */
    protected $backgroundColor = Style::COLOR_BLACK;

    /**
     * Text to style
     *
     * @var string $text
     */
    protected $text = '';

    /**
     * If text is underlined
     *
     * @var boolean $isUnderline
     */
    protected $isUnderline = false;

    /**
     * If text is bolded
     *
     * @var boolean $isBold
     */
    protected $isBold = false;

    /**
     * If background has highlighted color.
     *
     * @var boolean $hasHighlightedBackground
     */
    protected $hasHighlightedBackground = false;

    /**
     * If background has highlighted color.
     *
     * @var boolean $hasHighlightedBackground
     */
    protected $hasHighlightedForeground = false;

    /**
     * Class constructor
     *
     * @param string $text
     * @return Style Class instance
     */
    public function __construct($text = '', $argument = null)
    {
        $this->text = $text;
        $this->argument = Argument::getInstance();
    }

    /**
     * Enable / Disable underline style.
     *
     * @param boolean $isUnderline
     * @return Style Class instance
     */
    public function underline($isUnderline = true)
    {
        $this->isUnderline = (bool) $isUnderline;

        return $this;
    }

    /**
     * Enable / Disable bold style.
     *
     * @param boolean $isBold
     * @return Style Class instance
     */
    public function bold($isBold = true)
    {
        $this->isBold = (bool) $isBold;

        return $this;
    }

    /**
     * Enable / Disable highlight on background or foreground
     *
     * @param  string $type
     * @param  boolean $isHighlight
     * @return Style Class instance
     */
    public function highlight($type = 'bg', $isHighlight = true)
    {
        if ($type === 'bg') {
            $this->hasHighlightedBackground = $isHighlight;
        } else {
            $this->hasHighlightedForeground = $isHighlight;
        }

        return $this;
    }

    /**
     * Set color for background / foreground
     *
     * @param  string $type
     * @param  string $color
     * @return Style Class instance
     */
    public function color($type = 'bg', $color = Style::COLOR_WHITE)
    {
        if ($type === 'bg') {
            $this->backgroundColor = $color;
        } else {
            $this->foregroundColor = $color;
        }

        return $this;
    }

    /**
     * Get text with styles.
     *
     * @return string
     */
    public function get()
    {
        if ($this->argument instanceof Argument && ! $this->argument->has('color')) {
            return $this->text;
        }

        $text = '';
        if ($this->foregroundColor !== '') {

            //~ Highlight
            $highlight = $this->hasHighlightedForeground ? static::HIGH_FOREGROUND : static::REGULAR_FOREGROUND;

            //~ Decoration
            $decoration = $this->isBold ? static::DECORATION_BOLD : '';
            $decoration .= $this->isUnderline ? static::DECORATION_UNDERLINE : '';
            $decoration = ! empty($decoration) ? $decoration : static::DECORATION_NONE;

            //~ Apply style
            $text .= self::BEGIN . $decoration . $highlight . $this->foregroundColor . self::END;
        }

        if ($this->backgroundColor !== '') {
            $highlight = $this->hasHighlightedBackground ? static::HIGH_BACKGROUND : static::REGULAR_BACKGROUND;
            $text .= self::BEGIN . $highlight . $this->backgroundColor . self::END;
        }

        $text .= $this->text . self::DESACTIVATE;

        return $text;
    }

    /**
     * Reset styles.
     *
     * @return Style Class instance
     */
    public function reset()
    {
        $this->isBold = false;
        $this->isUnderline = false;
        $this->hasHighlightedBackground = false;
        $this->hasHighlightedForeground = false;
        $this->backgroundColor = Style::COLOR_BLACK;
        $this->foregroundColor = Style::COLOR_WHITE;

        return $this;
    }

    /**
     * Set text.
     *
     * @param string $text
     * @return Style Class instance
     */
    public function setText($text = '')
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * Return text with styles.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }

}