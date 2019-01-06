<?php

/* 
 * Send data to terminal by socket
 * string $command
 * string $data
 * string $target
 */

    $service_port='7999';
    if(is_array($data))
    {
        if (isset($data['ID'])) unset($data['ID']);
        if (isset($data['ID_TERMINAL'])) unset ($data['ID_TERMINAL']);
        $senddata =$command.':'. json_encode($data);
    }
    else 
    {
        $senddata = $command.':'.$data;
    }
    //$in= $command.':'.$senddata;
    
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
                socket_write($socket, $senddata, strlen($senddata));
            }
        }
        socket_close($socket);
        return TRUE;

