<?php
  echo $this->element('section-header', array('section'=>'Usuarios', 'section_url'=>'Usuarios'));
?>

<?php
  echo $this->element('Users/form', array('action'=>'edit', 'title'=>'Editar usuario'));
?>
