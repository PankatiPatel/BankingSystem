<?php

unset($_COOKIE['customer']);
unset($_COOKIE['customer_id']);
$res = setcookie('customer', '',time() - 3600);
$res1 = setcookie('customer_id', '',time() - 3600);
header("location: p2.html");








?>