<?php
require_once 'vendor/autoload.php';

//Set up twig templating
$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader);

//Render the template
echo $twig->render('events.twig');
?>