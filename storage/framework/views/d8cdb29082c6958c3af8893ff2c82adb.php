<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4">
                <h3 class="text-center mb-4">Sign Up</h3>

                <form id="signUpForm">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" required />
                        <div id="mailmessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="email">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required />
                        <div id="usernamemessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required />
                        <div id="passwordmessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="password">Confirm Password</label>
                        <input type="password" id="cpassword" name="cpassword" class="form-control" required />
                        <div id="cpasswordmessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block w-100 mb-4">Sign in</button>

                    <div class="text-center">
                        <p>Already a member? <a href="/login">Login</a></p>
                    </div>

                    <div id="message" class="text-danger mt-3 text-center"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#signUpForm').submit(function (e) {
        e.preventDefault();
        $('#message').text('');
        $('#usernamemessage').text('');
        $('#mailmessage').text('');
        $('#passwordmessage').text('');
        $('#cpasswordmessage').text('');
        if(!$('#username').val()){
          $('#usernamemessage').text('Username is required.');
          return false
        }
        if(!$('#email').val()){
          $('#mailmessage').text('Email is required.');
          return false
        }
        if(!$('#password').val()){
          $('#passwordmessage').text('Password is required.');
          return false
        }
        var password = $('#password').val();
        if(password.length < 5){
          $('#passwordmessage').text('Password legth is less than 5.');
          return false
        }
        if(!$('#cpassword').val()){
          $('#cpasswordmessage').text('Confirm Password is required.');
          return false
        }
        var cpassword = $('#cpassword').val();
        if(password != cpassword){
          $('#cpasswordmessage').text('Password is not matching.');
          return false
        }
        $.ajax({
            url: "<?php echo e(route('signup.submit')); ?>",
            method: "POST",
            data: {
                name: $('#username').val(),
                email: $('#email').val(),
                password: password,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#message').removeClass('text-danger').addClass('text-success').text('Signup successful!');
                window.location.href = '/login';
            },
            error: function (xhr) {
                $('#message').text(xhr.responseJSON?.message || 'Signup failed.');
            }
        });
    });
</script>

</body>
</html>
<?php /**PATH /home/kombanstech/Desktop/project_managment/resources/views/auth/signup.blade.php ENDPATH**/ ?>