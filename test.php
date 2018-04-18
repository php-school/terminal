<?php

use PhpSchool\Terminal\IO\ResourceInputStream;
use PhpSchool\Terminal\IO\ResourceOutputStream;
use PhpSchool\Terminal\TerminalReader;

require_once __DIR__ . '/vendor/autoload.php';

$terminal = new PhpSchool\Terminal\UnixTerminal(new ResourceInputStream(), new ResourceOutputStream());
$terminal->write('Enter age? ');
var_dump($terminal->read(5));
//var_dump($terminal->readCharacter());
//var_dump($terminal->readCharacter());

