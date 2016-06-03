<?php

//$additional is an array of [key, value] pairs
$additional = array();

//$additional['title'] = 'Home to that Strange Guy';

$additional['description'] = 'The homepage for M4Numbers: that strange internet '
                             .'guy. Here are a few things that are on this site '
                             .'that you might find interesting';

$file_contents = file_get_contents('documents/' . $file);

use \Michelf\Markdown;
$additional['file'] = Markdown::defaultTransform($file_contents);
$additional['parts'] = $parts;