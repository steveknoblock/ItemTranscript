<?php
$title = __('Transcript');
echo head(array('title'=>$title, 'bodyclass'=>'transcript'));
?>
<style>
div.dialog, div.voiceover, div.offcamera { margin-bottom: 1.3em }
</style>
<?php
/*
print '<pre>';
print_r($this->transcript);
print '</pre>';
*/
?>

<div>Title: <?php echo metadata('transcript', 'title'); ?></div>
<div>Description: <?php echo metadata('transcript', 'description'); ?></div>

<div><?php

$text_transcript = metadata('transcript', 'entry', array( 'no_filter' => true, 'no_escape'=> true));
//var_dump( $text_transcript ); 

$parser = new TranscriptParse($text_transcript);
$parser->parse();
print "<h3>Transcript</h3>";
print $parser->text;
//var_dump( $parser ); 


?></div>

<?php echo foot(); ?>