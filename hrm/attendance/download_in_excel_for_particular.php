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
    ///////////  Start: Fetch data by name and id.....
if($emp_id_inAjax != 'all'){
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
            } else { $table .="<td align='center'>&nbsp;</td></tr>"; }
               //////////   Start: Data collect for Excel file open
               $data_for_excelfile[] = $i;
               $data_for_excelfile[] = ucwords($emp_name_inAjax);
               $data_for_excelfile[] = $date_from_piut;
               $data_for_excelfile[] = $time_from_piut;
               $data_for_excelfile[] = $date_from_pout;
               $data_for_excelfile[] = $time_from_pout;
            if(!empty($pout)) {
               $data_for_excelfile[] = $hours.' : '.$minutes;
            } else { $data_for_excelfile[] = ''; }
               //////////   End: Data collect for Excel file open
    }
        $table .= "</table> </div>";
        $data = @$data_for_excelfile;
        if(@$piut == "") {
            echo "<div style=\"color:#444; font-weight:bold; font-size:15px; text-align:center; padding:10px 0px; background-color:#eee;\">"."No Data found between these days !!"."</div>";
        } else { 
            header("Content-Type: application/force-download");
            header("Content-disposition: attachment; filename=spreadsheet.xls");
            // Fix for crappy IE bug in download.
            header("Pragma: ");
            header("Cache-Control: ");
            //echo ' S.No '."\t".' Employee Name  '."\t".' In Date '."\t".' In Time '."\t".' Out Date '."\t".' Out Time '."\t".' Total Time '."\n";
            $counter =0;
            foreach($data as $row) {
               // echo  " ".$row ." " ."\t"; 
                $counter++;
                ///////////  Column is 7 so we use "$counter % 7".
                if(($counter % 7) == 0){
                    //echo "\n";
                }
            }
            echo $table;
        }
    ///////////  End: Fetch data by name and id.....
}exit;

?>