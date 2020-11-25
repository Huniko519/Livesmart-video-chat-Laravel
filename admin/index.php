<?php

session_start();
if (empty($_SESSION["username"])) {
    header("Location:loginform.php");
} else {
    header("Location:agent.php");
}