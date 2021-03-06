<?php
    queue_js_file(array('vendor/jquery.nestedSortable', 'navigation','transcripts'));
	queue_css_file(array('transcripts', 'jquery-ui'));

	$head = array('bodyclass' => 'simple-notes primary', 
              'title' => __('Editing Transcript "%s"', '#'. html_escape($this->transcript->id) .' '.html_escape($this->transcript->title))
              );
   echo head($head);
?>
<?php echo flash(); ?>
<form id="edit-transcript-form" method="post" class="">
	<section class="seven columns alpha">
	<fieldset>
	<legend><?php echo __('Transcript Metadata'); ?></legend>
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('title', $this->transcript->title); ?>
        	</div>
        </div>
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('description', __('Description')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('description', $this->transcript->description,array( 'cols' => '55', 'rows' => '7')); ?>
            </div>
        </div>        
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('entry', __('Transcript Text')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('entry', $this->transcript->entry); ?>
            </div>
        </div>
	</fieldset>

    <fieldset>
        <legend><?php echo __('Notes'); ?></legend>
        <div id="notes-list-container">
            <?php if (!$transcript->notes): ?>
                <p><?php echo __('There are no notes.'); ?></p>
            <?php else: ?>
                <p id="reorder-instructions"><?php echo __('To reorder notes, click and drag a note to the preferred location.'); ?></p>
                <?php 
                echo common('note-list', array('transcript' => $transcript), 'transcripts'); ?>
            <?php endif; ?>
        </div>
        <div id="page-add">
            <input type="submit" name="add_note" id="add-note" value="<?php echo __('Add Note'); ?>" />
            <?php echo $this->formHidden('transcript-id-hidden', $transcript->id); ?>
		</div>
    </fieldset>
</section>

   <section id="save" class="three columns omega panel">
            <?php echo $this->formSubmit('save_transcript', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($transcript->exists()): ?>
            <?php
                $uri = public_url(array(
        	'module' => 'item-transcript',
        	'controller' => 'transcripts',
        	'action' => 'show',
           	'id' => $transcript->id
           	), '', array(), false, false); 
           	
           	?>
                <a href="<?php echo html_escape($uri); ?>" class='big blue button' 'target' = '_blank'>
                        <?php echo __('View Public Transcript'); ?>
                </a>
                <?php echo link_to($transcript, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
            <div id="public-featured">
                <div class="public">
                    <label for="public"><?php echo __('Public'); ?>:</label> 
                    <?php echo $this->formCheckbox('public', $this->transcript->public, array(), array('1', '0')); ?>
                </div>
                <div class="featured">
                    <label for="featured"><?php echo __('Featured'); ?>:</label> 
                    <?php echo $this->formCheckbox('featured', $this->transcript->featured, array(), array('1', '0')); ?>
                </div>
            </div>
    </section>
    
    
</form>
        
<?php echo foot(); ?>