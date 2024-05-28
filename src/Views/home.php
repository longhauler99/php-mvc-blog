<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-MVC-APPLICATION</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home">BlogDaily</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
<!--                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Job Seekers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Career
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Employers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
-->                </ul>
                <form name="logout-form" action="/logout" method="POST">
                    <button class="btn btn-sm btn-danger text-light" type="submit" name="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
            <div class="alert alert-light mt-2">
                <div class="text-center">
                    <h1 class="h1">Simple PHP MVC Starter!</h1>
                </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Email Address</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)) {
                            foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user->UserID ?></td>
                                    <td><?= $user->UserName ?></td>
                                    <td><?= $user->Email ?></td>
                                    <td><?= $user->Password ?></td>
                                </tr>
                            <?php endforeach;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Your content here -->

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


