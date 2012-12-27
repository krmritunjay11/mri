<?php
///////   Start: get from index page
$q=@$_GET["q"]; $r=@$_GET["r"]; $s=@$_GET["s"];
$selected_emp = stristr($q,'@@@@');
$emp_name_inAjax = trim($selected_emp, '@@@@');
$emp_id_inAjax = stristr($q,'@@@@',true);
$start_date = $r;
$end_date = $s;
$end_date .= $end_date." 23:59:59";
$end_date;
/////////   End: get from index page
require("db_connection.php");
$i = 0; $where = ''; $symbol = ">="; $symbol2 = "<=";
/////////    Start: Fetch data for all
if($emp_id_inAjax == 'all'){
    $where .= " `punch_in_user_time` $symbol '$start_date' AND";
    $where .= " `punch_in_user_time` $symbol2 '$end_date' AND";
    $where = rtrim( $where,'AND');
    $where  = ' WHERE '.$where;
    $query = "SELECT * FROM `ohrm_attendance_record` $where ORDER BY `punch_in_user_time` ASC";
    $result = mysql_query($query) or die(mysql_error());
    $table='';
    $table ="<div id='time_table'><table border='0' width='100%'  cellpadding='0' cellspacing='0' bgcolor='white'><tr bgcolor='#FAD163'><th>S.No</th>
        <th>Employee Name</th>
        <th>In Date</th><th>In Time</th>
        <th>Out Date</th><th>Out Time</th><th>Total Time<br/>Hr/Min</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $ids =  $row['employee_id']; 
        $query1 = "SELECT * FROM `hs_hr_employee` WHERE emp_number=".$ids;//." ORDER BY `emp_firstname` ASC";
        $result1 = mysql_query($query1) or die(mysql_error()); 
            while($rows = mysql_fetch_array($result1)) {
                if ($i++ % 2 == 0) {
                $table .= "<tr bgcolor=\"#ffffff\">";
                } else {
                $table .= "<tr bgcolor=\"#eeeeee\">";
                }
                    $emp_fname1 = $rows['emp_firstname'];
                    $emp_lname1 = $rows['emp_lastname'];
                    $emp_fullname1 = ucwords($emp_fname1." ".$emp_lname1);
                
                $table .="<td align='center'>".$i."</td>";
                $table .="<td align='center'>".$emp_fullname1."</td>";
                    $piut =  $row['punch_in_user_time'];
                    $date_from_piut = stristr($piut,' ',true);
                $table .="<td align='center'>".$date_from_piut."</td>";
                    $space_time_from_piut = stristr($piut,' ');
                    $time_from_piut = trim($space_time_from_piut, ' ');
                $table .="<td align='center'>".$time_from_piut."</td>";
                    $pout =  $row['punch_out_user_time'];
                    $date_from_pout = stristr($pout,' ',true);
                $table .="<td align='center'>".$date_from_pout."</td>";
                    $space_time_from_pout = stristr($pout,' ');
                    $time_from_pout = trim($space_time_from_pout, ' ');
                $table .="<td align='center'>".$time_from_pout."</td>";
                if(!empty($pout)) {
                    $diff_time_in_seconds = strtotime($pout) - strtotime($piut); 
                    $hours = floor($diff_time_in_seconds/3600);
                    $remaining_time_in_seconds = $diff_time_in_seconds%3600;
                    $minutes = floor($remaining_time_in_seconds/60);
                $table .="<td align='center'>".$hours.' : '.$minutes;"</td></tr>";
                } else { 
                    $table .="<td align='center'>&nbsp;</td></tr>";
                }
            }
    }
    $table .= "</table> </div>";
    if(@$piut == "") {
        echo "<div style=\"color:#444; font-weight:bold; font-size:15px; text-align:center; padding:10px 0px; background-color:#eee;\">"."No Data found between these days !!"."</div>";
    } else {
        echo "<input type='button' name='button' onclick='download_in_excel_for_all();' value='Download All Records'><br><br>";
        echo $table;
    }
/////////    End: Fetch data for all
} else {
///////////  Start: Fetch data by name and id.....
    $where .= " `employee_id` = '$emp_id_inAjax' AND";
    $where .= " `punch_in_user_time` $symbol '$start_date' AND";
    $where .= " `punch_in_user_time` $symbol2 '$end_date' AND";
    $where = rtrim( $where,'AND');
    $where  = ' WHERE '.$where;
    $query = "SELECT * FROM `ohrm_attendance_record` $where ORDER BY `punch_in_user_time` ASC";
    $result = mysql_query($query) or die(mysql_error());
    $table='';
    $table ="<div id='time_table'><table border='0' width='100%'  cellpadding='0' cellspacing='0' bgcolor='white'><tr bgcolor='#FAD163'><th>S.No</th>
        <th>Employee Name</th>
        <th>In Date</th><th>In Time</th>
        <th>Out Date</th><th>Out Time</th><th>Total Time<br/>Hr/Min</th></tr>";
    while($row = mysql_fetch_array($result)) {
        if ($i++ % 2 == 0) {
        $table .= "<tr bgcolor=\"#ffffff\">";
        } else {
        $table .= "<tr bgcolor=\"#eeeeee\">";
        }
            $table .="<td align='center'>".$i."</td>";
            $table .="<td align='center'>".ucwords($emp_name_inAjax)."</td>";
                $piut =  $row['punch_in_user_time'];
                $date_from_piut = stristr($piut,' ',true);
            $table .="<td align='center'>".$date_from_piut."</td>";
                $space_time_from_piut = stristr($piut,' ');
                $time_from_piut = trim($space_time_from_piut, ' ');
            $table .="<td align='center'>".$time_from_piut."</td>";
                $pout =  $row['punch_out_user_time'];
                $date_from_pout = stristr($pout,' ',true);
            $table .="<td align='center'>".$date_from_pout."</td>";
                $space_time_from_pout = stristr($pout,' ');
                $time_from_pout = trim($space_time_from_pout, ' ');
            $table .="<td align='center'>".$time_from_pout."</td>";
            if(!empty($pout)) {
                $diff_time_in_seconds = strtotime($pout) - strtotime($piut); 
                $hours = floor($diff_time_in_seconds/3600);
                $remaining_time_in_seconds = $diff_time_in_seconds%3600;
                $minutes = floor($remaining_time_in_seconds/60);
            $table .="<td align='center'>".$hours.' : '.$minutes;"</td></tr>";
            } else { 
                $table .="<td align='center'>&nbsp;</td></tr>"; 
            }
    }
    $table .= "</table> </div>";
    if(@$piut == "") {
        echo "<div style=\"color:#444; font-weight:bold; font-size:15px; text-align:center; padding:10px 0px; background-color:#eee;\">"."No Data found between these days !!"."</div>";
    } else { 
        echo "<input type='submit' name='button' onclick='download_in_excel_for_particular();' value='Download Record'><br><br>";
        echo $table;
    }
}  ///////////  End: Fetch data by name and id.....
    
?>