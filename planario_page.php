<?php


$results = planario_get_event_all("23");
$test = "hello my friend";
$count = count($results);

if ($count == 0 ){
    $count = 'No';
};
?>
Results are In:</br>

<?php echo $count ; ?>
<?php echo _n(' result', ' results', "0"); ?>