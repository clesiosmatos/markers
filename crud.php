<?php

include 'connection.php';

if($_GET['action'] == 'create'){
    create($_POST);
}

if($_GET['action'] == 'delete'){
    delete($_GET['id']);
}

if($_GET['action'] == 'update'){
    update($_GET['id'], $_POST);
}