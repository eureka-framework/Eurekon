<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Class to execute specified console scripts.
 * 1.0.0: Initial class
 * 2.0.0: New better way to launch scripts, through scripts classes.
 * 2.1.0: PSR-2
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class Eurekon
{

    /**
     * @var float $time Timer for script
     */
    protected $time = 0.0;

    /**
     * @var boolean $isVerbose Set true to display header/footer script message (name, time...)
     */
    protected $isVerbose = true;

    /**
     * @var Argument $argument Argument object
     */
    protected $argument = null;

    /**
     * Class constructor.
     *
     * @param array $args List of arguments for current script to execute.
     * @return Eurekon Console instance.
     */
    public function __construct(Array $args)
    {
        $this->argument = Argument::getInstance()->parse($args);
    }

    /**
     * Display console lib help
     *
     * @return void
     */
    protected function help()
    {
        $style = new Style(' *** RUN - HELP ***');
        Out::std($style->color('fg', Style::COLOR_GREEN)->get());
        Out::std('');

        $help = new Help('...', true);
        $help->addArgument('', 'color', 'Activate colors (do not activate when redirect output in log file, colors are non-printable chars)', false, false);
        $help->addArgument('', 'debug', 'Activate debug mode (trace on exception if script is terminated with an exception)', false, false);
        $help->addArgument('', 'time-limit', 'Specified time limit in seconds (default: 0 - unlimited)', true, false);
        $help->addArgument('', 'error-reporting', 'Specified value for error-reporting (default: -1 - all)', true, false);
        $help->addArgument('', 'error-display', 'Specified value for display_errors setting. Values: 0|1 Default: 1 (display)', true, false);
        $help->addArgument('', 'quiet', 'Force disabled console lib messages (header, footer, timer...)', false, false);
        $help->addArgument('', 'name', 'Console class script to run (Example: \Eureka\Component\Database\Console', true, true);

        $help->display();
    }

    /**
     * This method is executed before main method of console.
     * - init timer
     * - init error_reporting
     * - init time limit for script
     * - init verbose mode
     *
     * @return void
     */
    public function before()
    {

        // ~ Init timer
        $this->time = - microtime(true);

        // ~ Reporting all error (default: all error) !
        error_reporting((int) $this->argument->get('error-reporting', null, - 1));
        ini_set('display_errors', (bool) $this->argument->get('error-display', null, 1));

        // ~ Set limit time to 0 (default: unlimited) !
        set_time_limit((int) $this->argument->get('time-limit', null, 0));

        $this->isVerbose = ! $this->argument->has('quiet');
    }

    /**
     * This method is main method for console lib.
     * - display console lib help
     * - OR display script help (if script name is defined)
     * - OR execute script
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        try {

            // ~ Hook for console help
            if (! $this->argument->has('name')) {

                // ~ Display Help
                $this->help();

                // ~ If no help asked, throw exception !
                if (! $this->argument->has('help')) {
                    throw new \Exception(__METHOD__ . '|Script name must be defined !');
                }

                return;
            }

            // ~ Create new object '*'
            $className = $this->argument->get('name');
            $script = new $className();

            if (!($script instanceof ConsoleInterface)) {
                throw new \Exception('Current script must be implement ConsoleInterface interface !');
            }

            if (! $script->executable()) {
                throw new \Exception(__METHOD__ . '|Script is not executable !');
            }

            $style = new Style(' >>> RUN - ' . $className . ' - ' . date('Y-m-d H:i:s') . ' <<<');
            $style->color('fg', Style::COLOR_GREEN);

            if ($this->argument->has('help')) {

                Out::std($style->get());

                $script->help();
            } else {

                // ~ Execute this method before starting main script method
                $script->before();

                // ~ Display header script only after execution of before method (prevent error with start_session() for example).
                Out::std($style->get());

                // ~ Execute main script method.
                $script->run();

                // ~ Execute this method after execution of main script method.
                $script->after();
            }
        } catch (\Exception $exception) {

            if (! $this->isVerbose) {
                throw $exception;
            }

            $style = new Style(' ~~ EXCEPTION[' . $exception->getCode() . ']: ' . $exception->getMessage());
            $style->color('bg', Style::COLOR_RED);
            Out::std(PHP_EOL . $style->get());

            if ($this->argument->has('debug')) {
                echo $exception->getTraceAsString(), PHP_EOL;
            }
        }
    }

    /**
     * This method is executed after main method of console.
     * - display footer script data (timer)
     *
     * @return void
     */
    public function after()
    {

        // ~ Display footer script timer
        $this->time += microtime(true);
        $style = new Style(' *** END SCRIPT - Time taken: ' . round($this->time, 2) . 's - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style::COLOR_GREEN);

        Out::std($style->get());
    }
}
