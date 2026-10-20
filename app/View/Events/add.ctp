<?php
  echo $this->element('section-header', array('section'=>'Crear Evento', 'section_url'=>'Evento / Crear'));
?>

<?php
  echo $this->element('Productos/form', array('action'=>'add', 'title'=>'Nuevo Evento'));
?>
