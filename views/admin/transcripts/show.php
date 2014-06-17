<?php
$title = __('Transcript');
echo head(array('title'=>$title, 'bodyclass'=>'transcript'));
?>

<?php
/*
print '<pre>';
print_r($this->transcript);
print '</pre>';
*/
echo metadata('transcript', 'title');
//echo metadata('transcript', 'description');
//echo metadata('transcript', 'entry');
?>

<?php echo foot(); ?>