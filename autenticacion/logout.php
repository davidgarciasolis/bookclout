<?php
session_start();
session_destroy();
header("Location: ../manager/login.php");
exit();
