<?php
$redir=$_GET['redir'];

session_start();
session_unset();

if ($redir=='main') {
	header('Location: ./biddsat.php');
} else if ($redir=='index') {
	header('Location: ./index.html');
}
?>
