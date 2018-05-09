<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='mpt';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
  //updating '<%LANG_TITLE%>' (varchar, required)
   global $title;
   $rec['TITLE']=$title;
   if ($rec['TITLE']=='') {
    $out['ERR_TITLE']=1;
    $ok=0;
   }
  //updating 'name' (varchar)
   global $name;
   $rec['NAME']=$name;
  //updating 'linkedRoom' (varchar)
   global $linkedroom;
   $rec['LINKEDROOM']=$linkedroom;
  //updating 'ip' (varchar)
   global $ip;
   $rec['IP']=$ip;
  //updating 'providertts' (varchar)
   global $providertts;
   $rec['PROVIDERTTS']=$providertts;
  //updating 'apikeytts' (varchar)
   global $apikeytts;
   $rec['APIKEYTTS']=$apikeytts;
  //updating 'providerstt' (varchar)
   global $providerstt;
   $rec['PROVIDERSTT']=$providerstt;
  //updating 'apikeystt' (varchar)
   global $apikeystt;
   $rec['APIKEYSTT']=$apikeystt;
  //updating 'sensitivity' (varchar)
   global $sensitivity;
   $rec['SENSITIVITY']=$sensitivity;
  //updating 'alarmkwactivated' (varchar)
   global $alarmkwactivated;
   $rec['ALARMKWACTIVATED']=$alarmkwactivated;
  //updating 'alarmtts' (varchar)
   global $alarmtts;
   $rec['ALARMTTS']=$alarmtts;
  //updating 'alarmstt' (varchar)
   global $alarmstt;
   $rec['ALARMSTT']=$alarmstt;
  //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  //updating '<%LANG_LINKED_PROPERTY%>' (varchar)
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID'] and $ip) {
     SQLUpdate($table_name, $rec); // update sql
     $senddata = json_encode($rec);
     $this->send_mpt('settings',$senddata,$ip);
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }
  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
