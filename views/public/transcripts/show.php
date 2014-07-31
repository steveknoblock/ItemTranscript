<?php
$title = __('Transcript');
echo head(array('title'=>$title, 'bodyclass'=>'transcript'));
?>


<?php

foreach ($notes as $note) {
	debug('building js content');
	$o .= 'notes[' . $note->order . ']=' . '"' . $note->text .'";' . PHP_EOL;
}

print "<!-- Start Film Notes Include -->";
print "<script>";
print "<!--";
print $o;
print "//-->";
print "</script>";
print "<!-- End Film Notes Include -->";
?>


<script>
  $(function() {
    //$( document ).tooltip({ content: "Awesome title!" });
    
    $( document ).tooltip( "option", "items", "notes[nid]" );
    
  });
  </script>
  <style>
  note {
    display: inline-block;
    width: 5em;
  }
  </style>
  
<a href="http://jqueryui.com/themeroller/" title="ThemeRoller: jQuery UI&apos;s theme builder application">ThemeRoller</a>
will also style tooltips accordingly.</p>

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

$text_transcript = metadata('transcript', 'entry', array( 'no_filter' => true, 'no_escape'=> true));
//var_dump( $text_transcript ); 

$parser = new TranscriptParse($text_transcript);
$parser->parse();
print "<h3>Transcript</h3>";
print $parser->text;
//var_dump( $parser ); 


?></div>

<?php echo foot(); ?>