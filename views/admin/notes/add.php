<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Add Note')
              );
echo head($head);
?>
<?php echo flash(); ?>
<form id="edit-transcript-form" method="post" class="">
		<div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('text', __('Note')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('text', '',array( 'cols' => '55', 'rows' => '7')); ?>
            </div>
        </div>

		<?php echo $this->formHidden('transcript_id', $transcript_id); ?>

    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
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