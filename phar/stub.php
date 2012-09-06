#!/usr/bin/env php
<?php

/**
 * octdocd PHAR stub.
 *
 * @octdoc      h:phar/stub
 * @copyright   copyright (c) 2012 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
/**/

if (!class_exists('PHAR')) {
    print 'unable to execute -- wrong PHP version\n';
    exit(1);
}

Phar::mapPhar();
include 'phar://octdocd.phar/main.inc.php';

__HALT_COMPILER();