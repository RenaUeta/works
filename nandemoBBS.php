<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>なんでも掲示板</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
</head>

<body style="padding-top: 60px;">
  <header>
    <!-- トップ固定ナビゲーション -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#"><i class="fas fa-pen-square"></i></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header" aria-expanded="false" aria-label="ナビゲーションの切替">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar-header">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">書き込む<span class="sr-only">(現位置)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#message">書き込み一覧</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main role="main" class="container">
    <div class="text-center">
      <h1 class="mt-5">なんでも掲示板</h1>
      <p class="lead">ここはなんでも書いていい掲示板</p>
    </div>

    <div class="jumbotron mt-4">
      <form method="POST" action="./nandemoBBS.php">
      	<div class="form-group mb-3">
      		<label for="uname">ユーザー名</label>
      		<input class="form-control" type="text" name="uname" value="">
      	</div>
        <div class="form-group form-row">
          <div class="col-md-6">
            <label for="title">タイトル</label>
            <input class="form-control" type="text" name="title">
          </div>
          <div class="col-md-6">
            <label for="password">パスワード</label>
            <input class="form-control" type="password" name="dpw">
          </div>
        </div>
      	<div class="mb-3">
      		<label for="message">本文</label>
      		<textarea class="form-control" name="message" rows="4"></textarea>
      	</div>
      	<button type="submit" class="btn btn-primary">書き込む</button>
      </form>
    </div>



    <?php
    $dbconn = pg_connect("host=localhost dbname=anera user=anera password=sg33BGk6")
      or die('Could not connect: ' . pg_last_error());
    if(isset($_POST['uname']) && strlen($_POST['uname'])>0){
    $uname=$_POST['uname'];
    }

    if(isset($_POST['title']) && strlen($_POST['title'])>0){
    $title=$_POST['title'];
    }

    if(isset($_POST['message']) && strlen($_POST['message'])>0){
    $message=$_POST['message'];
    }

    if(isset($_POST['dpw']) && strlen($_POST['dpw'])>0){
    $dpw=$_POST['dpw'];
    }

    if(isset($_POST['delpid']) && strlen($_POST['delpid'])>0){
    $delpid=$_POST['delpid'];
    }


    if (isset($message)){
      $sql="insert into phpbbs(uname,title, message,pdate,dpw)
        values('" . $uname . "','" . $title . "','" . $message .
        "',current_date, '" . $dpw . "');";
      $result = pg_query($sql) or die('Query failed: ' . pg_last_error());
    }

    if (isset($delpid) && isset($dpw)){
      $sql="delete from phpbbs where pid='" . $delpid . "' and dpw='" . $dpw .
        "';";
      $result = pg_query($sql) or die('Query failed: ' . pg_last_error());
    }


    $query="select pdate,title,uname,message,pid,dpw from phpbbs order " .
      "by pid desc;";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_row($result)){
      echo $line[1] . " (" . $line[0] . ") by " . $line[2] . "<br>" .
        $line[3];
      echo "<form id=\"message\" method=\"POST\" action=\"./nandemoBBS.php\">" .
      "<div class=\"form-row align-items-center\">" .
        "<input type=\"hidden\" name=\"delpid\" value=\"" . $line[4] . "\">" .
        "<div class=\"col-sm-3\">" .
          "<label class=\"sr-only\" for=\"dpw\">" . "削除パスワード</label>" .
          "<input class=\"form-control\" type=\"password\" name=\"dpw\">" .
        "</div>" .
        "<div class=\"col-auto my-1\">" .
          "<input type=\"submit\" class=\"btn btn-primary\" value=\"削除\">" .
        "</div>" .
      "</div>" .
      "</form><hr class=\"mb-4\">";
    }
    ?>
  </main>
