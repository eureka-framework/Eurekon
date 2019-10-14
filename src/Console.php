<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class to execute specified console scripts.
 *
 *
 * @author Romain Cottard
 */
class Console
{
    use LoggerAwareTrait;

    /** @var float $time Timer for script */
    protected $time = 0.0;

    /** @var boolean $isVerbose Set true to display header/footer script message (name, time...) */
    protected $isVerbose = true;

    /** @var Argument\Argument $argument Argument object */
    protected $argument = null;

    /** @var ContainerInterface */
    protected $container = null;

    /** @var int $exitCode Exit code script. */
    protected $exitCode = 0;

    /** @var array $baseNamespaces Base namespaces for scripts class to execute. */
    protected $baseNamespaces = ['Eureka\Component'];

    /**
     * Class constructor.
     *
     * @param array $args List of arguments for current script to execute.
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(array $args, ContainerInterface $container = null, LoggerInterface $logger = null)
    {
        $this->argument  = Argument\Argument::getInstance()->parse($args);
        $this->container = $container;

        if ($logger !== null) {
            $this->setLogger($logger);
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set base namespaces.
     *
     * @param  array $baseNamespaces
     * @return $this
     */
    public function setBaseNamespaces(array $baseNamespaces = [])
    {
        foreach ($baseNamespaces as $baseNamespace) {
            $this->baseNamespaces[] = trim($baseNamespace, '\\');
        }

        return $this;
    }

    /**
     * Display console lib help
     *
     * @return void
     */
    protected function help()
    {
        $style = new Style\Style(' *** RUN - HELP ***');
        IO\Out::std($style->colorForeground(Style\Color::GREEN)->get());
        IO\Out::std('');

        $help = new Help('...');
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
        $this->time = -microtime(true);

        // ~ Reporting all error (default: all error) !
        error_reporting((int) $this->argument->get('error-reporting', null, - 1));
        ini_set('display_errors', (bool) $this->argument->get('error-display', null, 1));

        // ~ Set limit time to 0 (default: unlimited) !
        set_time_limit((int) $this->argument->get('time-limit', null, 0));

        // Set memory limit
        ini_set('memory_limit', (string) $this->argument->get('memory-limit', null, '256M'));

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
        $script = null;

        $beforeHasBeenRun = false;

        try {
            $name = $this->argument->get('name', null, '');

            //~ Try to get default argument value if exist, to use it as a name.
            if (empty($name)) {
                $name = $this->argument->get('__default__', null, '');
            }

            // ~ Hook for console help
            if (empty($name)) {

                // ~ Display Help
                $this->help();

                // ~ If no help asked, throw exception !
                if (!$this->argument->has('help')) {
                    throw new \RuntimeException(__METHOD__ . '|Script name must be defined !');
                }

                return;
            }

            // ~ Create new object '*'
            $scriptName  = str_replace('/', '\\', ucwords($name, '/\\'));

            $classFound = false;
            $className  = '';
            foreach ($this->baseNamespaces as $baseNamespace) {
                $className = '\\' . trim($baseNamespace . '\\' . $scriptName, '\\');

                if (class_exists($className)) {
                    $classFound = true;
                    break;
                }
            }

            if (!$classFound) {
                throw new \RuntimeException('Current script class does not exists (script: "' . $scriptName . '") !');
            }

            $script = $this->getScriptInstance($className);
            $script->setContainer($this->getContainer());

            $style = new Style\Style();
            $style->setText(' *** RUN - ' . $scriptName . ' - ' . date('Y-m-d H:i:s') . ' ***');
            $style->color('fg', Style\Color::GREEN);

            if ($this->argument->has('help')) {
                IO\Out::std($style->get());

                $script->help();
            } else {

                // ~ Execute this method before starting main script method
                $script->before();

                // ~ Display header script only after execution of before method (prevent error with start_session() for example).
                if (!$this->argument->has('script-no-header')) {
                    IO\Out::std($style->get());
                }

                $beforeHasBeenRun = true;

                // ~ Execute main script method.
                $script->run();
            }
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            if (!$this->isVerbose) {
                throw $exception;
            }

            if ($this->logger instanceof LoggerInterface && !$exception instanceof Exception\AlreadyLoggedException) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception, 'type' => 'console.log']);
            }

            $style = new Style\Style(' ~~ EXCEPTION[' . $exception->getCode() . ']: ' . $exception->getMessage());
            $style->color('bg', Style\Color::RED);
            IO\Out::std(PHP_EOL . $style->get());

            if ($this->argument->has('debug')) {
                echo $exception->getFile().PHP_EOL;
                echo $exception->getLine().PHP_EOL;
                echo $exception->getTraceAsString().PHP_EOL;
            }
        } finally {

            if ($beforeHasBeenRun && $script instanceof ScriptInterface) {
                // ~ Execute this method after execution of main script method.
                $script->after();
            }
        }
    }

    /**
     * Get a valid script Instance
     * @param $className
     * @return mixed
     * @throws \LogicException
     */
    protected function getScriptInstance($className)
    {
        try {
            $script = $this->getContainer()->get(strtr($className, '/', '\\'));
        } catch (\Exception $exception) {
            $script = new $className();
        }

        if (!($script instanceof ScriptInterface)) {
            throw new \LogicException('Current script must implement ScriptInterface interface !');
        }

        if (!$script->executable()) {
            throw new \LogicException(__METHOD__ . '|Script is not executable !');
        }
        return $script;
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
        $style = new Style\Style(' *** END SCRIPT - Time taken: ' . round($this->time, 2) . 's - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style\Color::GREEN);

        if (!$this->argument->has('script-no-header')) {
            IO\Out::std($style->get());
        }
    }

    /**
     * Terminate script with correct execution code.
     *
     * @return void
     */
    public function terminate()
    {
        exit($this->exitCode);
    }
}
