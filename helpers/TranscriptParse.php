<?php

// todo: rename to TextTranscript
// so $text_transcript-parse() works
// and class is named after object not action
class TranscriptParse {

	public $text;
	
	public function __construct($text) {
		$this->text = $text;
	}
	
	protected function Blockify()
	{
		$this->text = preg_split("/[\n]+/", $this->text);
	}

	protected function dialog() {
	
	$class = "para"; // default
	if( preg_match('/^[A-Z]+:/', $v) ) {
	    $class = "dialog";
	    }
	if( preg_match('/^[A-Z]+ +\(V\/O\):/', $v) ) {
	    $class = "voiceover";
	    }
	if( preg_match('/^[A-Z]+ +\(O\/C\):/', $v) ) {
	    $class = "offcamera";
	    }
	    $o .= '<span class="'. $class. '">'. $v ."</span>\n";
	}
	
	
	protected function Timeify() {

	/**
	 * Process time codes into links
	 *
	 */
	
	//$v = preg_replace('/(\dd:\dd)/', 'rtsp://video.ibiblio.org/folkstreams/video/the_music_district.rm?start=12:45&end=25:54', $v);
	
	
	

	}
	

	    
	    
	    
	
	protected function Noteify() {
	
	// Process note references in transcript text
	 if( preg_match('/(\[[0-9]+\])/', $v) ) {
	   
	    $v = preg_replace('/\[([0-9]+)\]/', '<A class=popup HREF="javascript:void(0);" onMouseOver="return overlib(INARRAY, \\1, STICKY, CAPTION, \'Film Note\');" onMouseOut="nd();">[\\1]</a>', $v);   
	    }
	                  
	    $o .= '<p class="'. $class. '">'. $v ."</p>\n";
	}
	} // end fn

} // end TranscriptParse
