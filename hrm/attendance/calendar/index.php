<?php require_once('calendar/classes/tc_calendar.php'); ?>
<html>
<head>
<link href="calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="calendar/calendar.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php 
$a =  @$_POST['date3'];
$b =  @$_POST['date4'];
echo $a;
echo $b;
?>
<form name="form1" method="post" action="">
    <div style="float: left;">
      <?php
            $thisweek = date('W');
            $thisyear = date('Y');
            $dayTimes = getDaysInWeek($thisweek, $thisyear);
            //----------------------------------------
            $date1 = date('Y-m-d', $dayTimes[0]);
            $date2 = date('Y-m-d', $dayTimes[(sizeof($dayTimes)-1)]);

            function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {
              // Count from '0104' because January 4th is always in week 1
              // (according to ISO 8601).
              $time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
              // Get the time of the first day of the week
              $dayTime = strtotime('-' . (date('w', $time) - $dayStart) . ' days', $time);
              // Get the times of days 0 -> 6
              $dayTimes = array ();
              for ($i = 0; $i < 7; ++$i) {
                $dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
              }
              // Return timestamps for mon-sun.
              return $dayTimes;
            }
          $myCalendar = new tc_calendar("date3", true, false);
          $myCalendar->setIcon("calendar/images/iconCalendar.gif");
          $myCalendar->setDate(date('d', strtotime($date1)), date('m', strtotime($date1)), date('Y', strtotime($date1)));
          $myCalendar->setPath("calendar/");
          $myCalendar->setYearInterval(1970, 2020);
          //$myCalendar->dateAllow('2009-02-20', "", false);
          $myCalendar->setAlignment('left', 'bottom');
          $myCalendar->setDatePair('date3', 'date4', $date2);
          //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
          $myCalendar->writeScript();
          ?>
    </div>
  </div>
  <div style="float: left;">
    <div style="float: left; padding-left: 3px; padding-right: 3px; line-height: 18px;">to</div>
    <div style="float: left;">
      <?php
          $myCalendar = new tc_calendar("date4", true, false);
          $myCalendar->setIcon("calendar/images/iconCalendar.gif");
          $myCalendar->setDate(date('d', strtotime($date2)), date('m', strtotime($date2)), date('Y', strtotime($date2)));
          $myCalendar->setPath("calendar/");
          $myCalendar->setYearInterval(1970, 2020);
          //$myCalendar->dateAllow("", '2009-11-03', false);
          $myCalendar->setAlignment('left', 'bottom');
          $myCalendar->setDatePair('date3', 'date4', $date1);
          //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
          $myCalendar->writeScript();
          ?>
    </div>
  </div>
  <p>
    <!-- <input type="button" name="button2" id="button2" value="Check the value" onClick="javascript:alert('Date select from '+this.form.date3.value+' to '+this.form.date4.value);"> -->
    <input type="submit" name="submit" value="Submit" >
 </p>
</form>
</body>
</html>
