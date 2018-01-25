#!/usr/bin/env php
<?php

use Izomorry4\BracketChecker;

require_once "../vendor/autoload.php";

set_time_limit(0);
$host = "localhost";
$port = '';

while (!$port) {
    print sprintf("Enter port number: ");
    $port = trim(fgets(STDIN));
}
$socket = socket_create(AF_INET, SOCK_STREAM, 0);
socket_bind($socket, $host, $port);
socket_listen($socket, 3);

do {
    $messageSocket = socket_accept($socket);
    $msg = "It's bracket-checker server\n";
    $r = socket_write($messageSocket, $msg, strlen($msg));
    do {
        $buffer = socket_read($messageSocket, 2048);
        if ($buffer == 'quit') {
            break;
        }
        try {
            $isCorrect = BracketChecker\Checker::IsCorrect($buffer);
            $answer = sprintf("The result for you string %s is %r.\n", $buffer, $isCorrect ? 'ok' : 'error');
        } catch (Exception $e) {
            $answer = sprintf("ERROR! %s \n", $e->getMessage());
        }
        socket_write($messageSocket, $answer, strlen($answer));
        print sprintf("\n");
    } while (true);
    socket_close($messageSocket);
} while (true);
socket_close($socket);
