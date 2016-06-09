<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


//~ Mount external libs if constant exists
if (defined('EXTERNAL_APP')) {
    Phar::mount('Package/', EXTERNAL_APP);
}

if (defined('EXTERNAL_COMPONENT')) {
    Phar::mount('Component/', EXTERNAL_COMPONENT);
}

if (defined('EXTERNAL_CONFIG')) {
    Phar::mount('Config/', EXTERNAL_CONFIG);
}


include 'phar://' . __FILE__ . '/index.php';

__HALT_COMPILER();
?>
