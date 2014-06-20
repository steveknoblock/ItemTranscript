<?php

$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Editing Transcript "%s"', '#'. $this->id .' '.$this->title)
              );
echo head($head);

?>
<?php echo flash(); ?>
<?php echo $this->form; ?>
<?php echo foot(); ?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(window).load(function() {
        Omeka.wysiwyg();
    });
//]]>
</script>
