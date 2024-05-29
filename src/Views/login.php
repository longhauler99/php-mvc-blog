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
<!--    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">-->
</head>
<body>
<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true" checked>

    <div class="signup">
<!--        <form id="signup-form" class="form" action="/xregister" method="POST">-->
        <form id="signup-form" class="form">
            <label for="chk" aria-hidden="true">Sign up</label>
            <input type="text" name="username" placeholder="User name" required="">
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="password" placeholder="Password" required="">
            <input type="password" name="password2" placeholder="Confirm Password" required="">
            <button class="signup-btn" type="button">Sign up</button>
        </form>
    </div>

    <div class="login">
        <form id="login-form" class="form" action="/login" method="POST">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="text" name="username" placeholder="User Name" required="">
            <input type="password" name="password" placeholder="Password" required="">
            <button class="login-btn" type="submit" name="login-button">Login</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('click', ClickHandler);

    let loginForm = document.getElementById('login-form');


    // window["entryForm"] = new EntryFormTemplate(signupForm, loginForm);

    function ClickHandler(event)
    {
        let eln = event.target;

        if(eln.matches('.signup-btn'))
        {
            event.preventDefault();
            let queryCue = new FormData(document.getElementById('signup-form'));
            // console.log(jsonForm(queryCue));
// return;
            const apiUrl = '/register';

            fetch(apiUrl, {
                method: 'POST',
                // headers: {
                //     'Content-Type': 'application/json',
                // },
                body: queryCue,
            })
                .then(response => {
                    if(!response.ok)
                    {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(returnData => {
                    if(returnData.success)
                    {
                        alert(returnData.success);
                    }
                    else if(returnData.error)
                    {
                        alert(returnData.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            // Save1Record('/register', entryForm.signupFormData);
            // console.log(entryForm.signupFormData);
        }
    }

    function Save1Record(apiUrl, saveData)
    {
    }

/*
    function EntryFormTemplate(entryFormData)
    {
        this.fData = new FormData();
        this.entryForm = JSON.stringify(entryForm);

        this.entryFormData = this.fData.append('x1', this.entryForm);
    }
*/

    function jsonForm(fd)
    {
        let jf = {};
        for (var pair of fd.entries()) {
            jf[pair[0]] = pair[1];
        }

        return JSON.stringify(jf);
    }


</script>
</body>
</html>
<!-- partial -->

</body>
</html>
