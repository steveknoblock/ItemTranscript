<div class="field">
	<div class="two columns alpha">
        <label for="entry">Notes</label>
    </div>
    <div class="five columns omega inputs">
		<?php echo item_transcript_notes_list(); ?>
	</div>
</div>
<?php echo $this->formHidden('notes-hidden'); ?>
<?php echo $this->formHidden('notes-delete-hidden'); ?>
<script type="text/javascript">
Omeka.addReadyCallback(Omeka.ItemTranscript.enableSorting);
Omeka.addReadyCallback(Omeka.ItemTranscript.activateDeleteLinks);
Omeka.addReadyCallback(Omeka.ItemTranscript.setUpFormSubmission);
</script>

