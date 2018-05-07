<?php
/**
* РўРµСЂРјРёРЅР°Р» РіРѕР»РѕСЃРѕРІРѕРіРѕ СѓРїСЂР°РІР»РµРЅРёСЏ 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 23:05:08 [May 07, 2018])
*/
//
//
class mdmPiTerminal extends module {
/**
* mdmPiTerminal
*
* Module class constructor
*
* @access private
*/
function mdmPiTerminal() {
  $this->name="mdmPiTerminal";
  $this->title="МажорКолонка";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
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
  if ($event=='SAY') {
   $level=$details['level'];
   $message=$details['message'];
   debmes('mpt say ' . $message . '; level = ' . $level);
  }
  if ($event=='ASK') {
   $message=$details['prompt'];
   $target=$details['target'];
   debmes('mpt ask ' . $message . '; target = ' . $target);
  }
  if ($event=='SAYTO') {
   $level=$details['level'];
   $message=$details['message'];
   $destination=$details['destination'];
   debmes('mpt say ' . $message . '; level = ' . $level . '; to = ' . $destination);
  }
 }
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  subscribeToEvent($this->name, 'SAY');
  subscribeToEvent($this->name, 'SAYTO');
  subscribeToEvent($this->name, 'ASK');
  parent::install();
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
 mpt: TITLE varchar(100) NOT NULL DEFAULT ''
 mpt: NAME varchar(255) NOT NULL DEFAULT ''
 mpt: LINKEDROOM varchar(255) NOT NULL DEFAULT ''
 mpt: IP varchar(255) NOT NULL DEFAULT ''
 mpt: PROVIDERTTS varchar(255) NOT NULL DEFAULT ''
 mpt: APIKEYTTS varchar(255) NOT NULL DEFAULT ''
 mpt: PROVIDERSTT varchar(255) NOT NULL DEFAULT ''
 mpt: APIKEYSTT varchar(255) NOT NULL DEFAULT ''
 mpt: SENSITIVITY varchar(255) NOT NULL DEFAULT ''
 mpt: ALARMKWACTIVATED varchar(255) NOT NULL DEFAULT ''
 mpt: ALARMTTS varchar(255) NOT NULL DEFAULT ''
 mpt: ALARMSTT varchar(255) NOT NULL DEFAULT ''
 mpt: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 mpt: LINKED_PROPERTY varchar(100) NOT NULL DEFAULT ''
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWF5IDA3LCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
