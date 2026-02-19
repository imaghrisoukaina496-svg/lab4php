<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'Logger.php'; // adapte le chemin si besoin

use App\Log\Logger;

$logger = new Logger(__DIR__ . '/../../logs/pdo_errors.log');

$logger->error('Ceci est un test', ['variable' => 123]);

