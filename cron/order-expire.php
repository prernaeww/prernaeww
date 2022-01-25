<?php

$result = file_get_contents('https://api.canteeny.com/order_expire');
$result = file_get_contents('https://api.canteeny.com/reminder');

$msg = "Execute order_assign cronjob from Cantenny ".$result;
mail("ravi@excellentwebworld.com","Execute cronjob from Cantenny",$msg);
echo 'Cron executed successfully';
exit;

?>