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
    if(($rec['ID_TERMINAL'] == '' or $findduble['ID']) and $_POST['panel_whith_id_terminal'])
    {
        $out['ERR_ID_TERMINAL']=1;
        $ok=0;
    }
  // $postdata = $_POST;
  // unset($postdata["panel"],$postdata["view_mode"],$postdata["edit_mode"],$postdata["mode"],$postdata["id"]);
  // unset($postdata["view_mode"],$postdata["edit_mode"],$postdata["mode"]);

  // foreach ($postdata as $key => $value) {
  //     $data[$key] = $value;
  // }
   if($this->debug == 1) debmes('mpt edit befour ok');
   if ($ok) {
        if($this->debug == 1) debmes('mpt edit after ok');

        $sendData = array();
        if ($rec['ID']) {
          //       Update terminal
            if($this->debug == 1) debmes("mpt edit update : $nmTerm ! ip: " . $terminal['HOST']);
            if ($this->config['CREATE_CLASS'] == 1)
            {
                if($this->debug == 1) debmes("mpt edit update CreateClass=True : $terminal['NAME'] ! ip: " . $tmp['HOST']);
              // $rec['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
              // $rec['MAJORDOMO_OBJECT_NAME'] = $terminal['NAME'];
                $postdata['smarthome']['object_method'] = 'TerminalDataProcessing';
                $postdata['smarthome']['object_name'] = $terminal['NAME'];
            }






            // Стройим массив для построения интерфейса настроек терминала

                $navTabNumber = 0;
              // $settingOption = 0;
                foreach ($mapSettings as $keyMapPanel => $valueMapPanel) {
                  // Закладки в модуле
                  $out['NAV-TABS'][$navTabNumber]['NAV-TITLE'] = $keyMapPanel;
                  $out['NAV-TABS'][$navTabNumber]['NAV-DIV-ID'] = translitIt($keyMapPanel);
                  $out['NAV-TABS'][$navTabNumber]['NAV-N'] = $navTabNumber;
                // $out['NAV-TABS'][$navTabNumber]['BODY'] = "<li><a data-toggle='tab' href='#" . translitIt($keyMapPanel) . "' class='active'>[#TITLE#]</a></li>";
                // $out['NAV-TABS'][$navTabNumber]['BODY'] .= "<div id='" . translitIt($keyMapPanel) . "' class='tab-pane fade in'>";
                // $out['NAV-TABS'][$navTabNumber]['BODY'] .= '<form action="?" method="post" enctype="multipart/form-data" name="frm' . translitIt($keyMapPanel) . '" class="form-horizontal">';
                // if ($out['OK']) $out['NAV-TABS'][$navTabNumber]['BODY'] .= '<div class="alert alert-success">Сохранено</div>';
                // if ($out['ERR']) $out['NAV-TABS'][$navTabNumber]['BODY'] .= '<div class="alert alert-danger">Вы не выбрали терминал</div>';

                  $caseNumber = 0;
                  foreach ($valueMapPanel as $keyMapCase => $valueMapCase) {
                    // Секции
                    $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['CASE-NAME'] = $keyMapCase;
                    $settingNumber = 0;
                    foreach ($valueMapCase as $keyMapSetting => $valueMapSetting) {
                      // Опции
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-NAME'] = '$keyMapCase.$keyMapSetting';
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-TITLE'] = $valueMapSetting['name'];
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-DESC'] = $valueMapSetting['desc'];
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-TYPE'] = $valueMapSetting['type'];
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-DEFAULT'] = $valueMapSetting['default'];
                      $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-VALUE'] = $valueMapSetting['value'];
                      If($valueMapSetting['type'] == 'select')
                      {
                        foreach ($valueMapSetting['option'] as $keySettingOption => $valueSettingOption) {
                          $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-SELECT-OPTIONS'][]= array('SETTING-SELECT-OPTIONS-VALUE' => $keySettingOption, 'SETTING-SELECT-OPTION-TITLE' => $valueSettingOption );
                        // $settingOption += 1;
                        }
                      }
                      if($_POST['$keyMapCase.$keyMapSetting') $postdata[$keyMapCase][$keyMapSetting] = $valueMapSetting['value'];
                    // switch ($valueMapSetting['type'])
                    // {
                    //   case 'text':
                    //     $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-FORM'] = '<input type="text" class="form-control" name="' . $keyMapSetting . '" value="' . $valueMapSetting['default'] . '" id="' . $keyMapSetting .'">';
                    //     break;
                    //
                    //   case 'checkbox':
                    //     $out['NAV-TABS'][$navTabNumber]['CASES'][$caseNumber]['SETTINGS'][$settingNumber]['SETTING-FORM'] = '<input type="checkbox" name="' . $keyMapSetting . '" value="1"[#if SETTINGS_ALARMKWACTIVATED="1"#] checked[#endif#]>';
                    //     break;
                    //
                    //   case 'select':
                    //     // code...
                    //     break;
                    // }
                      $settingNumber += 1;
                    }
                    $caseNumber += 1;
                  }
                  $navTabNumber += 1;
                }
          // $settings = json_decode($rec['SETTINS']);
          //
          // foreach ($mapSettings as $keyMapPanel => $valueMapPanel) {
          //   foreach ($valueMapPanel as $keyMapCase => $valueMapCase) {
          //     foreach ($valueMapCase as $keyMapSetting => $valueMapSetting) {
          //       If(!$settings[$keyMapPanel][$keyMapCase][$keyMapSetting]) $settings[$keyMapPanel][$keyMapCase][$keyMapSetting] = $valueMapSetting['default'];
          //       //$setting = $postdata[$keyMapCase] . -'_' . $postdata[$keyMapSetting];
          //       If($postdata[$keyMapCase . -'_' . $keyMapSetting]) $settings[$keyMapPanel][$keyMapCase][$keyMapSetting] = $postdata[$keyMapCase . -'_' . $keyMapSetting];
          //     // foreach ($settings as $keys => $values) {
          //     //   foreach ($postdata as $keyp => $valuep) {
          //     //       $data[$key] = $value;
          //     //   }
          //     // }
          //     }
          //   }
          // }
          // $rec['SETTINS'] = json_encode($settings);
            SQLUpdate($table_name, $rec); // update sql
        } else
        {
// New terminal
            if($this->debug == 1) debmes('mpt edit no recid insert');
            $new_rec=1;
            if($this->debug == 1) debmes('mpt edit add object: ' . $terminal['NAME']);
            if ($this->config['CREATE_CLASS'] == 1)
            {
                $this->addObject($terminal['NAME']);
                $postdata['smarthome']['object_method'] = 'TerminalDataProcessing';
                $postdata['smarthome']['object_name'] = $terminal['NAME'];
              // $rec['MAJORDOMO_OBJECT_METHOD'] = 'TerminalDataProcessing';
              // $rec['MAJORDOMO_OBJECT_NAME'] = $terminal['NAME'];
            }
          // foreach ($mapSettings as $keyMapPanel => $valueMapPanel) {
          //   foreach ($valueMapPanel as $keyMapCase => $valueMapCase) {
          //     foreach ($valueMapCase as $keyMapSetting => $valueMapSetting) {
          //       If($settings[$keyMapPanel][$keyMapCase][$keyMapSetting]) $settings[$keyMapPanel][$keyMapCase][$keyMapSetting] = $valueMapSetting['default'];
          //     // foreach ($settings as $keys => $values) {
          //     //   foreach ($postdata as $keyp => $valuep) {
          //     //       $data[$key] = $value;
          //     //   }
          //     // }
          //     }
          //   }
          // }
          // $rec['SETTINS'] = json_encode($settings);
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
  // for($terminals_i=0;$terminals_i<$terminals_total;$terminals_i++) {
  //     $terminal_id_opt[$tmp[$terminals_i]['ID']]=$tmp[$terminals_i]['NAME'];
  // }
    for($i=0;$i<$terminals_total;$i++) {
        if ($rec['ID_TERMINAL']==$tmp[$i]['ID']) $tmp[$i]['SELECTED']=1;
        $terminal_id_opt[$tmp[$terminals_i]['ID']]=$tmp[$terminals_i]['NAME'];
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
