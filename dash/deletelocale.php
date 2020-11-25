<?php
$fileName = $_GET['file'];
@unlink('../locales/' . $fileName . '.json');

header('Location: locale.php');
exit;

