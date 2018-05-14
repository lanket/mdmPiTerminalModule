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
   
    //options for 'ID_TERMINAL' (select)
    global $id_terminal;
    $rec['ID_TERMINAL'] = $id_terminal;
    $qry = '';
    if($rec['ID']) $qry = " and ID <> " . $rec['ID'];
    $findduble=SQLSelectOne("SELECT * FROM $table_name WHERE ID_TERMINAL='$id_terminal' $qry");
    // chech not empy terminal and mast no dubles
    if($rec['ID_TERMINAL'] == '' or $findduble['ID'])
    {
        $out['ERR_ID_TERMINAL']=1;
        $ok=0;
    }
  //updating '<%LANG_TITLE%>' (varchar, required)
   /*
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
   */
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
   if(!$alarmkwactivated) $alarmkwactivated = 0;
   $rec['ALARMKWACTIVATED']=$alarmkwactivated;
  //updating 'alarmtts' (varchar)
   global $alarmtts;
   if(!$alarmtts) $alarmtts=0;
   if(!$alarmtts)
   $rec['ALARMTTS']=$alarmtts;
  //updating 'alarmstt' (varchar)
   global $alarmstt;
   if(!$alarmstt) $alarmstt=0;
   $rec['ALARMSTT']=$alarmstt;
/*
   //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  //updating '<%LANG_LINKED_PROPERTY%>' (varchar)
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
 
 */
  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     //$tmp = SQLSelectOne('SELECT HOST FROM terminals where ID = ' . $rec['ID_TERMINAL']);
     //$rec['IP'] = $tmp['HOST'];
     //debmes('mpt: ' . $tmp['HOST']);
     SQLUpdate($table_name, $rec); // update sql
     $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
     $senddata = json_encode($rec);
     //debmes('mpt: ' . $senddata);
     $this->send_mpt('settings',$senddata,$rec['IP']);
    } else {
     $new_rec=1;
     //$tmp = SQLSelectOne('SELECT HOST FROM terminals where ID = ' . $rec['ID_TERMINAL']);
     //$rec['IP'] = $tmp['HOST'];
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
     $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
     $senddata = json_encode($rec);
     //debmes('mpt: ' . $senddata);
     $this->send_mpt('settings',$senddata,$rec['IP']);
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }
    $tmp=SQLSelect("SELECT ID, TITLE as NAME FROM terminals ORDER BY NAME");
    $terminals_total=count($tmp);
    for($terminals_i=0;$terminals_i<$terminals_total;$terminals_i++) {
        $terminal_id_opt[$tmp[$terminals_i]['ID']]=$tmp[$terminals_i]['NAME'];
    }
    for($i=0;$i<$terminals_total;$i++) {
        if ($rec['ID_TERMINAL']==$tmp[$i]['ID']) $tmp[$i]['SELECTED']=1;
    }
    $out['ID_TERMINAL_OPTIONS']=$tmp;
  
  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
