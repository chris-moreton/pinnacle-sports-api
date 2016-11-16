<?php
require 'vendor/autoload.php';

use Netsensia\Pinnacle\Api\Client\Client;

$key = trim(file_get_contents('.apiKey'));

$c = new Client($key);

var_dump($c->getOdds(33, null, null, null, 'decimal'));

