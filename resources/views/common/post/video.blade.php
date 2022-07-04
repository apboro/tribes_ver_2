<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Отправка файла на сервер</title>
 </head>
 <body>
  <form action="" enctype="multipart/form-data" method="post">
    {{ csrf_field() }}
   <p><input type="file" name="f">
   <input type="submit" value="Отправить"></p>
  </form> 
 </body>
</html>