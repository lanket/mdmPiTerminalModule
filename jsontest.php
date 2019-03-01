<?php

$json = file_get_contents('/modules/mdmPiTerminal/jsonfrommdp.json');

$tmp = json_decode($json);
print_r($tmp);
/*
foreach ($tmp as $kl1 => $val1) {
  if(is_array($val1))
  {
    foreach ($val1 as $kl2 => $val2) {
      if(is_array($val2))
      {

      }
      Else
      {
        print_r("$kl2 = $val2");
      }
    }
  }
  Else
  {
    print_r("$kl1 = $val1");
  }
}
*/
