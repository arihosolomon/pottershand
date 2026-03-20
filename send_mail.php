<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Your details — change these
    $to       = "info@pottershand-foundation.org";       // <-- YOUR email address
    $siteName = "Potters Hand Foundation";      // <-- YOUR restaurant name

    // Collect and sanitize form data
    $name    = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email   = htmlspecialchars(strip_tags(trim($_POST["email"])));
    $subject = htmlspecialchars(strip_tags(trim($_POST["subject"])));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

    // Validate that fields are not empty
    if (empty($name) || empty($email) || empty($message) || empty($subject)) {
        echo json_encode(["status" => "error", "msg" => "Please fill in all fields."]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "msg" => "Please enter a valid email address."]);
        exit;
    }

    // Build the email
    $emailSubject = "$siteName Contact Form: $subject";
    $emailBody    = "You have received a new message from your website contact form.\n\n";
    $emailBody   .= "Name:    $name\n";
    $emailBody   .= "Email:   $email\n";
    $emailBody   .= "Subject: $subject\n\n";
    $emailBody   .= "Message:\n$message\n";

    $headers  = "From: $siteName <no-reply@pottershand-foundation.org>\r\n"; // <-- YOUR domain
    $headers .= "Reply-To: $name <$email>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send the email
    if (mail($to, $emailSubject, $emailBody, $headers)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Mail server error. Please try again later."]);
    }

} else {
    echo json_encode(["status" => "error", "msg" => "Invalid request."]);
}
?>