<?php
include_once "cors.php";
include_once "functions.php";
if (!isset($_GET["id"])) {
    echo json_encode(null);
    exit;
}
$id = $_GET["id"];
$content = delSuggestion($id);
echo json_encode($content);
?>