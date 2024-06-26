<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>BlogDaily</title>
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
        <form id="login-form">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="email" name="email" placeholder="Email Address" required="">
            <input type="password" name="password" placeholder="Password" required="">
            <button class="login-btn" type="submit">Login</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('click', ClickHandler);

    // window["entryForm"] = new EntryFormTemplate(signupForm, loginForm);

    function ClickHandler(event)
    {
        let eln = event.target;

        if(eln.matches('.signup-btn'))
        {
            const apiUrl = '/register';
            let queryCue = new FormData(document.getElementById('signup-form'));
            Save1Record(apiUrl, queryCue);

        }
        else if(eln.matches('.login-btn'))
        {
            event.preventDefault();
            const apiUrl = '/login';
            let queryCue = new FormData(document.getElementById('login-form'));
            Save1Record(apiUrl, queryCue);

        }
    }

    function Save1Record(apiUrl, queryCue)
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
                    window.location.href = returnData.redirect;
                    // alert(returnData.success);
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
