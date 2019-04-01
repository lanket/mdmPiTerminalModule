<<?php

$calendar_categories=SQLSelect("SELECT ID,TITLE,ICON FROM calendar_categories ORDER BY PRIORITY DESC");
  foreach($calendar_categories as $cc=>$ccvalue) {
     $events_past=SQLSelect("SELECT *, (TO_DAYS(DUE)-TO_DAYS(NOW())) as AGE FROM calendar_events WHERE TO_DAYS(DUE)<TO_DAYS(NOW()) AND IS_NODATE=0 AND IS_TASK=1 AND IS_DONE=0 and CALENDAR_CATEGORY_ID=" . $ccvalue['ID'] . " ORDER BY IS_TASK DESC, AGE");
    foreach($events_past as $eventsKey=>$eventsValue) {
     $calendar_categories[$cc]['EVENTS_PAST'][]=$eventsValue;
   }
 }
  $out['CALENDAR_CATEGORIES']=$calendar_categories;

 ?>




 [#begin CALENDAR_CATEGORIES#]

    <h1 class="title" style="padding-top: 15px;">[#if ICON!=""#]<img style="width: 40px;" src="<#ROOTHTML#>cms/calendar/[#ICON#]">[#else#]<img style="width: 40px;" src="<#ROOTHTML#>cms/calendar/default.gif">[#endif ICON#] [#TITLE#]</h1>
    <table border="0">
    [#if EVENTS_PAST#]
    <h2 style="color: #ff0000; font-size: 18px;margin-top: 5px;"><#LANG_PAST_DUE#></h2>
    <table class="paddingleft" border="0" style="margin-bottom: 15px;">
         [#begin EVENTS_PAST#]
         <tr>
          <td valign="top">[#if IS_TASK="1"#]<img style="height: 25px;" src="../cms/calendar/task.svg" title="??????" alt="??????">[#else#]<img style="height: 25px;" src="../cms/calendar/notification.svg" title="???????" alt="???????">[#endif#]</td>
          <td id="yestask" width="100%"><span id="task_title[#ID#]">[#TITLE#] (?????? [#AGE#] [#DAYS#])</span> <div class="menu"><a href="?view_mode=edit&id=[#ID#]"><img style="height: 15px;" src="../cms/calendar/settings.svg" /></a>[#if IS_TASK="1"#] | <input type="checkbox" name="task[#ID#]" class="tasks" value="[#ID#]" data-role="none">[#endif#]</div> [#if NOTES=""#][#else#]<span style="color: grey; font-style: italic;"> — [#NOTES#]</span>[#endif#]</td>
          <!--td><a style="font-size: 13px;" href="?view_mode=edit&id=[#ID#]"><#LANG_EDIT#></a></td-->
         </tr>
         [#end EVENTS_PAST#]
    </table>
    [#endif EVENTS_PAST#]
[#end CALENDAR_CATEGORIES#]
