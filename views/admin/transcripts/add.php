<?php
    $title = __('Add Transcript');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'exhibits'));
?>
<?php echo flash(); ?>
<form id="transcript-metadata-form" method="post" class="transcript-builder">
    <section class="seven columns alpha">
    <fieldset>
        <legend><?php echo __('Transcript Metadata'); ?></legend>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('title', $this->title); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('description', __('Description')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('description', $this->description, array('rows'=>'8','cols'=>'40')); ?>
            </div>
        </div>
    </fieldset>
    </section>
<section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_transcript', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($transcript->exists()): ?>
                <?php echo transcript_builder_link_to_transcript($this, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank')); ?>
                <?php echo link_to($this, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
            <div id="public-featured">
                <div class="public">
                    <label for="public"><?php echo __('Public'); ?>:</label> 
                    <?php echo $this->formCheckbox('public', $transcript->public, array(), array('1', '0')); ?>
                </div>
                <div class="featured">
                    <label for="featured"><?php echo __('Featured'); ?>:</label> 
                    <?php echo $this->formCheckbox('featured', $transcript->featured, array(), array('1', '0')); ?>
                </div>
            </div>
        </div>
    </section>
</form>
<?php echo $this->form; ?>
<?php echo foot(); ?>