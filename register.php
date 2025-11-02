<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'courtship_program');

// Set headers for AJAX response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Response array
$response = array();

try {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Check if form is submitted via POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }
    
    // Sanitize and validate input data
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $attendance = trim($_POST['attendance'] ?? '');
    $partnerName = trim($_POST['partnerName'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    $errors = array();
    
    if (empty($firstName) || strlen($firstName) < 2) {
        $errors[] = "First name is required and must be at least 2 characters";
    }
    
    if (empty($lastName) || strlen($lastName) < 2) {
        $errors[] = "Last name is required and must be at least 2 characters";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required";
    }
    
    if (empty($phone) || !preg_match('/^[\d\s\+\-\(\)]+$/', $phone)) {
        $errors[] = "Valid phone number is required";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    
    if (empty($attendance)) {
        $errors[] = "Attendance mode is required";
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM courtship_registrations WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'emailExists' => true, 'message' => 'Email already registered']);
exit;
    }
    $stmt->close();
    
    // Prepare SQL statement
    $sql = "INSERT INTO courtship_registrations (first_name, last_name, email, phone, category, attendance_mode, partner_name, message, registration_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $category, $attendance, $partnerName, $message);
    
    // Execute statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Registration successful! We will contact you soon.";
        $response['registration_id'] = $stmt->insert_id;
        
        // Send confirmation email (optional)
        sendConfirmationEmail($email, $firstName, $lastName);
        
    } else {
        throw new Exception("Error executing statement: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

// Send JSON response
echo json_encode($response);

// Function to send confirmation email
function sendConfirmationEmail($email, $firstName, $lastName) {
    $to = $email;
    $subject = "Registration Confirmation - Couples In Courtship Mentorship Program 2026";
    
    $message = "
    <html>
    <head>
        <title>Registration Confirmation</title>
    </head>
    <body>
        <h2>Dear $firstName $lastName,</h2>
        <p>Thank you for registering for the <strong>Couples In Courtship Mentorship Program 2026</strong>.</p>
        
        <h3>Program Details:</h3>
        <ul>
            <li><strong>Duration:</strong> January - June, 2026</li>
            <li><strong>Schedule:</strong> Every Sunday Evening, 5:00 PM - 7:00 PM</li>
            <li><strong>Commencement:</strong> 11th January, 2026</li>
            <li><strong>Venue:</strong> The Rhema House, Cecelia Building, Beside Monarch Plaza, Adjacent Omololu Hospital, Ojurin-Akobo, Ibadan</li>
        </ul>
        
        <p>We will contact you soon with further details about the program.</p>
        
        <p>If you have any questions, please feel free to contact us.</p>
        
        <p>Best regards,<br>
        <strong>Pastors Peniela & Oluwatoyin Akintujoye</strong><br>
        The Rhema House</p>
    </body>
    </html>
    ";
    
    // Headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: The Rhema House <noreply@theremahouse.org>" . "\r\n";
    
    // Send email
    mail($to, $subject, $message, $headers);
}
?>