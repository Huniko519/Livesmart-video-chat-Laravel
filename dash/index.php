<?php

session_start();
if (empty($_SESSION["username"])) {
    header("Location:loginform.php");
} else {
    header("Location:dash.php");
}