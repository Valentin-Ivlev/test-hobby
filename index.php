<!doctype html>
<html lang="ru" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Test</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="/css/bootstrap-reboot.min.css" rel="stylesheet">
    <link href="/css/bootstrap-utilities.min.css" rel="stylesheet">
    <style>
      .bg-dark {
        background-color: #000000 !important;
      }
      body {
        background: #fff;
      }
      main > .container {
        padding: 60px 15px 15px;
      }
      .footer {
        background-color: #f5f5f5;
      }
      .footer > .container {
        padding-right: 15px;
        padding-left: 15px;
      }
      .page-item {cursor: pointer;}
      .page-item.disabled {cursor: default;}
      .modal-open {
        overflow: hidden;
      }
    </style>
</head>
<body class="d-flex flex-column h-100">
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <button class="btn btn-outline-info my-2 my-sm-0 mx-auto" id="load_button">Загрузить</button>
    </div>
  </nav>
</header>
<main role="main" class="container mt-3">
<div class="container">
  <div class="row">
    <div class="col-12 text-center" id="main_spinner"><div class="spinner-border text-success" role="status"></div></div>
    <div class="col-12" id='main_content'>
    </div>
  </div>
</div>
</main>
<footer class="footer mt-auto py-3 bg-dark">
  <div class="container-fluide mx-3">
    <span class="text-muted" style="color: #808080"> </span>
  </div>
</footer>
</content>
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">Загрузка новых статей</h5>
      </div>
      <div class="modal-body">
        <div class="col-12 text-center"><div class="spinner-border text-success" role="status" id="infoModal_spinner"></div><div id="infoModal_result"></div></div>
      </div>
      <div class="modal-footer" id="infoModal_bottom">
        <button type="button" class="btn" data-bs-dismiss="modal" id="infoModal_button">Ok</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="article_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title h4" id="article_header"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="spinner-border text-success" role="status" id="article_spinner"></div><div id="article_text"></div>
      </div>
    </div>
  </div>
</div>
<script src="/js/jquery-3.4.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.esm.min.js"></script>
</body>
<script>
  var current_page=0;
  $(document).on('click', '[id^="topage_"]', function() {
    var page_num=$(this).attr('id');
    current_page=page_num.replace('topage_', '');
    load_page(current_page);
  });
  $(document).on('click', '[id^="view_"]', function() {
    var article_id=$(this).attr('id');
    article_id=article_id.replace('view_', '');
    var article_name=$(this).attr('a-name');
    view_article(article_id, article_name);
  });
  $("#load_button").click(function(){
    $('#infoModal').modal('show');
    $('#infoModal_bottom').addClass("d-none");
    $('#infoModal_spinner').removeClass("d-none");
    $('#infoModal_button').removeClass("btn-success");
    $('#infoModal_button').removeClass("btn-danger");
    $('#infoModal_result').html('');
    $.ajax({
      type: "GET",
      url: "./php/get-new-articles.php",
      complete: function(msg) {
        $('#infoModal_bottom').removeClass("d-none");
        $('#infoModal_spinner').addClass("d-none");
      },
      success: function(msg) {
        var message=jQuery.parseJSON(msg);
        if(message.added_articles>0) $('#infoModal_result').html('Загрузка заершена<br>Добавлено статей: '+message.added_articles);
        else $('#infoModal_result').html('Новых статей не обнаружено');
        $('#infoModal_button').addClass("btn-success");
        $('#infoModal_button').html('OK');
        load_page(current_page);
      },
      error: function(msg) {
        $('#infoModal_result').html('Ошибка');
        $('#infoModal_button').addClass("btn-danger");
        $('#infoModal_button').html('Закрыть');
      }
    })
  });
  function load_page(page_num) {
    $('#main_spinner').removeClass("d-none");
    $('#main_content').addClass("d-none");
    $.ajax({
      type: "GET",
      url: "./php/get-page.php?page="+page_num,
      complete: function(msg) {
        $('#main_content').removeClass("d-none");
        $('#main_spinner').addClass("d-none");
      },
      success: function(msg) {
        if(msg.length>0) $('#main_content').html(msg);
        else $('#main_content').html('Cтатьи не загржены');
      },
      error: function(msg) {
        $('#main_content').html('Ошибка');
      }
    })
  }
  function view_article(article_id, article_header) {
      $('#article_spinner').removeClass("d-none");
      $('#article_text').addClass("d-none");
      $('#article_header').html('');
      $('#article_text').html('');
      $('#article_modal').modal('show');
      $('#article_header').html(article_header);
      $.ajax({
        type: "GET",
        url: "./php/get-article.php?a_id="+article_id,
        complete: function(msg) {
          $('#article_text').removeClass("d-none");
          $('#article_spinner').addClass("d-none");
        },
        success: function(msg) {
          $('#article_text').html(msg);
        },
        error: function(msg) {
          $('#article_text').html('Ошибка');
        }
      })
  }
  load_page(current_page);
</script>
</html>