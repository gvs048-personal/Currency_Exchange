<?php
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
	$phone_no = isset($_POST['phone_no']) ? strip_tags(trim($_POST['phone_no'])) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';
	$subject = "Enquiry from Oxford Street FX";

    // Validate form fields
    if (empty($name)) {
        $errors[] = 'Name is empty';
    }
	if (empty($phone_no)) {
        $errors[] = 'Phone No is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }

    // If no errors, send email
    if (empty($errors)) {
        // Recipient email address (replace with your own)
        $recipient = "rates@oxfordstreetfx.com";

        // Additional headers
        $headers = "From: $name <$email>";

        // Send email
        if (mail($recipient,$subject , $message, $headers)) {
            header("Location: emailconfirmation.html");
        } else {
            echo "Failed to send email. Please try again later.";
        }
    } else {
        // Display errors
        echo "The form contains the following errors:<br>";
        foreach ($errors as $error) {
            echo "- $error<br>";
        }
    }
} else {
    // Not a POST request, display a 403 forbidden error
    header("HTTP/1.1 403 Forbidden");
    echo "You are not allowed to access this page.";
}
?>