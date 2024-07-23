<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "potfilio";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send OTP (for example, via email)
function sendOTP($email, $otp) {
    // Use mail() function or any other email sending library/service
    $subject = "Your OTP Code";
    $message = "Your OTP code is $otp.";
    $headers = "From: no-reply@yourdomain.com";

    return mail($email, $subject, $message, $headers);
}

// Function to generate OTP
function generateOTP() {
    return rand(100000, 999999); // Generate a 6-digit OTP
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $otp = $_POST['otp'];
    $remember_me = isset($_POST['remember_me']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    if (!verifyRecaptcha($recaptcha_response)) {
        echo "reCAPTCHA verification failed!";
        exit();
    }

    // Retrieve user from the database
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Generate and send OTP
            $generated_otp = generateOTP();
            if (sendOTP($email, $generated_otp)) {
                // Store OTP in session for later verification
                session_start();
                $_SESSION['generated_otp'] = $generated_otp;
                $_SESSION['email'] = $email;
                $_SESSION['remember_me'] = $remember_me;
                
                echo "OTP sent to your email!";
                // Redirect to OTP verification page
                header("Location: "C:\xampp\htdocs\project\potfilio\adnim dashboard\admin panel.php"");
                exit();
            } else {
                echo "Failed to send OTP!";
            }
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "Invalid email or password!";
    }
}

$conn->close();
?>
