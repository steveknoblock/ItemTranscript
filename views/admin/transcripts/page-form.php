<?php

//var_dump( $transcript );

//$title = ($actionName == 'Add') ? __('Add Transcript') : __('Edit Transcript "%s"', $transcript->title);

if( $transcript->title ) {
	$title = __('Edit Transcript "%s"', $transcript->title);
	} else {
	$title = __('Add Transcript');
}
	

echo head(array('title'=> $title, 'bodyclass'=>'transcripts'));
?>

<?php echo flash(); ?>
<form id="transcript-form" method="post">
    <div class="seven columns alpha">
    <fieldset>
        <div class="field">
            <div class="two columns alpha">
            <?php echo $this->formLabel('title', __('Transcript Title')); ?>
            </div>
            <div class="inputs five columns omega">
            <?php echo $this->formText('title', $transcript->title); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('slug', __('Page Slug')); ?>
            </div>
            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __('No spaces or special characters allowed'); ?></p>
                <?php echo $this->formText('slug', $transcript->slug); ?>
            </div>
        </div>
    </fieldset>
    <fieldset id="block-container">
        <h2><?php echo __('Content'); ?></h2>
        <span class="collapse"><?php echo __('Collapse All'); ?></span>
        <span class="expand"><?php echo __('Expand All'); ?></span>
        <p class="instructions"><?php echo __('To reorder blocks and items, click and drag them to the preferred location.'); ?></p>
        <?php
        /*
        foreach ($transcript->getPageBlocks() as $index => $block):
            $block->order = $index + 1;
            echo $this->partial('transcripts/block-form.php', array('block' => $block));
        endforeach;
        */
        ?>
        <div class="add-block">
            <h2><?php echo __('New Block'); ?></h2>
            
        </div>
    </fieldset>
    </div>
    
    <div class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('continue', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($transcript->exists()): ?>
                <?php //echo transcript_link_to_transcript($transcript, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank'), $transcript); ?>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php //This item-select div must be outside the <form> tag for this page, b/c IE7 can't handle nested form tags. ?>


<script type="text/javascript">
jQuery(document).ready(function () {
    Omeka.TranscriptBuilder.setUpBlocks(<?php echo json_encode(url('transcripts/block-form')); ?>);
    <?php
    /*
    if ($transcript->exists()) {
        $validateUrl = url(
            array('action' => 'validate-page', 'id' => $transcript->id),
            'transcriptStandard', array(), true);
    } else {
        $validateUrl = url(
            array('action' => 'validate-page', 'transcript_id' => $transcript->transcript_id,
                'parent_id' => $transcript->parent_id),
            'transcriptAction', array(), true);
    }
    */
    ?>
    Omeka.TranscriptBuilder.setUpPageValidate(<?php echo js_escape($validateUrl); ?>);

    Omeka.wysiwyg();
    jQuery(document).on('transcript-builder-refresh-wysiwyg', function (event) {
        // Add tinyMCE to all textareas in the div where the item was attached.
        jQuery(event.target).find('textarea').each(function () {
            tinyMCE.execCommand('mceAddControl', false, this.id);
        });
    });
});
</script>
<?php echo foot(); ?>
