<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Blog System</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1a252f;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" action="/" method="POST">
            @csrf
            <input type="email" id="email" name="email" placeholder="Email Address" required />
            <input type="password" id="password" name="password" placeholder="Password" required />
            <button type="submit" id="loginbtn">🔐 Login</button>
        </form>
        <div class="link">
            Don't have an account? <a href="#">Register</a>
        </div>
    </div>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $("#loginbtn").on("click", function(event) {
        event.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: "/api/login",
            type: "POST",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: JSON.stringify({
                email: email,
                password: password
            }),
            success: function(response) {
                // ✅ Token save
                localStorage.setItem('token', response.token);

                // ✅ Name save (Assuming API returns user object)
                if (response.user && response.user.name) {
                    localStorage.setItem('name', response.user.name);
                }

                swal("Success!", "You have logged in successfully!", "success")
                    .then(() => {
                        window.location.href = "http://127.0.0.1:8000/blog";
                    });
            },
            error: function(xhr, status, error) {
                console.error("Login failed:", xhr.responseText);
                swal("Error!", "Login failed. Please check your credentials.", "error");
            }
        });
    });
</script>
