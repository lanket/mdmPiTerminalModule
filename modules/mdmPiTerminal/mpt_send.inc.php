<?php

/* 
 * Send data to terminal by socket
 * string $command
 * string $data
 * string $target
 */

    $service_port='7999';
    $in= $command.':'.$data;
    
    if($this->debug == 1) debmes('mpt send - ' . $in . '; to = ' . $target);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket) {
            $result = socket_connect($socket, $target, $service_port);
            if ($result) {
                socket_write($socket, $in, strlen($in));
            }
        }
        socket_close($socket);

