<?php
require_once(__DIR__.'/db-connect.php');
require_once(__DIR__.'/simple-html-dom/simple_html_dom.php');
$article_id=$_GET['a_id'];
$query=mysqli_query($link, "SELECT * FROM articles WHERE id=".$article_id.";");
$data=mysqli_fetch_assoc($query);
echo $data['text'];
?>