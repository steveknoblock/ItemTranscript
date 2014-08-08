<?php
    $title = __('Add Transcript');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'transcript'));
?>
<?php echo flash(); ?>
<form enctype="application/x-www-form-urlencoded" action="" method="post">
<section class="seven columns alpha">
	<div class="field">
		<div id="item-transcript-title-label" class="two columns alpha"><label for="item-transcript-title" class="required">Title</label></div>
	<div class="inputs five columns omega">
	<p class="explanation">Name of transcript (required)</p>
	<input type="text" name="title" id="item-transcript-title" value="">
	</div></div>
	<div class="field"><div id="item-transcript-description-label" class="two columns alpha"><label for="item-transcript-description" class="optional">Description</label></div>
		<div class="inputs five columns omega">
		<p class="explanation">Description of transcript</p>
		<textarea name="description" id="item-transcript-description" cols="50" rows="7"></textarea>
		</div>
	</div>
	<div class="field"><div id="item-transcript-entry-label" class="two columns alpha"><label for="item-transcript-entry" class="optional">Transcript Entry</label>
	</div>
	<div class="inputs five columns omega">
<p class="explanation">Add transcript text (using SpeakUp!) markup</p>
<textarea name="entry" id="item-transcript-entry" cols="50" rows="7"></textarea>
</div>
</div>
</section> 
    
<section id="save" class="three columns omega panel"><input id='save-changes' class='submit big green button' type='submit' value='Save Changes' name='submit' />
	<div class="field"><div id="item-transcript-is-published-label" class="two columns alpha"><label for="item-transcript-is-published" class="optional">Publish this transcript?</label>
	</div>
	<div class="inputs">
	<input type="hidden" name="public" value="0"><input type="checkbox" name="public" id="item-transcript-is-published" value="1" checked="checked" values="1 0">
	<p class="explanation">Checking this box will make the transcript public</p>
	</div>
	</div>
	<div class="field"><div id="item-transcript-is-featured-label" class="two columns alpha"><label for="item-transcript-is-featured" class="optional">Feature this transcript?</label></div>
	<div class="inputs">
	<input type="hidden" name="featured" value="0"><input type="checkbox" name="featured" id="item-transcript-is-featured" value="1" values="1 0">
	<p class="explanation">Checking this box will make the transcript featured</p>
	</div>
	</div>
	</section>
</form>
<?php echo foot(); ?>