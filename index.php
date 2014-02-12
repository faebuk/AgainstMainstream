<?php 
//Neuer Dispatcher wird erstellt, welche die ganze Website an die verschiedenen Controller "versendet"
require_once 'lib/Dispatcher.php';

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
?>