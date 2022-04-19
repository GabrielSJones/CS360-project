<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="navbar.css">
		<link rel="stylesheet" href="custom.css">
		
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
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">+Add</a>
								<ul class="dropdown-menu" aria-labelledby="dropdown03">
									<li><a class="dropdown-item" href="addphones.html">Phones</a></li>
									<li><a class="dropdown-item" href="addtvs.html">Televisions</a></li>
									<li><a class="dropdown-item" href="addinternet.html">Internet</a></li>
									<li><a class="dropdown-item" href="addplans.html">Plans</a></li>
									<li><a class="dropdown-item" href="addcompanies.html">Companies</a></li>
								</ul>
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
	
	// gets values that were submitted by the form
	$id = $_POST['ID'];
	$name = $_POST['Name'];
	$screenSize = $_POST['ScreenSize'];
	$manufacturer = $_POST['Manufacturer'];
	$refreshRate = $_POST['RefreshRate'];
	
	echo "<center>";
	echo "<table>";
	echo "<tr> <th>ID</th> <th>Name</th> <th>Screen Size</th> <th>Manufacturer</th> <th>Refresh Rate</th> </tr>";
	echo "<tr> <td>$id</td> <td>$name</td> <td>$screenSize</td> <td>$manufacturer</td> <td>$refreshRate</td> </tr>";
	echo "</table>";
	echo "</center>";
	
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
	
	// check to make sure primary key is not already taken
	$idCheck = "SELECT * FROM Phones WHERE PhoneID = '$id'";
	$checkResults = $conn->query($idCheck);
	// if it's not, then add the row to the table
	if ($checkResults->num_rows < 1)
	{
		$query = "INSERT INTO Phones (PhoneID, Name, ScreenSize, Manufacturer, RefreshRate) VALUES ('$id', '$name', '$screenSize', '$manufacturer', '$refreshRate')";
		if ($conn->query($query) === TRUE)
		{
			echo "<center> <h1>Row Added Successfully</h1> </center>";
		}
		else
		{
			echo "<center> <h1>Error: Row Was Not Added To The Table</h1> </center>";
		}
	}
	else
	{
		echo "<center> <h1>Error: Row Was Not Added To The Table (ID already taken)</h1> </center>";
	}
	
?>