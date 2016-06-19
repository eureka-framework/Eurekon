<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Class to display console help message.
 *
 * 1.0.0: Initial interface
 * 2.0.0: New better way to launch scripts, through scripts classes.
 * 2.1.0: PSR-2
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class Help
{

    /**
     *
     * @var array $arguments List of arguments for script
     */
    protected $arguments = array();

    /**
     *
     * @var string $script_name Script name
     */
    protected $scriptName = '';

    /**
     * Class constructor.
     *
     * @param string $scriptName Script name
     * @return Help Class instance
     */
    public function __construct($scriptName)
    {
        $this->scriptName = $scriptName;

        $this->addArgument('h', 'help', 'Reserved - Display Help', false, false);
        $this->addArgument('', 'color', 'Reserved - Use color system for cli display', false, false);
        $this->addArgument('', 'debug', 'Reserved - Use this argument to display trace for exceptions', false, false);
        $this->addArgument('', 'time-limit', 'Reserved - Set time limit in seconds (default: 0 - unlimited)', true, false);
        $this->addArgument('', 'error-reporting', 'Reserved - Set value for error-reporting (default: -1 - all)', true, false);
        $this->addArgument('', 'error-display', 'Reserved - Set value for display_errors setting. Values: 0|1 Default: 1 (display)', true, false);
        $this->addArgument('', 'quiet', 'Reserved - Do not show message displayed throught Out::std() method', false, false);
        $this->addArgument('', 'name', 'Reserved - Console class script to run (Example: \Eureka\Package\Orm\Console', true, true);
    }

    /**
     * Display help
     * Result example:
     *
     * -h, --help Reserved - Display Help', PHP_EOL;
     * --color Reserved - Use color system for cli display
     * --debug Reserved - Use this argument to display trace for exceptions
     *
     * @return void
     */
    public function display()
    {
        $style = new Style();
        echo PHP_EOL;

        echo $style->setText('Use    : ')
            ->color('fg', Style::COLOR_GREEN)
            ->highlight('fg')
            ->bold()
            ->get();

        echo $style->reset()
            ->setText('php  ' . $this->scriptName . ' [OPTION]...')
            ->highlight('fg')
            ->bold()
            ->get() . PHP_EOL;

        echo $style->reset()
            ->setText('OPTIONS: ')
            ->color('fg', Style::COLOR_GREEN)
            ->bold()
            ->get() . PHP_EOL;

        foreach ($this->arguments as $argument) {
            $line = '  ';

            if (! empty($argument->shortName)) {
                $line .= '-' . $argument->shortName;

                if ($argument->hasValue) {
                    $line .= ' ARG';
                }

                $line .= ',';
            }

            $line = str_pad($line, 10); // add 8 space


            if (! empty($argument->fullName)) {
                $line .= '--' . $argument->fullName;
                if ($argument->hasValue) {
                    $line .= '=ARG';
                }
            }

            $line = $style->reset()
                ->setText(str_pad($line, 40))
                ->bold()
                ->get();
            $line .= $argument->description;

            if ($argument->isMandatory) {
                $line .= $style->reset()
                    ->setText(' - MANDATORY')
                    ->color('fg', Style::COLOR_RED)
                    ->get();
            }

            echo $line, PHP_EOL;
        }

        echo PHP_EOL;
    }

    /**
     * Add argument in list for script help
     *
     * @param string $shortName Short name for argument
     * @param string $fullName Full name for argument
     * @param string $description Argument's description
     * @param boolean $hasValue If argument must have value
     * @param boolean $isMandatory Set true to force mandatory mention.
     * @return void
     */
    public function addArgument($shortName = '', $fullName = '', $description = '', $hasValue = false, $isMandatory = false)
    {
        $argument = new \stdClass();
        $argument->shortName = $shortName;
        $argument->fullName = $fullName;
        $argument->description = $description;
        $argument->hasValue = (bool) $hasValue;
        $argument->isMandatory = (bool) $isMandatory;

        $this->arguments[] = $argument;
    }
}
