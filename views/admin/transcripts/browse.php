<?php
$title = __('Browse Transcripts') . ' ' . __('(%s total)', $total_results);
echo head(array('title'=>$title, 'bodyclass'=>'transcripts'));
?>
    
<?php if (!count($transcripts)): ?> 
    <div id="no-transcripts">
    <h2><?php echo __('There are no transcripts yet.'); ?></h2>
    
    <?php if (is_allowed('ItemTranscript_Transcripts','add')): ?>
        <a href="<?php echo html_escape(url('item-transcript/transcripts/add')); ?>" class="big green add button"><?php echo __('Add a Transcript'); ?></a></p>
    <?php endif; ?>
    </div>
    
<?php else: ?>

<?php if (is_allowed('ItemTranscript_Transcripts', 'add')): ?>
<div class="table-actions">
    <a href="<?php echo html_escape(url('item-transcript/transcripts/add')); ?>" class="small green add button"><?php echo __('Add a Transcript'); ?></a>
</div>
<?php endif; ?>

<?php echo pagination_links(); ?>
<table id="transcripts" class="full">
    <thead>
    <tr>
        <?php
        $browseHeadings[__('Title')] = 'title';
        $browseHeadings[__('Description')] = 'description';
        $browseHeadings[__('Date Added')] = 'added';
        echo browse_sort_links($browseHeadings, array('link_tag' => 'th scope="col"', 'list_tag' => '')); ?>
    </tr>
    </thead>
    <tbody>
        
<?php foreach($transcripts as $key=>$transcript): ?>
    <tr class="transcript<?php if ($key % 2 == 1) echo ' even'; else echo ' odd'; ?>">
        <td class="transcript-info<?php if ($transcript->featured) echo ' featured'; ?>">
            <span>
          		<a class="edit" href="<?php echo html_escape(record_url($transcript, 'show')); ?>">
					<?php echo html_escape($transcript->title); ?>
				</a>
				
				
            <?php if(!$transcript->public): ?>
                <?php echo __('(Private)'); ?>
            <?php endif; ?>
            </span>
            
            <ul class="action-links group">
				<li><a class="edit" href="<?php echo html_escape(record_url($transcript, 'edit')); ?>">
					<?php echo __('Edit'); ?>
				</a></li>
				<li><a class="delete-confirm" href="<?php echo html_escape(record_url($transcript, 'delete-confirm')); ?>">
					<?php echo __('Delete'); ?>
				</a></li>
			</ul>
            
        </td>
        <td><?php echo metadata($transcript, 'description'); ?></td>
        <td><?php echo format_date(metadata($transcript, 'added')); ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
<?php echo pagination_links(); ?>
<?php endif; ?>
<?php echo foot(); ?>
