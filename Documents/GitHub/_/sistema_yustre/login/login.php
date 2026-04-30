<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENTRAR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/login/style.css">
</head>

<body>

    <?php
    // Mostrar errores si existen
    if (isset($_GET['error'])) {
        $error_message = '';

        switch ($_GET['error']) {
            case 'empty_fields':
                $error_message = 'Please fill in all fields';
                break;
            case 'invalid_email':
                $error_message = 'Invalid email format';
                break;
            case 'invalid_credentials':
                $error_message = 'Incorrect email or password';
                break;
            case 'db_error':
                $error_message = 'Database connection error';
                break;
        }

        if ($error_message) {
            echo '<div class="alert alert-danger alert-floating" role="alert">';
            echo '<strong>Error!</strong> ' . $error_message;
            echo '</div>';
        }
    }
    ?>

    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">

                    <div class="login-box">
                        <div class="text-center mb-4">
                            <h2 class="login-title">WELCOME BACK TO THE RANCH</h2>
                            <p class="login-subtitle">Please login to your account</p>
                        </div>

                        <form id="loginForm" method="POST" action="/login/process_login.php">

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control custom-input" id="email" name="email"
                                    placeholder="Enter your email" required>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control custom-input" id="password" name="password"
                                        placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                        title="Show/hide password"
                                        style="border-color: var(--border-color, #ced4da); background: transparent;">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-3">
                                <div class="form-check terms-check">
                                    <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                    <label class="form-check-label terms-label" for="termsCheck">
                                        I agree to the
                                        <a href="#" class="terms-link" data-bs-toggle="modal"
                                            data-bs-target="#termsModal">
                                            Terms and Conditions
                                        </a>
                                    </label>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-login">
                                    Login
                                </button>
                            </div>

                            <!-- Forgot Password Link -->
                            <div class="text-center">
                                <a href="#" class="forgot-password" data-bs-toggle="modal"
                                    data-bs-target="#forgotPasswordModal">
                                    Forgot your password?
                                </a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">📋 Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body terms-body">

                    <h6>1. Acceptance of Terms</h6>
                    <p>By accessing and using the <strong>Sistema Yustre</strong> cattle management platform, you agree
                        to be bound by these Terms and Conditions. If you do not agree, you must not use this system.
                    </p>

                    <h6>2. Authorized Use</h6>
                    <p>This system is intended exclusively for authorized personnel of Rancho Yustre. Access credentials
                        are personal and non-transferable. You are responsible for maintaining the confidentiality of
                        your password and all activities that occur under your account.</p>

                    <h6>3. Data Privacy</h6>
                    <p>All information entered into this system — including animal records, medical data, and employee
                        information — is confidential and property of Rancho Yustre. You agree not to share, copy, or
                        distribute any data obtained through this platform without explicit authorization.</p>

                    <h6>4. Data Accuracy</h6>
                    <p>Users are responsible for the accuracy of the data they enter. Intentionally entering false or
                        misleading information may result in account suspension and legal action.</p>

                    <h6>5. Security</h6>
                    <p>You agree not to attempt to bypass, disable, or circumvent any security measures of this system.
                        Any unauthorized access attempts will be logged and reported.</p>

                    <h6>6. System Availability</h6>
                    <p>Rancho Yustre does not guarantee uninterrupted availability of this system. Scheduled maintenance
                        or unforeseen technical issues may cause temporary downtime.</p>

                    <h6>7. Modifications</h6>
                    <p>Rancho Yustre reserves the right to modify these Terms and Conditions at any time. Continued use
                        of the system after changes constitutes acceptance of the updated terms.</p>

                    <h6>8. Contact</h6>
                    <p>For questions regarding these terms, please contact your system administrator.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                        onclick="document.getElementById('termsCheck').checked=true">
                        ✅ I Accept
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot your password?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ffffff" viewBox="0 0 16 16" class="mb-3" style="opacity:0.85;">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    </svg>
                    <p class="mb-2" style="color:#ffffff;">Para restablecer tu contrasena, por favor <strong>comunicate con un administrador</strong>.</p>
                    <p class="small" style="color:rgba(255,255,255,0.62);">El administrador podra cambiarla directamente desde el panel de administracion del sistema.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="/login/script.js"></script>

</body>

</html>