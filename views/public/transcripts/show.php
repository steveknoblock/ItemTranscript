<?php
$title = __('Transcript');
echo head(array('title'=>$title, 'bodyclass'=>'transcript'));
?>


<?php


print "<!-- Start Film Notes Include -->";
print "<script>";
print "<!--";
print $o;
print "alert(note[11]);\n";
print "//-->";
print "</script>";
print "<!-- End Film Notes Include -->";
?>


<script>
  $(function() {
    $( document ).tooltip();
  });
  </script>
  <style>
  note {
    display: inline-block;
    width: 5em;
  }
  </style>


<note title="This is a note">[1]</note>

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

debug('In show.php');
debug('Building note texts array');
$note_texts = array();
foreach ($notes as $note) {
	debug('building note content'.$note->text);
	//$o .= 'notes[' . $note->order . ']=' . '"' . $note->text .'";' . PHP_EOL;
	//$note_texts[] = '<div class="note_text">'. $note->text .'</div>'. PHP_EOL;
	$note_texts[] = $note->text;
}
debug('Finished building note texts array');
debug('  of '. count($note_texts) .' elements.');
$text_transcript = metadata('transcript', 'entry', array( 'no_filter' => true, 'no_escape'=> true));
//var_dump( $text_transcript ); 
$parser = new TranscriptParse($text_transcript);
$parser->setMap($note_texts);
$parser->parse();
$parser->process_notes();
print "<h3>Transcript</h3>";
print $parser->text;

//var_dump( $parser ); 


?></div>

<?php echo foot(); ?>