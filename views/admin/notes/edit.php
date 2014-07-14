<?php
// notes edit


$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Editing Note "%s"', '#'. $this->note->id .' '.$this->note->title)
              );
echo head($head);

//echo $this->title;
//echo $this->description;
//print "<p>Breakpoint";
//echo $this->note->title;
//echo $this->note->description;


?>
<?php echo flash(); ?>
<?php //echo $this->transcript; ?>

<form id="edit-transcript-form" method="post" class="">
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('title', $this->note->title); ?>
            </div>
        </div>
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('description', __('Description')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('description', $this->note->description,array( 'cols' => '55', 'rows' => '7')); ?>
            </div>
        </div>
        
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('entry', __('Transcript Entry')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('entry', $this->note->entry); ?>
            </div>
        </div>

    

    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($note->exists()): ?>
                <?php echo __('View Public Page'); ?>
                <?php echo link_to($exhibit, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
            <div id="public-featured">
                <div class="public">
                    <label for="public"><?php echo __('Public'); ?>:</label> 
                    <?php echo $this->formCheckbox('public', $this->note->public, array(), array('1', '0')); ?>
                </div>
                <div class="featured">
                    <label for="featured"><?php echo __('Featured'); ?>:</label> 
                    <?php echo $this->formCheckbox('featured', $this->note->featured, array(), array('1', '0')); ?>
                </div>
            </div>
        </div>
    </section>
    

</form>
<?php echo foot(); ?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(window).load(function() {
        Omeka.wysiwyg();
    });
//]]>
</script>
<script type="text/javascript">
jQuery(document).ready(function () {
    Omeka.runReadyCallbacks();
});
</script>