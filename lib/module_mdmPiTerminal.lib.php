<?php

/* 
 * $command команда терминалу может быть tts settings ... подробнее о командах в вики терминала https://github.com/Aculeasis/mdmTerminal2/wiki
 * $data данные могут быть как массивом так и готовым json так и просто текстом
 * $target системное имя терминала либо его ip
 */

function sendToMpt($command, $data, $target)  
{
    require(DIR_MODULES.'mdmPiTerminal/mdmPiTerminal.class.php');
    $mpt = new  mdmPiTerminal();
    $res = $mpt->send_mpt($command, $data, $target);
    return $res;
}
?>