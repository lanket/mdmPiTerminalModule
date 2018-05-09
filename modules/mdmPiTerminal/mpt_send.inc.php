<?php

/* 
 * Send data to terminal by socket
 * string $command
 * string $data
 * string $target
 */

    $service_port='7999';
    $in= $command.':'.$data;

    if (preg_match('/^[\d\.]+$/',$target)) {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket) {
            $result = socket_connect($socket, $ip, $service_port);
            if ($result) {
                socket_write($socket, $in, strlen($in));
            }
        }
        socket_close($socket);
    } else {
        $qry=1;
        $qry.=" AND MAJORDROID_API=1";
        $qry.=" AND (NAME LIKE '".DBSafe($target)."' OR TITLE LIKE '".DBSafe($target)."')";
        $terminals = SQLSelect("SELECT * FROM terminals WHERE $qry");
        $total = count($terminals);
        for ($i = 0; $i < $total; $i++) {
            $address = $terminals[$i]['HOST'];
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket) {
                $result = socket_connect($socket, $address, $service_port);
                if ($result) {
                    socket_write($socket, $in, strlen($in));
                }
            }
            socket_close($socket);
        }
    }


