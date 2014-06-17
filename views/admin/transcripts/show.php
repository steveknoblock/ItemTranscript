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
?>

<div>Title: <?php echo metadata('transcript', 'title'); ?></div>
<div>Description: <?php echo metadata('transcript', 'description'); ?></div>
<div><pre><?php echo metadata('transcript', 'entry'); ?></pre></div>

<?php echo foot(); ?>