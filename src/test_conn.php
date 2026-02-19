<?php
require_once __DIR__ . '/Log/Logger.php';
require_once __DIR__ . '/Database/DBConnection.php';

use App\Log\Logger;
use App\Database\DBConnection;

$logger = new Logger(__DIR__ . '/logs/pdo_errors.log');
DBConnection::init($logger);

$pdo = DBConnection::get();
echo "Connexion OK\n";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

