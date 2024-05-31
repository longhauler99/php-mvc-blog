<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogDaily</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="font/bootstrap-icons.css">
</head>
<body>
<div class="mb-5">
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light shadow-sm">
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
        <div class="col col-8">
            <div class="mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>New Post</h5>
                    </div>
                    <div class="card-body">
                        <form id="post-form">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control post-title" name="title" id="title">
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea class="form-control post-description" name="description" id="desc" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="me-1">
                            <button type="button" class="new-post btn btn-success btn-sm">Submit Post</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="alert alert-light" id="posts-container">
<!--                    Posts-->
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer">
        <div class="toast" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="..." class="rounded me-2 bi bi-square-fill" alt="...">
                <strong class="me-auto">BlogDaily</strong>
                <small class="text-muted">just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

</div>
<!-- Your content here -->

<script src="js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('click', ClickHandler)

    function ClickHandler(event)
    {
        const eln = event.target;

        if(eln.matches('.new-post'))
        {
            const apiUrl = '/newpost';
            const apiUrl2 = '/fetchPosts';
            let queryCue = new FormData(document.getElementById('post-form'));
            Save1Record(apiUrl, queryCue, apiUrl2);
        }
    }

    fetchPosts('/fetchPosts');

    function Save1Record(apiUrl, queryCue, apiUrl2)
    {
        fetch(apiUrl, {
            method: 'POST',
            body: queryCue,
        })
            .then(response => {
                if(!response.ok)
                {
                    throw new Error(`HTTP Error! Status: ${response.status}`);
                }
                //
                return response.json();
            })
            .then(returnData => {
                if(returnData.success)
                {
                    // window.location.href = returnData.redirect;
                    // alert(returnData.success);
                    document.getElementById('post-form').reset();
                    showToast(returnData.success);
                    fetchPosts(apiUrl2);
                }
                else if(returnData.errors) // Handle multiple errors
                {
                    alert(returnData.errors.join("\n"));
                }
                else if(returnData.error) // Handle a single error
                {
                    alert(returnData.error);
                }
            })
            .catch(error => {
                alert(error.message);
            });
    }

    function fetchPosts(apiUrl2) {
        fetch(apiUrl2)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.posts && data.posts.length > 0)
                {
                    document.querySelector("#posts-container").innerHTML = createPostElement(data);
                }
                else
                {
                    console.log('No posts found');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function createPostElement(data) {
        let htmlContent = '';
        data.posts.forEach(post => {
            htmlContent +=
                `
                    <div class="post-section alert alert-light">
                        <h4 class="post-title">${post.title}</h4>
                        <p class="post-description">${post.content}</p>
                    </div>
                `;
        });
        //
        return htmlContent;
    }

    // Function to show toast
    function showToast(message) {
        const toastContainer = document.getElementById('toastContainer');
        const toastElement = document.getElementById('liveToast');
        const toastBody = toastElement.querySelector('.toast-body');
        toastBody.textContent = message;

        // Initialize the toast
        const toast = new bootstrap.Toast(toastElement);

        // Show the toast
        toast.show();
    }


</script>
</body>
</html>


