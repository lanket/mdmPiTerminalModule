<?php

/*
 * Read map of settings from terminal by socket
 * string $target
 */

    $service_port='7999';

    if (!filter_var($target, FILTER_VALIDATE_IP)) {
        $tmp = SQLSelectOne("SELECT HOST FROM terminals where NAME = '$target'");
        if($tmp['HOST'])
        {
            $target = $tmp['HOST'];
        }
        else
        {
            return FALSE;
        }

    }

    if($this->debug == 1) debmes("mpt send to = $target : $senddata");
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket) {
            $result = socket_connect($socket, $target, $service_port);
            if ($result) {
                socket_write($socket, "get_map_settings", 16);
                $tmp = socket_read($socket, 2000, PHP_NORMAL_READ);
            }
        }
        socket_close($socket);
        return json_decode($tmp , TRUE);
