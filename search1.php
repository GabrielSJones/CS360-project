<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="navbar.css">
		<link rel="stylesheet" href="table.css">
		
		<style>
			.bd-placeholder-img
			{
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				user-select: none;
			}

			@media (min-width: 768px)
			{
				.bd-placeholder-img-lg
				{
					font-size: 3.5rem;
				}
			}
		</style>
	</head>
	<body>
		<main>
		<!-- Search dropdown and +Add button at the top -->
			<nav class="navbar navbar-expand-sm navbar-dark bg-dark" aria-label="Third navbar example">
				<div class="container-fluid">
					<div class="collapse navbar-collapse" id="navbarsExample03">
						<ul class="navbar-nav me-auto mb-2 mb-sm-0">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Search</a>
								<ul class="dropdown-menu" aria-labelledby="dropdown03">
									<li><a class="dropdown-item" href="search1.html">Type 1</a></li>
									<li><a class="dropdown-item" href="search2.html">Type 2</a></li>
									<li><a class="dropdown-item" href="search3.html">Type 3</a></li>
								</ul>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="add.html">+Add</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</main>
		<script src="bootstrap.bundle.min.js"></script>
	</body>
</html>

<?php
	
	// gets the user inputs from the search bar
	$name = $_POST['name'];
	
	// variables used to log into the mysql server
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "company_database";
	
	// connects to the mysql server and crashes if it can't connect
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$q = "SELECT * FROM phones WHERE Name = '$name'";
	$result = $conn->query($q);
	
	if ($result->num_rows > 0)
	{
		echo "<center>";
		echo "<h1>Phones</h1>";
		echo "<table>";
		echo "<tr> <th>PhoneID</th> <th>Screen Size</th> <th>Name</th> <th>Manufacturer</th> <th>Refresh Rate</th> </tr>";
		while ($row = $result->fetch_assoc())
		{
			echo "<tr>";
			echo "<td>" . $row["PhoneID"] . "</td>";
			echo "<td>" . $row["ScreenSize"] . "</td>";
			echo "<td>" . $row["Name"] . "</td>";
			echo "<td>" . $row["Manufacturer"] . "</td>";
			echo "<td>" . $row["RefreshRate"] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</center>";
	}
	else
	{
		echo "No results";
	}
	
	$conn->close();
	
?>