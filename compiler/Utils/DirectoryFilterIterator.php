<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Eurekon;

/**
 * Directory filter  for iterator
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class DirectoryFilterIterator extends \RecursiveFilterIterator
{
    /**
     * List of directories to filter excluded.
     *
     * @var array $excluded
     */
    protected static $excluded = array();

    /**
     * Returns whether the current element of the iterator is acceptable
     * through this filter.
     *
     * @return   boolean
     */
    public function accept()
    {
        return !in_array($this->current()->getFilename(), static::$excluded, true);
    }

    /**
     * Set excluded directories.
     *
     * @param  array $directories
     * @return void
     */
    public static function exclude($directories)
    {
        static::$excluded = $directories;
    }
}