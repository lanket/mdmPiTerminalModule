<?php
/**
* модуль для голосового терминала
* @package project
* @author Ruslan <info@lanket.ru>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 23:05:08 [May 07, 2018])
*/
//
//
/**
* class json_@rpc
*
* @access private
*/

require(DIR_MODULES.$this->name.'/mpt_jsonLib.inc.php');


class mdmPiTerminal extends module {
/**
* mdmPiTerminal
*
* Module class constructor
*
* @access private
*/
function mdmPiTerminal() {
  $this->debug = 1;
  $this->name="mdmPiTerminal";
  $this->table_name="mpt";
  $this->title="MDM VoiceAssistant";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
  $this->service_port='7999';
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=1) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
    $this->getConfig();
    $out['CREATE_CLASS']=$this->config['CREATE_CLASS'];
    if ($this->view_mode=='update_settings') {
        global $create_class;
        $this->config['CREATE_CLASS']=$create_class;
        $this->saveConfig();
        $this->redirect("?");
    }
    global $sendCommand;
    if ($sendCommand)
    {
        header("HTTP/1.0: 200 OK\n");
        header('Content-Type: text/html; charset=utf-8');
        global $cmd;
        global $id;
        $tmp = SQLSelectOne("SELECT HOST FROM `terminals` inner join mpt on mpt.ID_TERMINAL = terminals.ID where mpt.ID =  $id");
        $target = $tmp['HOST'];
        $this->send_mpt('rec', $cmd, $target);
        echo "Ok";
        exit;
    }

 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='mpt' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_mpt') {
      $this->search_mpt($out);
  }
  if ($this->view_mode=='edit_mpt') {
   $this->edit_mpt($out, $this->id);
  }
  if ($this->view_mode=='delete_mpt') {
   $this->delete_mpt($this->id);
   $this->redirect("?");
  }
 }
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* mpt search
*
* @access public
*/
 function search_mpt(&$out) {
  require(DIR_MODULES.$this->name.'/mpt_search.inc.php');
 }
/**
* mpt edit/add
*
* @access public
*/
 function edit_mpt(&$out, $id) {

  require(DIR_MODULES.$this->name.'/mpt_edit.inc.php');
 }
/**
* mpt send data to terminal
*
* @access public
*/
 function send_mpt($command, $data, $target) {
  require(DIR_MODULES.$this->name.'/mpt_send.inc.php');
 }
 /**
 * Read map of settings from terminal
 *
 * @access public
 * return array
 */
  function read_mapSettingsMpt($target) {
   require(DIR_MODULES.$this->name.'/mpt_readMapSettings.inc.php');
  }
/**
* mpt delete record
*
* @access public
*/
 function delete_mpt($id) {
  $rec=SQLSelectOne("SELECT * FROM mpt WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM mpt WHERE ID='".$rec['ID']."'");
 }
 function propertySetHandle($object, $property, $value) {
   $this->getConfig();
   $table='mpt';
   $properties=SQLSelect("SELECT ID FROM $table WHERE LINKED_OBJECT LIKE '".DBSafe($object)."' AND LINKED_PROPERTY LIKE '".DBSafe($property)."'");
   $total=count($properties);
   if ($total) {
    for($i=0;$i<$total;$i++) {
     //to-do
    }
   }
 }
 function processSubscription($event, $details='') {
   require(DIR_MODULES.$this->name.'/mpt_processSubscription.inc.php');
 }

/**
* target destination name и еще всякого в IP
*
* Module installation routine
*
* @access private
*/

 function targetToIp($target='') {
    if(!$target) return null;
    if (preg_match('/^[\d\.]+$/',$target))
    {
        if($this->debug == 1) debmes('mpt ttIp 1: ' . $target);
        return $target;
    }
    else
    {
        $qry = "terminals.NAME LIKE '".DBSafe($target)."' OR terminals.TITLE LIKE '".DBSafe($target)."'";
        $res = SQLSelectOne("SELECT terminals.HOST FROM `mpt` inner join terminals on mpt.ID_TERMINAL = terminals.ID where $qry");
        if($this->debug == 1) debmes('mpt ttIp 2: ' . $res['HOST']);
        if(!res) return null;
        return $res['HOST'];
    }
 }

 /**
 * Рус <> Rus
 *
 * Module installation routine
 *
 * @access private
 */

 function translitIt($str){
     $tr = array(
         "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
         "Е"=>"E","Ё"=>"YO","Ж"=>"J","З"=>"Z","И"=>"I",
         "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
         "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
         "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"CH",
         "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
         "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
         "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"j",
         "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
         "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
         "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
         "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
         "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
     );
     return strtr($str,$tr);
 }


/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
    unsubscribeFromEvent($this->name, 'SAY');
    unsubscribeFromEvent($this->name, 'SAYTO');
    unsubscribeFromEvent($this->name, 'ASK');
    parent::install();
 }


 function addObject($nmTerm) {
    //global $create_class;
    if($this->debug == 1) debmes('mpt addObject create class : ' . $this->config['CREATE_CLASS']);
    if($this->debug == 1) debmes('mpt addObject nmTerm : ' . $nmTerm);
    if($this->debug == 1) debmes('mpt addObject after return ' . $nmTerm);
    addClass('Terminals');
    $phpcode = <<<EOD
/*
Параметры передаваемые с вызовом

    uptime: Аптайм терминала на момент регистрации вызова в секундах. Присутствует всегда.
    username: Если username задан то будет присылать его всегда.
    terminal: Если terminal задан то будет присылать его всегда.
    volume: Системная громкость терминала, -1 если не настроено или при ошибке чтения.
    mpd_volume: Громкость mpd, -1 при ошибке подключения или если громкость не регулируется.
    status: Причина вызова. Отсутствует при изменении громкости или если вызов произошел по таймеру.

Возможные значения status:

    start_record: Начало записи голоса (обычно после распознавания ключевого слова).
    stop_record: Окончание записи голоса.
    voice_activated: Терминал распознал ключевое слово. Только в chrome_mode = 0, в chrome_mode > 0 ему эквивалентен start_record.
    speech_recognized_success: Голосовая команда успешно распознана и обрабатывается.
    start_talking: Терминал начал говорить.
    stop_talking: Терминал закончил говорить.
    mpd_play, mpd_stop, mpd_pause: Статус mpd изменился на play, stop, pause.
    mpd_error: Ошибка получения статуса mpd.

Пример метода который сохраняет все параметры в свойства:
EOD;

    $phpcode .=chr(13) .'    foreach ($params as $param => $value) { '. chr(13) .'        $this->setProperty($param, $value);'. chr(13) .'    };'. chr(13) .'        */';
    addClassMethod('Terminals','GetDataFromTerminal',"");
    addClassMethod('Terminals','TerminalDataProcessing',$phpcode);
    if($this->debug == 1) debmes('mpt addObject befour add object ' . $nmTerm);
    addClassObject('Terminals',$nmTerm);
    if($this->debug == 1) debmes('mpt addObject after add object ' . $nmTerm);
 }

 /**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  SQLExec('DROP TABLE IF EXISTS mpt');
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data) {
/*
mpt -
*/
  $data = <<<EOD
 mpt: ID int(10) unsigned NOT NULL auto_increment
 mpt: ID_TERMINAL varchar(255) NOT NULL DEFAULT ''
 mpt: SETTINS TEXT NOT NULL DEFAULT ''
EOD;
  parent::dbInstall($data);
 }

 function validate($param)
    {
        global $$param;
        $value = $$param;
        if($this->debug == 1) $oldvalue = $value;
//        $setParam = !$_POST[$param];
        $param=strtoupper($param);
/*       $db = <<<EOD
        mpt: ID_TERMINAL varchar(255) NOT NULL DEFAULT ''
EOD;
        $data = explode("\n",  $db);
        foreach($data as $cur)
        {
            $curarray = explode(" ", $cur);
            if ($curarray[9] == $param)
            {
                if($curarray[10] == 'TINYINT' or substr($curarray[2],0,3) == 'INT' or $curarray[10] == 'BOOLEAN')
                {
                    if($this->debug == 1) debmes(">mpt edit validate int : $param = $oldvalue > $value ! isset = $setParam xxx " . isset($value));
//                    if(isset($value))
//                    {
//                        $value = str_replace("'","",$curarray[14]);
//                        if($this->debug == 1) debmes(">mpt edit validate int != 0 : $param = $oldvalue > $value" );
//                    }
                    $value=(int)$value;
                }
                else
                {
                    if(!$value) $value = str_replace("'","",$curarray[14]);
                }
            }
        }
        if($this->debug == 1) debmes("mpt edit validate : $param = $oldvalue > $value" );
        global $postdata;
        $postdata[$param] = $value;
        */

        return $value;
    }
   // --------------------------------------------------------------------
}

/*
*
* TW9kdWxlIGNyZWF0ZWQgTWF5IDA3LCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
