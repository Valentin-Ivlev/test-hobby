<?php
require_once(__DIR__.'/db-connect.php');
require_once(__DIR__.'/simple-html-dom/simple_html_dom.php');
$query=mysqli_query($link, "SELECT COUNT(id) AS total FROM articles;");
$data=mysqli_fetch_assoc($query);
$total_articles=$data['total'];
$current_page=$_GET['page'];
$articles_on_page=5;

$query=mysqli_query($link, "SELECT * FROM articles ORDER BY id desc LIMIT ".$current_page*$articles_on_page.", ".$articles_on_page.";");
$a_counter=0;
while ($row=mysqli_fetch_array($query)) {
  if ($a_counter>0) echo '<hr>';
  echo '<div class="px-0 mx-0"><a href="https://habr.com'.$row['link'].'" target="_blank"><b>'.$row['name'].'</b></a></div>';
  //echo $row['text'];
  $article_html=new simple_html_dom();
  echo '<div class="px-0 mx-0">'.trim(mb_substr(str_get_html($row['text'])->plaintext, 0, 200), ' ').'...</div><button class="btn btn-info" id="view_'.$row['id'].'" a-name="'.$row['name'].'">Полный текст</button>';
  $a_counter++;
}
function handle_pagination($total, $page, $shown, $url) {  
  $pages=ceil($total/$shown); 
  $range_start=(($page>=5)?($page-3):1);
  $range_end=((($page+5)>$pages)?$pages:($page+5));
  if ($page>=1) {
    $r[]='<li class="page-item"><span class="page-link" id="'.$url.'0'.'">&laquo; первая</span></li>';
    $r[]='<li class="page-item"><span class="page-link" id="'. $url.($page-1).'">&lsaquo; предыдущая</span></li>';
    $r[]=(($range_start>1)?'<li class="page-item disabled"><span class="page-link">...</span></li>':''); 
  }
  if ($range_end>1) {
    foreach(range($range_start, $range_end) as $key=>$value) {
      if ($value==($page+1)) $r[]='<li class="page-item active"><span class="page-link">'.$value.'</span></li>'; 
      else $r[]='<li class="page-item"><span class="page-link" id="'.$url.($value-1).'">'.$value.'</span></li>'; 
    }
  }
  if (($page+1)<$pages) {
    $r[]=(($range_end<$pages)?'<li class="page-item disabled"><span class="page-link">...</span></li>':'');
    $r[]='<li class="page-item"><span class="page-link" id="'.$url.($page+1).'">слудующая &rsaquo;</span></li>';
    $r[]='<li class="page-item"><span class="page-link" id="'.$url.($pages-1).'">последняя &raquo;</span></li>';
  }
  return ((isset($r))?'<nav class="col-12 mt-3"><ul class="pagination justify-content-center">'.implode($r).'</ul></nav>':'');
}
echo handle_pagination($total_articles, $current_page, $articles_on_page, 'topage_');
?>