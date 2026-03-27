<?php
// Retrieve the previous submission count from the hidden form field or start at zero.
$counter = isset($_POST['counter']) ? (int)$_POST['counter'] : 0;

$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = $passErr = $termsErr = "";
$name = $email = $website = $comment = $gender = $phone = $password = $confirmPass = "";
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
    $counter++; // Increment the attempt counter each time the user clicks the submit button.

    // Validate name to ensure it is not empty and contains only allowed characters like letters and dots.
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-'. ]*$/", $name)) {
            $nameErr = "Only letters, dots, and white space allowed";
        }
    }

    // Check if the email is provided and follows a standard valid email format.
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Verify the phone number is not empty and matches the required numeric format pattern.
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[+]?[0-9 \-]{7,15}$/", $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    // Validate the website URL format only if the user has entered data into the field.
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format (include http://)"; 
        }
    }

    // Ensure both password fields are filled, meet length requirements, and match each other perfectly.
    if (empty($_POST["password"]) || empty($_POST["confirmPass"])) {
        $passErr = "Both password fields are required";
    } else {
        $password = test_input($_POST["password"]);
        $confirmPass = test_input($_POST["confirmPass"]);
        
        if (strlen($password) < 8) {
            $passErr = "Password must be at least 8 characters long";
        } elseif ($password !== $confirmPass) {
            $passErr = "Passwords do not match";
        }
    }

    $comment = empty($_POST["comment"]) ? "" : test_input($_POST["comment"]);

    // Check that a gender option has been selected by the user.
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Verify that the user has explicitly checked the agreement to terms and conditions.
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
}

// Cleanse user input by removing extra spaces, backslashes, and converting special characters to HTML entities.
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Determine if the entire form is valid by checking if submission occurred without any error messages.
$formValid = $submitted && empty($nameErr) && empty($emailErr) && empty($genderErr) && empty($phoneErr) && empty($websiteErr) && empty($passErr) && empty($termsErr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern PHP Form</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --error-red: #ef4444;
            --success-green: #10b981;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .field-row { margin-bottom: 20px; display: flex; flex-direction: column; }
        label { font-weight: 600; font-size: 0.875rem; margin-bottom: 6px; }
        input[type="text"], input[type="password"], textarea { 
            width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; 
        }
        .error { color: var(--error-red); font-size: 0.8rem; margin-top: 4px; }
        .success-box { background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;}
        button { width: 100%; background: var(--primary-color); color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .output-box { margin-top: 24px; padding: 16px; background: #f3f4f6; border-radius: 8px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Get in Touch</h2>
    <p>Fields marked with <span style="color:var(--error-red)">*</span> are required.</p>

    <?php if ($formValid): ?>
        <div class="success-box">Form submitted successfully!</div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <input type="hidden" name="counter" value="<?= $counter ?>">

        <div class="field-row">
            <label>Name *</label>
            <input type="text" name="name" value="<?= $name ?>">
            <span class="error"><?= $nameErr ?></span>
        </div>

        <div class="field-row">
            <label>E-mail *</label>
            <input type="text" name="email" value="<?= $email ?>">
            <span class="error"><?= $emailErr ?></span>
        </div>

        <div class="field-row">
            <label>Phone Number *</label>
            <input type="text" name="phone" placeholder="+63..." value="<?= $phone ?>">
            <span class="error"><?= $phoneErr ?></span>
        </div>

        <div class="field-row">
            <label>Website</label>
            <input type="text" name="website" value="<?= $website ?>">
            <span class="error"><?= $websiteErr ?></span>
        </div>

        <div class="field-row">
            <label>Password *</label>
            <input type="password" name="password">
        </div>
        <div class="field-row">
            <label>Confirm Password *</label>
            <input type="password" name="confirmPass">
            <span class="error"><?= $passErr ?></span>
        </div>

        <div class="field-row">
            <label>Gender *</label>
            <div style="display: flex; gap: 10px;">
                <label><input type="radio" name="gender" value="Female" <?= ($gender=="Female")?"checked":"" ?>> Female</label>
                <label><input type="radio" name="gender" value="Male" <?= ($gender=="Male")?"checked":"" ?>> Male</label>
            </div>
            <span class="error"><?= $genderErr ?></span>
        </div>

        <div class="field-row">
            <label>
                <input type="checkbox" name="terms" <?= isset($_POST['terms']) ? "checked" : "" ?>> 
                I agree to the terms and conditions *
            </label>
            <span class="error"><?= $termsErr ?></span>
        </div>

        <button type="submit">Send Message</button>
    </form>

    <div class="output-box">
        <p><strong>Submission attempt:</strong> <?= $counter ?></p>
        
        <?php if ($submitted && $formValid): ?>
            <hr>
            <h3>Your Input:</h3>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Gender:</strong> <?= $gender ?></p>
        <?php else: ?>
            <p>Results will appear here.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>