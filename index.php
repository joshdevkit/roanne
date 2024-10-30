<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MAO - San Mariano Department of Agriculture</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .input_class {
            padding: 1.5rem;
            border-radius: 10px;
        }

        .button {
            padding: 0.75rem;
            border-radius: 10px;
        }

        .input-control {
            padding: 1.5rem;
        }

        .border-top-success {
            border-top: 4px solid #28a745;
        }

        .input-group-text {
            background-color: #fff;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="col-md-5">
            <div class="card p-4 border-top-success">
                <div class="d-flex justify-content-center align-items-center">
                    <img class="w-25" src="image/seal.png" alt="Logo">
                </div>
                <div class="card-body">
                    <h5 class="text-center bg-success text-white p-2 rounded-sm">Municipality of Agriculture Office</h5>
                    <div id="message" class="text-center mt-3"></div>
                    <form action="actions/login.php" method="POST">
                        <div class="form-group mt-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Email"
                                    class="form-control <?php echo isset($_SESSION['error_email']) ? 'is-invalid' : ''; ?>"
                                    autofocus
                                    required>
                            </div>
                            <?php if (isset($_SESSION['error_email'])): ?>
                                <p class="text-danger"><?php echo $_SESSION['error_email']; ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Password"
                                    class="form-control <?php echo isset($_SESSION['error_password']) ? 'is-invalid' : ''; ?>"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['error_password'])): ?>
                                <p class="text-danger"><?php echo $_SESSION['error_password']; ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success btn-block">Login <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>