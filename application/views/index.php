<!DOCTYPE html>
<html lang="ru">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>
        Admin
    </title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <body>
        <div class="container">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                          
                        </button>
                        <a class="navbar-brand" href="#">Project name</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/admin">Users</a></li>
                            <li><a href="/welcome/register">new user</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <?php if ($this->ciauth_lib->loggedIn()):?>
                            <li><a href="/welcome/logout">Logout</a></li>
                            
                            <?php endif;?>
                            
                        </ul>
                    </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
            </nav>
            <!--<div class="page-header">
                <h1><?php if (isset($pageHeader)) echo $pageHeader;?></h1>
            </div>-->
            
            
            
            <?php if (isset($bodyWrap)) echo $bodyWrap;?>
        </div>
        
    </body>
</html>