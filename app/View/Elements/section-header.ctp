<header class="page-header">
	<h2><?php echo $section;?></h2>

	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>

				<?php echo $this->Html->link('<i class="fas fa-home"></i>', array('controller'=>'events','action'=>'index'), array('escape'=>false));
				?>
			</li>
			<li><span><?php echo $section_url;?></span></li>
		</ol>
		<a class="sidebar-right-toggle"></a>
	</div>
</header>
