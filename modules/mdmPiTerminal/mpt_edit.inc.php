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
    if(($rec['ID_TERMINAL'] == '' or $findduble['ID']) and !$_POST['panel_voice'])
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
   if($_POST['panel_voice'])
   {
        //updating 'snowboy_token' (varchar)
         $rec['SNOWBOY_TOKEN']=  $this->validate('snowboy_token');
        //updating 'settings_sensitivity' (varchar)
         $rec['SETTINGS_SENSITIVITY']=  $this->validate('settings_sensitivity');
        //updating 'settings_providertts' (varchar)
         $rec['SETTINGS_PROVIDERTTS']=  $this->validate('settings_providertts');
        //updating 'settings_providerstt' (varchar)
         $rec['SETTINGS_PROVIDERSTT']=  $this->validate('settings_providerstt');

        //updating 'yandex_apikeytts' (varchar)
         $rec['YANDEX_APIKEYTTS']= $this->validate('yandex_apikeytts');
        //updating 'yandex_apikeystt' (varchar)
         $rec['YANDEX_APIKEYSTT']= $this->validate('yandex_apikeystt');
        //updating 'yandex_emotion' (varchar)
         $rec['YANDEX_EMOTION']= $this->validate('yandex_emotion');
        //updating 'yandex_speaker' (varchar)
         $rec['YANDEX_SPEAKER']= $this->validate('yandex_speaker');
        //updating 'aws_speaker' (varchar)
         $rec['AWS_SPEAKER']= $this->validate('aws_speaker');
        //updating 'aws_access_key_id' (varchar)
         $rec['AWS_ACCESS_KEY_ID']= $this->validate('aws_access_key_id');
        //updating 'aws_secret_access_key' (varchar)
         $rec['AWS_SECRET_ACCESS_KEY']= $this->validate('aws_secret_access_key');
        //updating 'aws_region' (varchar)
         $rec['AWS_REGION']= $this->validate('aws_region');
        //updating 'aws_boto3' (varchar)
         $rec['AWS_BOTO3']= $this->validate('aws_boto3');
        //updating 'rhvoice0rest_server' (varchar)
         $rec['RHVOICE0REST_SERVER']= $this->validate('rhvoice0rest_server');
        //updating 'rhvoice0rest_speaker' (varchar)
         $rec['RHVOICE0REST_SPEAKER']= $this->validate('rhvoice0rest_speaker');
        //updating 'rhvoice0rest_rate' (varchar)
         $rec['RHVOICE0REST_RATE']= $this->validate('rhvoice0rest_rate');
        //updating 'rhvoice0rest_pitch' (varchar)
         $rec['RHVOICE0REST_PITCH']= $this->validate('rhvoice0rest_pitch');
        //updating 'rhvoice0rest_volume' (varchar)
         $rec['RHVOICE0REST_VOLUME']= $this->validate('rhvoice0rest_volume');
        //updating 'rhvoice_rest_volume' (varchar)
         $rec['RHVOICE_SPEAKER']= $this->validate('rhvoice_speaker');
   }
   else {
        //updating 'settings_alarmkwactivated' (BOOLEAN)
         $rec['SETTINGS_ALARMKWACTIVATED']=  $this->validate('settings_alarmkwactivated');
        //updating 'settings_alarmtts' (BOOLEAN)
         $rec['SETTINGS_ALARMTTS']=  $this->validate('settings_alarmtts');
        //updating 'settings_alarmstt' (BOOLEAN)
         $rec['SETTINGS_ALARMSTT']=  $this->validate('settings_alarmstt');
        //updating 'settings_ask_me_again' (TINYINT)
         $rec['SETTINGS_ASK_ME_AGAIN']=  $this->validate('settings_ask_me_again');
        //updating 'settings_quiet' (BOOLEAN)
         $rec['SETTINGS_QUIET']=  $this->validate('settings_quiet');
        //updating 'settings_no_hello' (BOOLEAN)
         $rec['SETTINGS_NO_HELLO']=  $this->validate('settings_no_hello');
        //updating 'settings_phrase_time_limit' (TINYINT)
         $rec['SETTINGS_PHRASE_TIME_LIMIT']=  $this->validate('settings_phrase_time_limit');
        //updating 'settings_chrome_mode' (TINYINT)
         $rec['SETTINGS_CHROME_MODE']=  $this->validate('settings_chrome_mode');
        //updating 'settings_chrome_choke' (BOOLEAN)
         $rec['SETTINGS_CHROME_CHOKE']=  $this->validate('settings_chrome_choke');
        //updating 'settings_chrome_alarmstt' (BOOLEAN)
         $rec['SETTINGS_CHROME_ALARMSTT']=  $this->validate('settings_chrome_alarmstt');
   }
/*
   //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  //updating '<%LANG_LINKED_PROPERTY%>' (varchar)
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
 
 */
  //UPDATING RECORD
   if($this->debug == 1) debmes('mpt edit befour ok');
   if ($ok) {
        if($this->debug == 1) debmes('mpt edit after ok');
        $tmp = SQLSelectOne('SELECT HOST, NAME FROM terminals where ID = ' . $rec['ID_TERMINAL']);
        //$ip = $tmp['HOST'];
        if ($rec['ID']) {
            SQLUpdate($table_name, $rec); // update sql
            if($this->debug == 1) debmes('mpt: ' . $tmp['HOST']);
        } else {
            if($this->debug == 1) debmes('mpt edit no recid insert');
            $new_rec=1;
            $rec['ID']=SQLInsert($table_name, $rec); // adding new record
        }
        $rec['IP_SERVER']=$_SERVER['SERVER_ADDR'];
        $senddata = json_encode($rec);
        $this->send_mpt('settings',$senddata,$tmp['HOST']);
        if($this->debug == 1) debmes('mpt edit send: ' . $senddata);
        $nmTerm = $tmp['NAME'];
        if($this->debug == 1) debmes('mpt edit add object: ' . $nmTerm);
        addClassObject('Terminals', $nmTerm);
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
