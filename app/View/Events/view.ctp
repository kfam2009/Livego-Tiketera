<?php
echo $this->element('section-header', array('section' => $event['Event']['titulo'], 'section_url' => 'Evento en Vivo'));
?>
<div class="row" style ="margin-top:20px;">
	<div class="col">
		<div class="row">
			<div class="col-lg-3 col-xl-3 mb-4 mb-xl-0">

				<section class="card">
					<div class="card-body">
						<div class="thumb-info mb-3">
							<?php echo $this->Html->image(livego_imagen_url($event['Event']), array('class'=>"rounded img-fluid", 'alt'=>h($event['Event']['titulo'])));?>

							<div class="thumb-info-title">
								<span class="thumb-info-inner"><?php echo $event['Event']['titulo']; ?></span>
								<span class="thumb-info-type"><?php echo (date_format(date_create($event['Event']['fecha']), 'd-m-Y')); ?></span>
							</div>
						</div>

						<hr class="dotted short">
						<h5 class="mb-2 mt-3"><i class="fas fa-clock mr-1"></i><?php echo (date_format(date_create($event['Event']['fecha']), 'd-m-Y')); ?></h5>
						<p class="text-2"></p>
						<div class="clearfix">

						<h5 class="mb-2 mt-3">Descripción:</h5>
						<p class="text-2"><?php echo $event['Event']['descripcion']; ?></p>
						<div class="clearfix">

						<h5 class="mb-2 mt-3">Horarios:</h5>
						<p class="text-2"><?php echo $event['Event']['horario']; ?></p>
						<div class="clearfix">

						</div>

					</div>
				</section>

			</div>
			<div class="col-lg-9 col-xl-9" style="margin-top:40px;">
				<div class="tabs">

					<div class="tab-content">
						<div id="overview" class="tab-pane active">
							<div class="p-3">
									<iframe width="100%" height="720" src="<?php echo $event['Event']['link']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
