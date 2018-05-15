<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  if ($event=='SAY') {
   $level=$details['level'];
   $message=$details['message'];
        $rec=SQLSelect("SELECT terminals.HOST FROM `mpt` inner join terminals on mpt.ID_TERMINAL = terminals.ID");
        foreach ($rec as $terminalpi) {
            $this->send_mpt('tts', $message, $terminalpi['HOST']);
        }

   //debmes('mpt say ' . $message . '; level = ' . $level);
  }
  
  if ($event=='ASK') {
   $tartget = $this->targetToIp($details['target']);
   if(!$target) return 0;
   $message=$details['prompt'];
   $this->send_mpt('ask', $message, $target);
   //debmes('mpt ask ' . $message . '; target = ' . $target);
  }

  if ($event=='SAYTO') {
   //debmes('mpt sayto start');
   $level=$details['level'];
   $message=$details['message'];
   $target = $this->targetToIp($details['destination']);
   //debmes('mpt sayto after ttIp : ' . $target);
   if(!$target) return 0;
   $this->send_mpt('tts', $message, $target);
   //debmes('mpt sayto ' . $message . '; level = ' . $level . '; to = ' . $target);
  }
/*
  if ($event=='SAYREPLY') {
   $level=$details['level'];
   $message=$details['message'];
   $source=$details['source'];
   $tartget = $this->targetToIp($details['replyto']);
   if(!$target) return 0;
   $this->send_mpt('tts', $message, $target);
   debmes('mpt sayto ' . $message . '; level = ' . $level . '; to = ' . $destination);
  }
*/