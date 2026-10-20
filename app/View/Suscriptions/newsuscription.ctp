<?php
$titulo = h($Event['Event']['titulo']);
echo $this->element('section-header', array('section' => 'Espectadores: ' . $titulo, 'section_url' => 'Espectadores del evento'));
echo $this->element('Productos/suscription', array('title' => 'Listado'));
