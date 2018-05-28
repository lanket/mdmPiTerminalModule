<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='mpt_kw';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
   
    //options for 'ID_TERMINAL' (select)
/*    global $id_terminal;
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
*/
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

   //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update sql
     $tmp = SQLSelectOne('SELECT HOST FROM terminals where ID = ' . $rec['ID_TERMINAL']);
     $ip = $tmp['HOST'];
     if($this->debug == 1) debmes('mpt edit: update assistanr settings: ' . $tmp['HOST']);
     $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
     unset($rec['ID'],$rec['ID_TERMINAL']);
     $senddata = json_encode($rec);
     if($this->debug == 1) debmes('mpt edit: send assistanr settings: ' . $senddata);
     $this->send_mpt('settings',$senddata,$ip);
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
     $tmp = SQLSelectOne('SELECT HOST FROM terminals where ID = ' . $rec['ID_TERMINAL']);
     $ip = $tmp['HOST'];
     $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
     unset($rec['ID'],$rec['ID_TERMINAL']);
     $senddata = json_encode($rec);
     if($this->debug == 1) debmes('mpt add new  assistant: ' . $senddata);
     $this->send_mpt('settings',$senddata,$ip);
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
