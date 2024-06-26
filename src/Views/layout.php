<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
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
                <button class="btn btn-sm btn-success text-light me-2 new-post">New Post</button>
                <form name="logout-form" action="/logout" method="POST">
                    <button class="btn btn-sm btn-danger text-light" type="submit" name="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</div>
<div class="container-fluid">

    <?php echo $content ?>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer">
        <div class="toast" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto bi">BlogDaily</strong>
                <small class="text-muted">just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="formTemplate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="formTemplateLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formTemplateLabel">Add Employee</h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col">
                                <form id="genericForm">
                                    <div class="inputWrapper">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close-btn">Close</button>
                    <button id="" type="button" class="btn btn-sm btn-primary save-btn">save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Your content here -->

<script src="js/bootstrap.bundle.min.js"></script>
<script>

    window['liveForm'] = new FormTemplate(document.querySelector('#formTemplate'));

    document.addEventListener('click', ClickHandler)

    document.addEventListener("DOMContentLoaded", function() {
        fetchPosts('/fetchPosts');
    });

    function ClickHandler(event)
    {
        const eln = event.target;

        if(eln.matches("BUTTON"))
        {
            if(eln.matches('.new-post'))
            {
                const apiUrl = '/fetchModal';
                liveForm.SetDataAction('add');
                fetchModal(apiUrl, liveForm.dataAction);
            }
            else if(eln.matches('.save-btn'))
            {
                const apiUrl = '/newpost';
                const apiUrl2 = '/fetchPosts';
                let queryCue = new FormData(liveForm.form);
                Save1Record(apiUrl, queryCue, apiUrl2);
            }
            else if(eln.matches('.chg-btn'))
            {
                const apiUrl = '/editpost';
                const apiUrl2 = '/fetchPosts';
                let queryCue = new FormData(liveForm.form);
                queryCue.append('id', liveForm.recKey);
                Save1Record(apiUrl, queryCue, apiUrl2);
            }
            else if(eln.matches('.del-btn'))
            {
                const apiUrl = '/deletepost';
                const apiUrl2 = '/fetchPosts';
                let queryCue = new FormData();
                queryCue.append('id', liveForm.recKey);
                Save1Record(apiUrl, queryCue, apiUrl2);
            }
            else if(eln.matches('.close-btn'))
            {
                liveForm.form.reset();
                liveForm.close();
            }
        }
        else if(eln.matches("LI A"))
        {
            if(eln.matches(".edit-post"))
            {
                const apiUrl = '/fetchModal';
                liveForm.SetDataAction('edit');
                fetchModal(apiUrl, liveForm.dataAction, eln.dataset.id);
                // alert(eln.dataset.id);
            }
            else if(eln.matches(".delete-post"))
            {
                const apiUrl = '/fetchModal';
                liveForm.SetDataAction('del');
                fetchModal(apiUrl, liveForm.dataAction, eln.dataset.id);
                // alert(eln.dataset.id);
            }
        }
    }

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
                    showToast(returnData.success, 'bg-success');
                    liveForm.form.reset();
                    liveForm.close();
                    fetchPosts(apiUrl2);
                }
                else if(returnData.errors) // Handle multiple errors
                {
                    showToast(returnData.errors.join("\n"), 'bg-warning');
                    // alert(returnData.errors.join("\n"));
                }
                else if(returnData.error) // Handle a single error
                {
                    showToast(returnData.error, 'bg-warning');
                    // alert(returnData.error);
                }
            })
            .catch(error => {
                alert(error.message);
            });
    }

    function fetchPosts(apiUrl2)
    {
        fetch(apiUrl2)
            .then(response => {
                if (!response.ok)
                {
                    throw new Error('Network response was not ok');
                }

                return response.json();
            })
            .then(data => {
                const postsContainer = document.querySelector("#posts-container");

                if (data.posts && data.posts.length > 0 && postsContainer)
                {
                    postsContainer.innerHTML = createPostElement(data);
                }
                else
                {
                    if(postsContainer)
                    {
                        postsContainer.innerHTML = 'No posts yet!';
                    }
                    else
                    {
                        console.error('Posts container not found');
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            });
    }

    function createPostElement(data)
    {
        let htmlContent = '';

        data.posts.forEach(post => {
            htmlContent +=
                `<div class="alert alert-light">
                    <div class="post-section alert alert-light mb-4">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col">
                            <div class="row justify-content-between">
                                <div class="col">
                                    <h4 class="post-title">${post.title}</h4>
                                </div>
                                <div class="col text-end">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a data-id="${post.id}" class="dropdown-item edit-post">Edit</a></li>
                                            <li><a data-id="${post.id}" class="dropdown-item delete-post">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                                <p class="post-description">${post.content}</p>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col-md-2 text-start">
                            <span class="badge bg-primary"><i class="bi bi-chat"></i> 15 Comments</span>
                          </div>
                          <div class="col-md-2 text-start">
                            <span class="badge bg-success"><i class="bi bi-heart"></i> 50 Likes</span>
                          </div>
                          <div class="col-md-2 text-start">
                            <span class="badge bg-info"><i class="bi bi-share"></i> 100 Shares</span>
                          </div>
                          <div class="col-md-6 text-end">
                            <small class="text-muted">${post.username} . <i class="bi bi-clock"></i> ${new Date(post.created_at).toLocaleString()}</small>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>`;
        });
        //
        return htmlContent;
    }

    // Function to show toast
    function showToast(message, msg_color) {
        const toastContainer = document.getElementById('toastContainer');
        const toastElement = document.getElementById('liveToast');
        toastElement.querySelector('.toast-header').classList.add(msg_color);
        const toastBody = toastElement.querySelector('.toast-body');
        toastBody.textContent = message;

        // Initialize the toast
        const toast = new bootstrap.Toast(toastElement);

        // Show the toast
        toast.show();
    }

    async function fetchModal(apiUrl, action, id=null)
    {
        fetch(apiUrl, {
            method: 'POST',
            body: new URLSearchParams({
                action: action,
                id: id
            })
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
                    liveForm.open();
                    liveForm.recKey = returnData.data.post_id;
                    liveForm.title.innerText = returnData.data.modal_title;
                    liveForm.form.querySelector('.inputWrapper').innerHTML = returnData.data.form_template;
                    // alert(returnData.success);return;
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
        //
        //
        //
        // const response = await fetch(`${apiUrl}?${queryStr}`);
        // const data = await response.json();
        // liveForm.open();
        // liveForm.title.innerText = data[0].modal_title;
        // liveForm.form.querySelector('.inputWrapper').innerHTML = data[0].form_template;
    }


    function FormTemplate(xModal)
    {
        this.mWindow = new bootstrap.Modal(xModal, {
            backdrop: 'static',
            keyboard: false,
            draggable: true
        });

        this.open = function() {
            this.mWindow.show();
        }

        this.close = function() {
            this.mWindow.hide();
        }

        this.dialog = xModal.querySelector('.modal-dialog');
        this.header = xModal.querySelector('.modal-header');
        this.title = xModal.querySelector('.modal-title');
        this.xButton = xModal.querySelector('.btn-close');

        this.body = xModal.querySelector('.modal-body');
        this.form = xModal.querySelector('form');

        this.footer = xModal.querySelector('.modal-footer');
        this.closeButton = xModal.querySelector('.close-btn');
        this.saveButton = xModal.querySelector('.save-btn');
        this.savRecord = ''; // Snap initial record before changes
        this.recKey = ''; // Request data key

        this.SetDataAction = function(dAction = "") // Set or Reset data-action property
        {
            this.dataAction = dAction; // Set or Reset

            this.saveButton.classList.remove("save-btn", "chg-btn", "del-btn");

            if(this.dataAction === "add") {
                this.saveButton.hidden=false;
                this.saveButton.classList.add("save-btn");
                this.saveButton.innerText = "Save";
            }
            else if(this.dataAction === "edit") {
                this.saveButton.hidden=false;
                this.saveButton.classList.add("chg-btn");
                this.saveButton.innerText = "Update";
            }
            else if(this.dataAction === "viu") {
                this.saveButton.hidden=true;
            }
            else if(this.dataAction === "del") {
                this.saveButton.hidden=false;
                this.saveButton.classList.add("del-btn");
                this.saveButton.innerText = "Delete";
            }
        }
    }

</script>
</body>
</html>