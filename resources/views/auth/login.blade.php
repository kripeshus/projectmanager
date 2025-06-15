<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4">
                <h3 class="text-center mb-4">Sign In</h3>

                <form id="loginForm">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" required />
                        <div id="mailmessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required />
                        <div id="passwordmessage" class="text-danger mt-3 text-center"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block w-100 mb-4">Sign in</button>

                    <div class="text-center">
                        <p>Not a member? <a href="/signup">Register</a></p>
                    </div>

                    <div id="message" class="text-danger mt-3 text-center"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#loginForm').submit(function (e) {
        e.preventDefault();
        $('#message').text('');
        $('#mailmessage').text('');
        $('#passwordmessage').text('');
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
        $.ajax({
            url: "{{ route('login.submit') }}",
            method: "POST",
            data: {
                email: $('#email').val(),
                password: $('#password').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#message').removeClass('text-danger').addClass('text-success').text('Login successful!');
                window.location.href = '/project';
            },
            error: function (xhr) {
                $('#message').text(xhr.responseJSON?.message || 'Login failed.');
            }
        });
    });
</script>

</body>
</html>
