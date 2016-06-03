<?php

//$additional is an array of [key, value] pairs
$additional = array();

//$additional['title'] = 'Home to that Strange Guy';

$additional['description'] = 'The home of the internet nobody: M4Numbers. This site '
                             .'contains documentation details for the various projects'
                             .' that he has taken part in at some point or another.';

$file_contents = file_get_contents('documents/' . $file);

use \Michelf\Markdown;
$additional['file'] = Markdown::defaultTransform($file_contents);
$additional['parts'] = $parts;