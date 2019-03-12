<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }

$rec=SQLSelectOne("SELECT * FROM $this->table_name WHERE ID='$id'");
if($rec['ID_TERMINAL'])
 {
  $terminal = SQLSelectOne('SELECT HOST , NAME FROM terminals where ID = ' . $rec['ID_TERMINAL']);
  $out['IP_TERMINAL'] = $terminal['HOST'];
// $mapSettings = $this->read_mapSettingsMpt($tmp['HOST']);
}

  if ($this->mode=='update') {
   $ok=1;
    //options for 'ID_TERMINAL' (select)
    global $id_terminal;
    global $data;
    $rec['ID_TERMINAL'] = $id_terminal;
    $qry = '';
    if($rec['ID']) $qry = " and ID <> " . $rec['ID'];
    $findduble=SQLSelectOne("SELECT * FROM $this->table_name WHERE ID_TERMINAL='$id_terminal' $qry");
    // chech not empy terminal and mast no dubles
    if(!$mapSettings)
    {
      $tmp=SQLSelectOne("SELECT * FROM terminals WHERE ID='$id_terminal'");
      $mapSettings = $this->read_mapSettingsMpt($tmp['HOST']);
    }
    if(($rec['ID_TERMINAL'] == '' or $findduble['ID']) and !$_POST['panel_voice'] and !$_POST['panel_admin'])
    {
        $out['ERR_ID_TERMINAL']=1;
        $ok=0;
    }
    $postdata = $_POST;
    unset($postdata["panel"],$postdata["view_mode"],$postdata["edit_mode"],$postdata["mode"],$postdata["id"]);

    foreach ($postdata as $key => $value) {
        $data[$key] = $value;
    }
   if($this->debug == 1) debmes('mpt edit befour ok');
   if ($ok) {
        if($this->debug == 1) debmes('mpt edit after ok');
        $nmTerm = $tmp['NAME'];
        if ($rec['ID']) {
            if($this->debug == 1) debmes("mpt edit update : $nmTerm ! ip: " . $tmp['HOST']);
            if ($this->config['CREATE_CLASS'] == 1)
            {
                if($this->debug == 1) debmes("mpt edit update CreateClass=True : $nmTerm ! ip: " . $tmp['HOST']);
                $data['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
                $data['MAJORDOMO_OBJECT_NAME'] = $nmTerm;
                $postdata['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
                $postdata['MAJORDOMO_OBJECT_NAME'] = $nmTerm;
            }
            SQLUpdate($table_name, $rec); // update sql
        } else {
            if($this->debug == 1) debmes('mpt edit no recid insert');
            $new_rec=1;
            if($this->debug == 1) debmes('mpt edit add object: ' . $nmTerm);
            if ($this->config['CREATE_CLASS'] == 1)
            {
                $this->addObject($nmTerm);
                $rec['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
                $rec['MAJORDOMO_OBJECT_NAME'] = $nmTerm;
            }
            $rec['ID']=SQLInsert($table_name, $rec); // adding new record
        }
        $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
        // if($this->debug == 1) debmes('mpt edit send: ' . $senddata);

        $this->send_mpt('settings', $postdata, $tmp['HOST']);
        $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }

// создаем список терминалов с голочкой MJrApi для выпадающего списка
    $tmp=SQLSelect("SELECT ID, TITLE, NAME, HOST, if(IS_ONLINE = 1, 'Online', 'Offline') as ONLINE FROM terminals WHERE MAJORDROID_API = 1 ORDER BY TITLE");
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
