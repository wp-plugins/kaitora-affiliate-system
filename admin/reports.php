<div class="wrap">

<h2>Kaitora Affiliate Manager - Reports</h2>
<br /><p>

<?PHP

    if($_POST['kdals_empty_confirm']){
        
        $sql = "UPDATE ".$kdalstblname." SET clicks=0";
        $kdals_reset = $wpdb->query($sql);

        echo '<B>Successfully reset click data!</B><BR /><BR />';
        
    }

    if($_POST['kdals_empty_report']){
        
        echo '<p>
        
        <form method="post" name="empty_confirm_kdals" action="">
        
        Are you sure you wish to empty all the click data? Once emptied, it cannot be restored! <BR />

        <input type="submit" name="kdals_empty_confirm" class="button-primary" value="Confirm Data Reset!" />

        </form> </p><BR />';
        
    } 

$kdals_report_sql = "SELECT id,keyword,clicks FROM ".$kdalstblname."";
$current_report = $wpdb->get_results($kdals_report_sql);

if(count($current_report) == 0){
    echo '<B>There are currently no keywords being replaced!</B>';
} ELSE {
    
    echo '
    <table class="widefat"> 
    <thead>  
    <tr>
    <th>Keyword</th>  
    <th>Clicks</th>  
    </tr>  
    </thead>  
    <tfoot>  
    <tr>  
    <th>Keyword</th>  
    <th>Clicks</th>  
    </tr>  
    </tfoot>
    <tbody>  
    ';
    

foreach($current_report as $kdals_rep){
    echo ' 
<td>'.$kdals_rep->keyword.'</td>  
<td>'.$kdals_rep->clicks.'</td>  
</tr> ';
}  

echo '
</tbody>  
</table> 
' ;

}
?> 

</p>

<form method="post" name="empty_reports_kdals" action="">

<input type="submit" name="kdals_empty_report" class="button-primary" value="Reset Click Report Data" />

</form>

</div>
