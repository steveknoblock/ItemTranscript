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
class TranscriptParse {

	public $text;
	
	protected $textBlocks;
	
	public function __construct($text)
	{
		debug('TranscriptParse::__contstruct()');
		//debug('Text: '. $text);
		$this->text = $text;
		//debug($this->text);
		debug('Exiting TranscriptParse::__contstruct()');
	}
	
	public function parse() {
		debug('TranscriptParse::parse()');
		debug('Calling TranscriptParse::_dialog()');
		$this->Blockify();
		$o = '';
		foreach ( $this->textBlocks as $k => $v ) {
		
			// common mistakes
	$v = preg_replace('/\(V\/0\):/', '(V/O):', $v);
	$v = preg_replace('/\(0\/C\)/', '(O/C)', $v);
	
			// strip newlines, should any remain
	$v = preg_replace('/\n/', '', $v);
	
			$class = "line"; // default
			if( preg_match('/^[A-Z]+:/', $v) ) {
				debug('matches dialog');
				$class = "dialog";
				}
			if( preg_match('/^[A-Z]+ +\(V\/O\):/', $v) ) {
				debug('matches voiceover');
				$class = "voiceover";
				}
			if( preg_match('/^[A-Z]+ +\(O\/C\):/', $v) ) {
				$class = "offcamera";
				}
			$o .= '<div class="'. $class. '">'. $v ."</div>\n";
		}
		//debug('---->'.$o);
		debug('Exiting TranscriptParse::parse()');
		//debug($this->text);
		$this->text = $o;
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
	debug('TranscriptParse::_dialog()');
	
	
	    

	    debug('Exiting TranscriptParse::_dialog()');
	    return $v;
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
	} // end fn

} // end TranscriptParse

