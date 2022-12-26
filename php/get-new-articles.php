<?php
require_once(__DIR__.'/db-connect.php');
require_once(__DIR__.'/simple-html-dom/simple_html_dom.php');
$html=new simple_html_dom();
$html->load_file('https://habr.com/ru/all/');
$added_articles=0;
for($i=4; $i>=0; $i--) {
  $short_article=$html->find('.tm-article-snippet__title-link', $i);
  $header=$short_article->plaintext;
  $url=$short_article->href;
  $article_html=new simple_html_dom();
  $article_html->load_file('https://habr.com'.$url);
  $text=$article_html->find('.article-formatted-body', 0)->innertext;
  $q="INSERT INTO articles SELECT NULL, '".mysqli_real_escape_string($link, $header)."', '".mysqli_real_escape_string($link, $url)."', '".mysqli_real_escape_string($link, $text)."' FROM DUAL WHERE NOT EXISTS(SELECT link FROM articles WHERE link='".mysqli_real_escape_string($link, $url)."' LIMIT 1)";
  $query=mysqli_query($link, $q);
  if(mysqli_insert_id($link)>0) $added_articles++;
}
echo json_encode(Array(
    "added_articles"=>$added_articles
));
?>