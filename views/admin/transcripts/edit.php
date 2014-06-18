<?php
    $title = __('Edit Transcript');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'transcript'));
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
