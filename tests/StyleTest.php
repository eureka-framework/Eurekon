<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

require_once '/usr/share/php/PHP/Token/Stream/Autoload.php';
require_once '/usr/share/php/Text/Template/Autoload.php';

/**
 * Require Style
 */
require_once realpath(__DIR__ . '/../') . '/Style.php';

/**
 * Require Argument
 */
require_once realpath(__DIR__ . '/../') . '/Argument.php';

/**
 * Require ArgumentIterator
 */
require_once realpath(__DIR__ . '/../') . '/ArgumentIterator.php';

/**
 * Class StyleTest for unit testing of Eurekon
 * \Style
 *
 * @author Romain Cottard
 * @version 1.0.0
 */
class StyleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Eurekon\Style class.
     * Simulate --color argument for script.
     *
     * @covers Eurekon\Style::__construct
     * @covers Eurekon\Style::__toString
     * @covers Eurekon\Style::get
     * @covers Eurekon\Style::color
     * @covers Eurekon\Style::highlight
     * @covers Eurekon\Style::reset
     * @covers Eurekon\Style::bold
     * @covers Eurekon\Style::underline
     */
    public function testWithStyles()
    {
        Argument::getInstance()->parse(array('--color')); // Simulate color
        $textStyles = array();

        $style = new Style('Hello, Test !');

        $textStyles[] = $style->get();
        $textStyles[] = $style->reset()
            ->setText('This is my text')
            ->bold()
            ->get();
        $textStyles[] = $style->reset()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->bold()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('bg', Style::COLOR_GREEN)
            ->color('fg', Style::COLOR_BLACK)
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold(false)
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold()
            ->highlight('fg')
            ->get();
        $textStyles[] = (string) $style->reset()
            ->color('bg', Style::COLOR_YELLOW)
            ->highlight('bg');
        $textStyles[] = (string) $style->reset()
            ->color('bg', Style::COLOR_YELLOW)
            ->highlight('fg');

        $compare = $this->getTextsWithStyles();
        foreach ($textStyles as $index => $textStyle) {
            $textStyle = (string) $textStyle;
            $textRaw   = (string) $compare[$index];
            $this->assertEquals($textStyle, $textRaw);
        }
    }

    /**
     * Test Eurekon\Style class
     *
     * @covers Eurekon\Style::__construct
     * @covers Eurekon\Style::__toString
     * @covers Eurekon\Style::get
     * @covers Eurekon\Style::color
     * @covers Eurekon\Style::highlight
     * @covers Eurekon\Style::reset
     * @covers Eurekon\Style::bold
     * @covers Eurekon\Style::underline
     */
    public function testNoStyles()
    {
        Argument::getInstance()->parse(array());
        $textStyles = array();
        $style      = new Style('Hello, Test !');

        $textStyles[] = $style->get();
        $textStyles[] = $style->reset()
            ->setText('This is my text')
            ->bold()
            ->get();
        $textStyles[] = $style->reset()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->bold()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('bg', Style::COLOR_GREEN)
            ->color('fg', Style::COLOR_BLACK)
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold()
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold(false)
            ->underline()
            ->get();
        $textStyles[] = $style->reset()
            ->color('fg', Style::COLOR_RED)
            ->bold()
            ->highlight('fg')
            ->get();
        $textStyles[] = (string) $style->reset()
            ->color('bg', Style::COLOR_YELLOW)
            ->highlight('bg');
        $textStyles[] = (string) $style->reset()
            ->color('bg', Style::COLOR_YELLOW)
            ->highlight('fg');

        $compare = $this->getTextsWithoutStyles();
        foreach ($textStyles as $index => $textStyle) {
            $textStyle = (string) $textStyle;
            $textRaw   = (string) $compare[$index];
            $this->assertEquals($textStyle, $textRaw);
        }
    }

    /**
     * Get comparison list of texts with styles.
     *
     * @return array
     */
    protected function getTextsWithStyles()
    {
        return array(
            "\033[0;37m\033[40mHello, Test !\033[0m",
            "\033[1;37m\033[40mThis is my text\033[0m",
            "\033[4;37m\033[40mThis is my text\033[0m",
            "\033[1;4;37m\033[40mThis is my text\033[0m",
            "\033[0;30m\033[42mThis is my text\033[0m",
            "\033[1;4;31m\033[40mThis is my text\033[0m",
            "\033[4;31m\033[40mThis is my text\033[0m",
            "\033[1;91m\033[40mThis is my text\033[0m",
            "\033[0;37m\033[103mThis is my text\033[0m",
            "\033[0;97m\033[43mThis is my text\033[0m"
        );
    }

    /**
     * Get comparison list of texts without styles.
     *
     * @return array
     */
    protected function getTextsWithoutStyles()
    {
        return array(
            'Hello, Test !',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text',
            'This is my text'
        );
    }
}
