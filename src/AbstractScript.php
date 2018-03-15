<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

use Psr\Container;

/**
 * Console Abstraction class.
 * Must be parent class for every console script class.
 *
 * @author  Romain Cottard
 */
abstract class AbstractScript implements ScriptInterface
{
    /** @var bool $executable Set to true to set class as an executable script */
    private $executable = false;

    /** @var string $description Console script description. */
    private $description = 'Script description for Help !';

    /** @var \Psr\Container\ContainerInterface $container Set to true to set class as an executable script */
    private $container = null;

    /** @var \Eureka\Component\Config\Config|null $config Container for config values */
    private $config = null;

    /**
     * Help method.
     * Must be overridden.
     *
     * @return void
     */
    abstract public function help();

    /**
     * Run method.
     * Must be overridden.
     *
     * @return void
     */
    abstract public function run();

    /**
     * @param  \Psr\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(Container\ContainerInterface $container = null)
    {
        $this->container = $container;

        if ($container === null) {
            return $this;
        }

        try {
            $this->config = $this->container->get('config');
        } catch (Container\NotFoundExceptionInterface $exception) {
            $this->config = null;
        } catch (Container\ContainerExceptionInterface $exception) {
            $this->config = null;
        }

        return $this;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * @param  bool $executable
     * @return $this
     */
    public function setExecutable($executable = true)
    {
        $this->executable = (bool) $executable;

        return $this;
    }

    /**
     * Return executable status about class.
     *
     * @return bool
     */
    public function executable()
    {
        return $this->executable;
    }

    /**
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Psr\Container\ContainerInterface
     * @throws
     */
    public function getConfig()
    {
        return $this->container->get('config');
    }

    /**
     * Return console script description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Method called before run() method.
     * Can be overridden.
     *
     * @return void
     */
    public function before()
    {
    }

    /**
     * Method called after run() method.
     * Can be overridden.
     *
     * @return void
     */
    public function after()
    {
    }
}
