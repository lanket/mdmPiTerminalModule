<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='mpt';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if($rec['ID_TERMINAL'])  
  {
    $tmp = SQLSelectOne('SELECT HOST , NAME FROM terminals where ID = ' . $rec['ID_TERMINAL']);
    $out['IP_TERMINAL'] = $tmp['HOST'];
  }
  
  if ($this->mode=='update') {
   $ok=1;
    //options for 'ID_TERMINAL' (select)
    global $id_terminal;
    $rec['ID_TERMINAL'] = $id_terminal;
    $qry = '';
    if($rec['ID']) $qry = " and ID <> " . $rec['ID'];
    $findduble=SQLSelectOne("SELECT * FROM $table_name WHERE ID_TERMINAL='$id_terminal' $qry");
    // chech not empy terminal and mast no dubles
    if(($rec['ID_TERMINAL'] == '' or $findduble['ID']) and !$_POST['panel_voice'] and !$_POST['panel_admin'])
    {
        $out['ERR_ID_TERMINAL']=1;
        $ok=0;
    }

    global $postdata;
    $postdata = array();
    
        
    if($_POST['panel_voice'])
   {
        if($this->debug == 1) debmes("mpt edit panel_voice" );
        //updating 'snowboy_token' (varchar)
         $rec['SNOWBOY_TOKEN']=  $this->validate('snowboy_token');
        //updating 'settings_sensitivity' (varchar)
         $rec['SETTINGS_SENSITIVITY']=  $this->validate('settings_sensitivity');
        //updating 'settings_providertts' (varchar)
         $rec['SETTINGS_PROVIDERTTS']=  $this->validate('settings_providertts');
        //updating 'cache_tts_priority' (varchar)
         $rec['CACHE_TTS_PRIORITY']= $this->validate('cache_tts_priority');
        //updating 'cache_tts_size' (varchar)
         $rec['CACHE_TTS_SIZE']= $this->validate('cache_tts_size');
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
        //updating 'pocketsphinx0rest_server' (varchar)
         $rec['POCKETSPHINX0REST_SERVER']= $this->validate('pocketsphinx0rest_server');
   }
   else if($_POST['panel_admin'])
   {
        //updating 'update_interval' (varchar)
         $rec['UPDATE_INTERVAL']= $this->validate('update_interval');
        //updating 'update_turnoff' (varchar)
         $rec['UPDATE_TURNOFF']= $this->validate('update_turnoff');
        //updating 'update_fallback' (varchar)
         $rec['UPDATE_FALLBACK']= $this->validate('update_fallback');
        //updating 'update_pip' (varchar)
         $rec['UPDATE_PIP']= $this->validate('update_pip');
        //updating 'update_apt' (varchar)
         $rec['UPDATE_APT']= $this->validate('update_apt');
   }
   else 
   {
        if($this->debug == 1) debmes("mpt edit not panel_voice" );
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
        //updating 'majordomo_object_name' (varchar)
         $rec['MAJORDOMO_OBJECT_NAME']= $this->validate('majordomo_object_name');
        //updating 'majordomo_object_method' (varchar)
         $rec['MAJORDOMO_OBJECT_METHOD']= $this->validate('majordomo_object_method');
        //updating 'majordomo_heartbeat_timeout' (varchar)
         $rec['MAJORDOMO_HEARTBEAT_TIMEOUT']= $this->validate('majordomo_heartbeat_timeout');
        //updating 'proxy_enable' (varchar)
         $rec['PROXY_ENABLE']= $this->validate('proxy_enable');
        //updating 'proxy_monkey_patching' (varchar)
         $rec['PROXY_MONKEY_PATCHING']= $this->validate('proxy_monkey_patching');
        //updating 'proxy_proxy' (varchar)
         $rec['PROXY_PROXY']= $this->validate('proxy_proxy');
        //updating 'mpd_control' (varchar)
         $rec['MPD_CONTROL']= $this->validate('mpd_control');
        //updating 'mpd_ip' (varchar)
         $rec['MPD_IP']= $this->validate('mpd_ip');
        //updating 'mpd_port' (varchar)
         $rec['MPD_PORT']= $this->validate('mpd_port');
        //updating 'mpd_pause' (varchar)
         $rec['MPD_PAUSE']= $this->validate('mpd_pause');
        //updating 'mpd_smoothly' (varchar)
         $rec['MPD_SMOOTHLY']= $this->validate('mpd_smoothly');
        //updating 'mpd_quieter' (varchar)
         $rec['MPD_QUIETER']= $this->validate('mpd_quieter');
        //updating 'mpd_wait_resume' (varchar)
         $rec['MPD_WAIT_RESUME']= $this->validate('mpd_wait_resume');
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
                $rec['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
                $rec['MAJORDOMO_OBJECT_NAME'] = $nmTerm;
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
    $tmp=SQLSelect("SELECT ID, TITLE, NAME, HOST, if(IS_ONLINE = 1, 'Online', 'Offline') as ONLINE FROM terminals WHERE TTS_TYPE = 'majordroid' OR MAJORDROID_API = 1 ORDER BY TITLE");
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
