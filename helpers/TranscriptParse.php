<?php

/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package TextTranscript
 */

/**
 * Implements SpeakUp text transcript markup language.
 *
 * @package ItemTranscript
 */
 
// todo: rename to TextTranscript
// so $text_transcript-parse() works
// and class is named after object not action
// create beforeParse and afterParse callbacks
class TranscriptParse {

	public $text;
	protected $options;
	protected $map;
	
	protected $textBlocks;
	
	public function __construct($text)
	{
		//debug('TranscriptParse::__contstruct()');
		//debug('Text: '. $text);
		$this->text = $text;
		//debug($this->text);
		//debug('Exiting TranscriptParse::__contstruct()');
	}
	
	public function parse() {
		//debug('TranscriptParse::parse()');
		//debug('Calling TranscriptParse::_dialog()');
		$this->Blockify();
		$o = '';
		foreach ( $this->textBlocks as $k => $v ) {
		
			// common mistakes
			$v = preg_replace('/\(V\/0\):/', '(V/O):', $v);
			$v = preg_replace('/\(0\/C\)/', '(O/C)', $v);
	
			// strip newlines, should any remain
			$v = preg_replace('/\n/', '', $v);
	
			$class = "line"; // default
			if(preg_match('/^[A-Z]+:/', $v)) {
				//debug('matches dialog');
				$class = "dialog";
			}
			if(preg_match('/^[A-Z]+ +\(V\/O\):/', $v)) {
				//debug('matches voiceover');
				$class = "voiceover";
			}
			if(preg_match('/^[A-Z]+ +\(O\/C\):/', $v)) {
				$class = "offcamera";
			}
			
			//debug('process not references for this block');
			$v = preg_replace('/\[([0-9]+)\]/', '<span class="note" title="noteRef\\1" id="note_\\1">[\\1]</span>', $v);
			
			//debug('Processing note references');
			//if($this->options['noterefs']) {
				//$v = process_note_refs($v);
			//}
			
			// assemble output block
			$o .= '<div class="'. $class. '">'. $v ."</div>\n";
		}
		//debug('---->'.$o);
		//debug('Exiting TranscriptParse::parse()');
		//debug($this->text);
		$this->text = $o;
	}

	public function process_note_refs($text) {
			//debug('matches note reference');
			//$text = preg_replace('/\[([0-9]+)\]/', '<span class="note" title="noteRef\\1" id="note_\\1">[\\1]</span>', $text);
		//return $text;
	}

	public function process_notes() {
	//debug("Processing notes");
		$c = count($this->map);
		//debug("Processing $c notes");
		foreach( $this->map as $i=>$note ) {
		$note = html_escape($note);
			//debug('  noteRef'.$i);
			$this->text = preg_replace('/noteRef'.$i.'/', $note, $this->text);
		}
	}
	
	// expects array map of name value pairs for each option
	public function setOptions($options) {
		$this->options = $options;
	}

	public function setMap($map) {
		$this->map = $map;
	}
	
	protected function Blockify()
	{
		// normalize line endings before splitting into blocks
		$this->text = preg_replace('/\n\r/', "\n", $this->text);
		$this->text = preg_replace('/\r/', "\n", $this->text);
		
		$this->textBlocks = preg_split("/[\n]+/", $this->text);
	}

	protected function _dialog($v)
	{
	//debug('TranscriptParse::_dialog()');
	    //debug('Exiting TranscriptParse::_dialog()');
	    return $v;
	}
	
	
	protected function Timeify() {

	/**
	 * Process time codes into links
	 *
	 */
	
	//$v = preg_replace('/(\dd:\dd)/', 'rtsp://video.ibiblio.org/folkstreams/video/the_music_district.rm?start=12:45&end=25:54', $v);

	}
	
	// for use with overlib, unused in ItemTranscript
	protected function Noteify() {
	
		// Process note references in transcript text
		 if( preg_match('/(\[[0-9]+\])/', $v) ) {
	   
			$v = preg_replace('/\[([0-9]+)\]/', '<A class=popup HREF="javascript:void(0);" onMouseOver="return overlib(INARRAY, \\1, STICKY, CAPTION, \'Film Note\');" onMouseOut="nd();">[\\1]</a>', $v);   
			}
					  
			$o .= '<p class="'. $class. '">'. $v ."</p>\n";
	}

}