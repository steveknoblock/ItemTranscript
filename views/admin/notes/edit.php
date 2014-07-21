<?php
// notes edit


$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Editing Note "%s"', '#'. $this->note->id .' '.$this->note->title)
              );
echo head($head);

?>
<?php echo flash(); ?>
<?php //echo $this->transcript; ?>

<form id="transcript-edit-form" method="post" class="">
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('text', __('Note')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('text', $this->note->text,array( 'cols' => '55', 'rows' => '7')); ?>
            </div>
        </div>
        

    

    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($note->exists()): ?>
                <?php echo link_to($exhibit, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
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