<?php

/*
 * Read map of settings from terminal by socket_rpc
 * string $target
 * Пример взят отсюда https://gtxtymt.xyz/blog/json-rpc-client-php
 * Классы из примера mpt_jsonLib.inc.php
 */

/*
 $client = new mpt_client($this->targetToIp($target), ['login', 'password']);
 $response = $client->readMapSettings();

 if($response->isSuccess()) {
     return $response->getResult();
 }
 else {
     debmes ($response->getErrorCode().': '.$response->getErrorMessage());
 }
*/

$json = file_get_contents('/var/www/modules/mdmPiTerminal/jsonfrommdp.json');
return json_decode($json);

//    $service_port='7999';
/*
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
*/

/*
* first version whiout socket_rpc
*

    if($this->debug == 1) debmes("mpt send to = $this->targetToIp($target) : $senddata");
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket) {
            $result = socket_connect($socket, $this->targetToIp($target), $this->service_port);
            if ($result) {
                socket_write($socket, "get_map_settings", 16);
                $tmp = socket_read($socket, 2000, PHP_NORMAL_READ);
            }
        }
        socket_close($socket);
        return json_decode($tmp , TRUE);
*/
