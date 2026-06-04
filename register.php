<?php
	session_start();
	if(isset($_SESSION['username']))
		{
			header("Location:chat.php");
			exit();
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			font-family: Arial, sans-serif;
			background: linear-gradient(135deg, #f8fafc, #eef2e8);
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			padding: 20px;
		}

		.form-box {
			width: 100%;
			max-width: 460px;
			background: rgba(255, 255, 255, 0.95);
			padding: 28px;
			border-radius: 18px;
			box-shadow: 0 18px 50px rgba(15, 23, 42, 0.12);
			border: 1px solid rgba(148, 163, 184, 0.18);
		}

		h2 {
			margin: 0 0 22px;
			text-align: center;
			font-size: 2rem;
			color: #0f172a;
		}

		.field {
			display: grid;
			gap: 6px;
			margin-bottom: 4px;
		}

		label {
			font-size: 14px;
			font-weight: 600;
			color: #334155;
		}

		input {
			width: 100%;
			padding: 12px 14px;
			border: 1px solid #cbd5e1;
			border-radius: 10px;
			font-size: 14px;
			background: #f8fafc;
			color: #0f172a;
		}

		input:focus {
			outline: none;
			border-color: #6b8f5a;
			background: #fff;
			box-shadow: 0 0 0 4px rgba(107, 143, 90, 0.14);
		}

		.error {
			color: #dc2626;
			font-size: 12px;
			min-height: 16px;
		}

		button {
			width: 100%;
			padding: 13px;
			border: 0;
			border-radius: 10px;
			background: linear-gradient(135deg, #6b8f5a, #4f7a68);
			color: #fff;
			font-size: 15px;
			font-weight: 600;
			cursor: pointer;
			margin-top: 4px;
		}

		button:hover {
			filter: brightness(0.96);
		}

		.message {
			margin-top: 1px;
			font-size: 14px;
			text-align: center;
			min-height: 18px;
		}

		.signup-line {
			text-align: center;
			font-size: 14px;
			color: #334155;
		}

		.signup-line a {
			color: #4f7a68;
			text-decoration: none;
			font-weight: 600;
		}

		.signup-line a:hover {
			text-decoration: underline;
		}

		@media (max-width: 480px) {
			.form-box {
				padding: 20px;
			}

			h2 {
				font-size: 1.7rem;
			}
		}
	</style>
</head>
<body>
	<div class="form-box">
		<h2>Register</h2>
		<form id="registerForm" method="POST" action="save_user.php">
			<div class="field">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="e.g., alex99" required>
				<div class="error" id="usernameError"></div>
			</div>

			<div class="field">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" placeholder="e.g., name@example.com" required>
				<div class="error" id="emailError"></div>
			</div>

			<div class="field">
				<label for="phone">Phone No.</label>
				<input type="tel" id="phone" name="phone" placeholder="e.g., 9876543210" 
                minlength=10 maxlength=10 pattern="[0-9]{10}" required>
				<div class="error" id="phoneError"></div> 
			</div>

			<div class="field">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="At least 8 characters" required>
				<div class="error" id="passwordError"></div>
			</div>

			<div class="field">
				<label for="confirmPassword">Confirm Password</label>
				<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter password" required>
				<div class="error" id="confirmPasswordError"></div>
			</div>

			<button type="submit">Register</button>
			<div class="message" id="message"></div>
		</form>

		<div class="signup-line">Already have an account? <a href="login.php">Login</a></div>
	</div>
</body>
</html>
