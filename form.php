<?php
session_start();

// Initialize variables
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = $passErr = $termsErr = "";
$name = $email = $website = $gender = $phone = $password = $confirmPass = "";
$formValid = false;

// Handle Submission Attempt Counter using Session
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['attempts']++;

    // Validation Logic
    if (empty($_POST["name"])) { $nameErr = "Name is required"; } 
    else { $name = test_input($_POST["name"]); }

    if (empty($_POST["email"])) { $emailErr = "Email is required"; } 
    else { $email = test_input($_POST["email"]); }

    if (empty($_POST["phone"])) { $phoneErr = "Phone number is required"; } 
    else { $phone = test_input($_POST["phone"]); }

    $website = test_input($_POST["website"]);

    if (empty($_POST["password"])) { $passErr = "Password is required"; } 
    else {
        $password = test_input($_POST["password"]);
        $confirmPass = test_input($_POST["confirmPass"]);
        if ($password !== $confirmPass) { $passErr = "Passwords do not match"; }
    }

    if (empty($_POST["gender"])) { $genderErr = "Gender is required"; } 
    else { $gender = test_input($_POST["gender"]); }

    if (!isset($_POST["terms"])) { $termsErr = "Required"; }

    // Final Check
    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($passErr) && empty($genderErr) && isset($_POST["terms"])) {
        $formValid = true;
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get in Touch</title>
    <style>
        :root {
            --primary-color: #5d50e6;
            --bg-color: #f8f9fa;
            --success-bg: #e6fffa;
            --success-text: #2d3748;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        h2 { margin-top: 0; color: #1a202c; font-size: 24px; }
        p.subtext { color: #718096; font-size: 14px; margin-bottom: 20px; }
        .required { color: #e53e3e; }

        .success-banner {
            background-color: var(--success-bg);
            border: 1px solid #b2f5ea;
            color: #2c7a7b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .form-group { margin-bottom: 18px; }
        
        label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            font-size: 14px;
            color: #2d3748;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            outline: none;
        }

        input[type="text"]:focus { border-color: var(--primary-color); }

        .radio-group { display: flex; gap: 15px; align-items: center; margin-top: 5px; }
        .checkbox-group { margin: 20px 0; font-size: 14px; display: flex; align-items: center; gap: 8px; }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.2s;
        }

        .btn-submit:hover { opacity: 0.9; }

        /* Output Box Styling */
        .output-box {
            background-color: #f1f5f9;
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            border-top: 2px solid #cbd5e0;
        }

        .output-box h3 { margin-top: 0; font-size: 18px; }
        .output-item { margin-bottom: 8px; font-size: 15px; }
        .output-label { font-weight: bold; }
        .error { color: #e53e3e; font-size: 12px; margin-top: 4px; display: block; }
    </style>
</head>
<body>

<div class="container">
    <h2>Get in Touch</h2>
    <p class="subtext">Fields marked with <span class="required">*</span> are required.</p>

    <?php if ($formValid): ?>
        <div class="success-banner">Form submitted successfully!</div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Name <span class="required">*</span></label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label>E-mail <span class="required">*</span></label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>
        </div>

        <div class="form-group">
            <label>Phone Number <span class="required">*</span></label>
            <input type="text" name="phone" value="<?php echo $phone; ?>">
            <span class="error"><?php echo $phoneErr; ?></span>
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="text" name="website" value="<?php echo $website; ?>">
        </div>

        <div class="form-group">
            <label>Password <span class="required">*</span></label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>Confirm Password <span class="required">*</span></label>
            <input type="password" name="confirmPass">
            <span class="error"><?php echo $passErr; ?></span>
        </div>

        <div class="form-group">
            <label>Gender <span class="required">*</span></label>
            <div class="radio-group">
                <label style="font-weight: normal;"><input type="radio" name="gender" value="Female" <?php if($gender=="Female") echo "checked";?>> Female</label>
                <label style="font-weight: normal;"><input type="radio" name="gender" value="Male" <?php if($gender=="Male") echo "checked";?>> Male</label>
            </div>
            <span class="error"><?php echo $genderErr; ?></span>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="terms" id="terms">
            <label for="terms" style="margin-bottom: 0; font-weight: normal;">I agree to the terms and conditions <span class="required">*</span></label>
            <span class="error"><?php echo $termsErr; ?></span>
        </div>

        <button type="submit" class="btn-submit">Send Message</button>
    </form>

    <?php if ($_SESSION['attempts'] > 0): ?>
    <div class="output-box">
        <p><strong>Submission attempt:</strong> <?php echo $_SESSION['attempts']; ?></p>
        <hr style="border: 0; border-top: 1px solid #cbd5e0; margin: 15px 0;">
        <h3>Your Input:</h3>
        <div class="output-item"><span class="output-label">Name:</span> <?php echo $name; ?></div>
        <div class="output-item"><span class="output-label">Phone:</span> <?php echo $phone; ?></div>
        <div class="output-item"><span class="output-label">Email:</span> <?php echo $email; ?></div>
        <div class="output-item"><span class="output-label">Gender:</span> <?php echo $gender; ?></div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>