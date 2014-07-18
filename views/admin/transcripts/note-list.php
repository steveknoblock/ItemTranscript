<section>
<div class="field">
            <div class="two columns alpha">
                <label for="entry">Notes</label>
            </div>
            <div class="five columns omega inputs">
<ul id="note-list" class="sortable">
<?php //foreach($this->transcript->notes as $note): ?>
    <?php echo item_transcript_notes_list(); ?>
<?php //endforeach; ?>
</ul>
</div>
</div>
</section>
<script type="text/javascript">
Omeka.addReadyCallback(Omeka.ItemTranscript.enableSorting);
Omeka.addReadyCallback(Omeka.ItemTranscript.activateDeleteLinks);
</script>

