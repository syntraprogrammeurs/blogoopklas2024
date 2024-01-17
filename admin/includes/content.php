<?php
$user = User::find_by_id(11);
$user->username = "usernamesss";
$user->password = "pwdsss";
$user->last_name = "last_namesss";
$user->first_name = "first_namesss";

$user->save();
?>