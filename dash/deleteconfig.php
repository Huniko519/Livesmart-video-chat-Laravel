<?php
$fileName = $_GET['file'];
@unlink('../config/' . $fileName . '.json');

header('Location: config.php');
exit;

