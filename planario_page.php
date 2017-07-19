<?php


$results = planario_get_event_all("23");
$count = count($results);
$html_table = planario_build_html_table($results);

//Show Number or results
if ($count == 0 ){
    $count = 'No';
};
echo $count;
print(_n(' result', ' results', "0") . " returned."); 
?>
</br>
<?php echo $html_table ;?>
</br>




    