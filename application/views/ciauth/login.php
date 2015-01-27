<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Singin</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
    <div class="error"><?php echo $message;?></div>
    
        <form class="form-signin" method="POST">
            <div class="form-group<?php if (form_error('username')) echo ' has-error';?>">
                <label for="exampleInputEmail1">Username</label>
                <?php echo form_error('username');?>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group<?php if (form_error('password')) echo ' has-error';?>">
                <label for="exampleInputPassword1">Password</label>
                <?php echo form_error('password');?>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-default">Login</button>
        </form>
    </div>
</body>
</html>