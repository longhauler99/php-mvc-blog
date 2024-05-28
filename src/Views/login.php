<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>PHP-MVC-FRAMEWORK</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Slide Navbar</title>
<!--    <link rel="stylesheet" type="text/css" href="slide navbar css/style.css">-->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true" checked>

    <div class="signup">
        <form id="signup-form" class="form" action="/register" method="POST">
            <label for="chk" aria-hidden="true">Sign up</label>
            <input type="text" name="username" placeholder="User name" required="">
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="pwd" placeholder="Password" required="">
            <input type="password" name="pwd2" placeholder="Confirm Password" required="">
            <button type="submit" name="signup-button">Sign up</button>
        </form>
    </div>

    <div class="login">
        <form id="login-form" class="form" action="/login" method="POST">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="text" name="username" placeholder="User Name" required="">
            <input type="password" name="pwd" placeholder="Password" required="">
            <button type="submit" name="login-button">Login</button>
        </form>
    </div>
</div>
</body>
</html>
<!-- partial -->

</body>
</html>
