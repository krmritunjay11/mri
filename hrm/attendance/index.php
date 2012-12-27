<?php
require_once("db_connection.php");
if($_SESSION['user'] != 1) {
    header("location:../index.php"); exit;
}
require_once('calendar/calendar/classes/tc_calendar.php');
?>
<script type="text/javascript">
    function download_in_excel_for_all() {
        var emp_selected = document.form1.emp_select.value;
        var start_date = document.form1.date3.value;
        var end_date = document.form1.date4.value;
        window.location = "download_in_excel_for_all.php?q="+emp_selected+"&r="+start_date+"&s="+end_date;
    }
    function download_in_excel_for_particular() {
        var emp_selected = document.form1.emp_select.value;
        var start_date = document.form1.date3.value;
        var end_date = document.form1.date4.value;
        window.location = "download_in_excel_for_particular.php?q="+emp_selected+"&r="+start_date+"&s="+end_date;
    }
</script>
<?php 
//////////          Start Search
$where = ''; $employee_number = '';
$emp_fullname = '';
$emp_fname = ''; $emp_lname = '';
if(isset($_POST['empfname']) && !empty($_POST['empfname'])){
    $empfname = $_POST['empfname'];
    $where .= " `emp_firstname` LIKE '%$empfname%' AND";
    $emplname = $_POST['emplname'];
    $where .= " `emp_lastname` LIKE '%$emplname%' AND";
    $where = rtrim( $where,'AND');
    $where  = ' WHERE '.$where;
    $searchquery = "SELECT * FROM `hs_hr_employee` $where";
    $mysql_result = mysql_query($searchquery) or die(mysql_error().'Unable to fetch search');
    while($ans = mysql_fetch_assoc($mysql_result)) {
        $emp_fname = $ans['emp_firstname'];
        $emp_lname = $ans['emp_lastname'];
        $employee_number = $ans['emp_number'];
        $emp_fullname[$employee_number] = $emp_fname." ".$emp_lname;
    }
}
//////////          End Search
?>
<html>
<head>
<title>OrangeHRM - New Level of HR Management</title>
<link href="calendar/calendar/calendar.css" rel="stylesheet" type="text/css" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="calendar/calendar/calendar.js"></script>
<script language="javascript" src="main1.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>
    function showUser() {
        var emp_selected = document.form1.emp_select.value;
        var start_date = document.form1.date3.value;
        var end_date = document.form1.date4.value;
        if (emp_selected=="")
        {
            document.getElementById("txtHint").innerHTML="";
            return;
        } 
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("get","viewAttenAjax.php?q="+emp_selected+"&r="+start_date+"&s="+end_date,true);
        xmlhttp.send();
    }
</script>
</head>
<body>
    <form name="serach" action="" method="post" onSubmit="return validate()" id="form">
    	<div class="logo"><img src="images/orange3.png"></div>
        <div class="navbar"><b><a href="../index.php" style="text-decoration:none;" >Home</a></b>
        </div>
        <div class="maincontent">
           <span class="main_heading"> <h2>Employee Search</h2></span>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="padding:10px 0 0 10px;">
                	<label>First Name :</label> 
                	<input type="text" name="empfname" value="" >
                    </td>
                <td style="padding-top:10px;">
                	<label>Last Name : </label>
                	<input type="text" name="emplname" value="" >
                    </td>
                <td style="padding:10px 0 0 5px;"><input type="submit"value="Search"></td>
            </tr>
        </table>
    </form>
    <form name="form1" method="post" action="" id="form1" style="padding-top:15px;">
        <div style="float: left;">
        <div style="float: left; padding:0 15px; line-height: 18px; font-size:15px;">From :</div>
        <div style="float: left; padding-left:19px;">
            <?php
            ///////////////    Start:   Calendar
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
              $myCalendar->setIcon("calendar/calendar/images/iconCalendar.gif");
              $myCalendar->setDate(date('d', strtotime($date1)), date('m', strtotime($date1)), date('Y', strtotime($date1)));
              $myCalendar->setPath("calendar/calendar/");
              //$myCalendar->setYearInterval(1970, 2020);
              //$myCalendar->dateAllow('2009-02-20', "", false);
              $myCalendar->setAlignment('left', 'bottom');
              $myCalendar->setDatePair('date3', 'date4', $date2);
              //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
              $myCalendar->writeScript();
              ?>
        </div>
        </div>
        <div style="float: left;">
        <div style="float: left; padding:0 15px; line-height: 18px; font-size:15px;">To </div>
        <div style="float: left; padding-right:12px;">
            <?php
                $myCalendar = new tc_calendar("date4", true, false);
                $myCalendar->setIcon("calendar/calendar/images/iconCalendar.gif");
                $myCalendar->setDate(date('d', strtotime($date2)), date('m', strtotime($date2)), date('Y', strtotime($date2)));
                $myCalendar->setPath("calendar/calendar/");
                //$myCalendar->setYearInterval(1970, 2020);
                //$myCalendar->dateAllow("", '2009-11-03', false);
                $myCalendar->setAlignment('left', 'bottom');
                $myCalendar->setDatePair('date3', 'date4', $date1);
                //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
                $myCalendar->writeScript();
                /////////////////    Start:   Calendar
            ?>
        </div>
        </div>
        <span id="select_name"><select name="emp_select" onChange="showUser()">
            <?php echo "<option value=\"\">Select Employee</option>";
                echo "<option value=\"all@@@@allemployees\">All&nbsp; Employees</option>";
                foreach($emp_fullname as $emp_ids => $emp_names) {
                echo "<option value=\"".$emp_ids."@@@@".$emp_names."\">".ucwords($emp_names)."</option>";
            } ?>
        </select></span>
        <p>
        <!-- <input type="button" name="button2" id="button2" value="Employee Details" onclick="showUser()"> -->
        <!-- <input type="submit" name="submit" value="Submit" > -->
        </p><br />
        <?php 
        if(isset($_POST['empfname']) && !empty($_POST['empfname'])){
            if(!empty($employee_number)) {
                echo '<b style="font-size:12px; color:#555555;">'."<pre>"."             Please select date and employee name."."</pre>".'</b>';
            } else {
                echo '<b style="font-size:12px; color:#555555;">'."<pre>"."             No employees found in database."."</pre>".'</b>';
            }
        } ?>
    </form></div>
    <div class="employee_timetable" id="txtHint"><b>Employee info will be listed here.</b></div>
    <div class="copyright"><a href="http://www.orangehrm.com/" target="_blank">OrangeHRM</a> ver 2.7 © OrangeHRM Inc. 2005 - 2012 All rights reserved. </div>
</body>
</html>

