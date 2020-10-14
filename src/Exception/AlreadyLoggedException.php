<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon\Exception;

/**
 * Class AlreadyLoggedException
 *
 * @author Romain Cottard
 */
class AlreadyLoggedException extends \Exception
{
    /**
     * AlreadyLoggedException constructor.
     * Override standard constructor
     *
     * @param \Exception $previous
     */
    public function __construct(\Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
