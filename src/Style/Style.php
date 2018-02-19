<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Style;

use Eureka\Eurekon\Argument\Argument;

/**
 * Add style to text for unix terminal display.
 *
 * @author Romain Cottard
 */
class Style
{
    /** @var string DECORATION_NONE Index color character for no decoration. */
    const DECORATION_NONE = '0;';

    /** @var string DECORATION_BOLD Index color character for text bold decoration. */
    const DECORATION_BOLD = '1;';

    /** @var string DECORATION_UNDERLINE Index color character text underline decoration. */
    const DECORATION_UNDERLINE = '4;';

    /** @var string REGULAR_FOREGROUND Index color character normal foreground. */
    const REGULAR_FOREGROUND = '3';

    /** @var string REGULAR_BACKGROUND Index color character normal background. */
    const REGULAR_BACKGROUND = '4';

    /** @var string HIGH_FOREGROUND Index color character highlight foreground. */
    const HIGH_FOREGROUND = '9';

    /** @var string HIGH_BACKGROUND Index color character highlight background. */
    const HIGH_BACKGROUND = '10';

    /** @var string BEGIN First characters for color text. (internal constant) */
    const BEGIN = "\033[";

    /** @var string END End characters for color text. (internal constant) */
    const END = 'm';

    /** @var string DEACTIVATE Last characters for stopping color text. (internal constant) */
    const DEACTIVATE = "\033[0m";

    /** @var string $foregroundColor Foreground color character */
    protected $foregroundColor = Color::WHITE;

    /** @var string $foregroundColor Foreground color character */
    protected $backgroundColor = Color::BLACK;

    /** @var string $text Text to style */
    protected $text = '';

    /** @var boolean $isUnderline If text is underlined */
    protected $isUnderline = false;

    /** @var boolean $isBold If text is bolded */
    protected $isBold = false;

    /** @var boolean $hasHighlightedBackground If background has highlighted color. */
    protected $hasHighlightedBackground = false;

    /** @var boolean $hasHighlightedBackground If background has highlighted color. */
    protected $hasHighlightedForeground = false;

    /** @var int $padNb Pad number of char */
    protected $padNb = 0;

    /** @var string $padChar Pad char */
    protected $padChar = ' ';

    /** @var int $padDir Pad direction */
    protected $padDir = STR_PAD_RIGHT;

    /** @var bool $isStyleEnabled */
    protected $isStyleEnabled = true;

    /**
     * Class constructor
     *
     * @param string $text
     */
    public function __construct($text = '')
    {
        $this->text        = $text;
        $this->isStyleEnabled = Argument::getInstance()->has('color');
    }

    /**
     * Enable / Disable underline style.
     *
     * @param boolean $isUnderline
     * @return $this
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
     * @return $this
     */
    public function bold($isBold = true)
    {
        $this->isBold = (bool) $isBold;

        return $this;
    }

    /**
     * Enable / Disable highlight on background or foreground
     *
     * @param  string  $type
     * @param  boolean $isHighlight
     * @return $this
     */
    public function highlight($type = 'bg', $isHighlight = true)
    {
        if ($type === 'bg') {
            $this->highlightBackground($isHighlight);
        } else {
            $this->highlightForeground($isHighlight);
        }

        return $this;
    }

    /**
     * Enable / Disable highlight on background
     *
     * @param  boolean $isHighlight
     * @return $this
     */
    public function highlightBackground($isHighlight = true)
    {
        $this->hasHighlightedBackground = $isHighlight;

        return $this;
    }

    /**
     * Enable / Disable highlight on background or foreground
     *
     * @param  boolean $isHighlight
     * @return $this
     */
    public function highlightForeground($isHighlight = true)
    {
        $this->hasHighlightedForeground = $isHighlight;

        return $this;
    }

    /**
     * Set color for background / foreground
     *
     * @param  string $type
     * @param  string $color
     * @return $this
     */
    public function color($type = 'bg', $color = Color::WHITE)
    {
        if ($type === 'bg') {
            $this->backgroundColor = $color;
        } else {
            $this->foregroundColor = $color;
        }

        return $this;
    }

    /**
     * Set color for background
     *
     * @param  string $color
     * @return $this
     */
    public function colorBackground($color = Color::WHITE)
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * Set color for foreground
     *
     * @param  string $color
     * @return $this
     */
    public function colorForeground($color = Color::WHITE)
    {
        $this->foregroundColor = $color;

        return $this;
    }

    /**
     * Get text with styles.
     *
     * @return string
     */
    public function get()
    {
        $textDisplay = $this->text;

        if ($this->padNb > 0) {
            $textDisplay = str_pad($textDisplay, $this->padNb, $this->padChar, $this->padDir);
        }

        if (!$this->isStyleEnabled) {
            return $textDisplay;
        }

        $text = '';
        if ($this->foregroundColor !== '') {

            //~ Highlight
            $highlight = $this->hasHighlightedForeground ? static::HIGH_FOREGROUND : static::REGULAR_FOREGROUND;

            //~ Decoration
            $decoration = $this->isBold ? static::DECORATION_BOLD : '';
            $decoration .= $this->isUnderline ? static::DECORATION_UNDERLINE : '';
            $decoration = !empty($decoration) ? $decoration : static::DECORATION_NONE;

            //~ Apply style
            $text .= self::BEGIN . $decoration . $highlight . $this->foregroundColor . self::END;
        }

        if ($this->backgroundColor !== '') {
            $highlight = $this->hasHighlightedBackground ? static::HIGH_BACKGROUND : static::REGULAR_BACKGROUND;
            $text      .= self::BEGIN . $highlight . $this->backgroundColor . self::END;
        }

        $text .= $textDisplay . self::DEACTIVATE;

        return $text;
    }

    /**
     * Reset styles.
     *
     * @return $this
     */
    public function reset()
    {
        $this->isBold                   = false;
        $this->isUnderline              = false;
        $this->hasHighlightedBackground = false;
        $this->hasHighlightedForeground = false;
        $this->backgroundColor          = Color::BLACK;
        $this->foregroundColor          = Color::WHITE;
        $this->padNb                    = 0;
        $this->padChar                  = ' ';
        $this->padDir                   = STR_PAD_RIGHT;

        return $this;
    }

    /**
     * Set text.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text = '')
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * Set pad for the text.
     *
     * @param  int    $pad
     * @param  string $char
     * @param  int    $dir
     * @return $this
     */
    public function pad($pad, $char = ' ', $dir = STR_PAD_RIGHT)
    {
        $this->padNb   = $pad;
        $this->padChar = $char;
        $this->padDir  = $dir;

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
