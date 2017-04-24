<?php

namespace TLMK\Library;
require "../../vendor/autoload.php";
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);
//print_r($_POST);

if (isset($_POST['action'])) {
    $number = new NumberList();
    if ($_POST['action'] == 'listClass') {
        $class = $number->listClassification();
        foreach ($class as $item) {
            echo "<option value='{$item['NumReg']}'>{$item['LITERAL']}</option>";
        }
    }
    if ($_POST['action'] == 'listNumbers') {
        $listNumber = $number->listNumberByClass($_POST['classes'], $_POST['limit']);
        if (count($listNumber) >= 1) {
            foreach ($listNumber as $item) {
                echo '<tr>';
                echo "<td>{$item['telefono']}</td>";
                echo "<td>{$item['LITERAL']}</td>";
                echo "<td><a id='{$item['_id']}' class='btn waves-effect waves-ripple white-text addNumber red accent-2'>
                Agregar</a></td>";
                echo '</tr>';

            }
        } else {
            echo "<h5>No se encontraron resultados</h5>";
        }

    }
    if ($_POST['action'] == 'addNumberTask') {
        $task = $number->insertTasks($_POST['id_user'], $_POST['id_client']);
        echo $task;
    }

}