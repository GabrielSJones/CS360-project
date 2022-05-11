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
	
	// prints a table of results from a query
	function printQueryResults($results, $attributes, $headers, $table)
	{
		if ($results->num_rows > 0)
		{
			echo "<center> <h1>Results</h1> </center>";
			echo "<center>";
			echo "<table>";
			
			echo "<tr>";
			foreach ($headers as $header)
			{
				echo "<th>$header</th>";
			}
			echo "<th>Change</th>";
			echo "</tr>";
			
			while ($row = $results->fetch_assoc())
			{
				echo "<tr>";
				foreach ($attributes as $att)
				{
					echo "<td>" . $row["$att"] . "</td>";
				}
				echo "<td> <form action='edit.html' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='w-75 btn btn-primary'>Edit</button> </form>";
				echo "<form action='delete.php' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='w-75 btn btn-danger'>Delete</button> </form> </td>";
				echo "</tr>";
			}
			
			echo "</table>";
			echo "</center>";
		}
		else
		{
			echo "<center> <h1>No Results</h1> </center>";
		}
	}
	
	$screenSizeType = $_POST['ScreenSizeType'];
	$screenSize = $_POST['ScreenSize'];
	$refreshRateType = $_POST['RefreshRateType'];
	$refreshRate = $_POST['RefreshRate'];
	$manufacturer = $_POST['Manufacturer'];
	$screenSize = $_POST['ScreenSize'];
	
	if ($screenSizeType != "any" and $screenSize == "")
	{
		echo "<center> <h1>Error: Please enter a value for Screen Size</h1> </center>";
		die();
	}
	if ($refreshRateType != "any" and $refreshRate == "")
	{
		echo "<center> <h1>Error: Please enter a value for Refresh Rate</h1> </center>";
		die();
	}
	
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
	
	$query = "SELECT * FROM Phones";
	if ($screenSizeType != "any" or $refreshRateType != "any" or $manufacturer != "")
	{
		$query .= " WHERE";
		$first = TRUE;
		if ($screenSizeType != "any")
		{
			$query .= " ScreenSize $screenSizeType '$screenSize'";
			$first = FALSE;
		}
		if ($refreshRateType != "any")
		{
			if (!$first)
			{
				$query .= " AND";
			}
			$query .= " RefreshRate $refreshRateType '$refreshRate'";
			$first = FALSE;
		}
		if ($manufacturer != "")
		{
			if (!$first)
			{
				$query .= " AND";
			}
			$query .= " LOWER(Manufacturer) = LOWER('$manufacturer')";
		}
	}
	
	$results = $conn->query($query);
	$attributes = array("PhoneID", "Name", "ScreenSize", "Manufacturer", "RefreshRate");
	$headers = array("ID", "Name", "Screen Size", "Manufacturer", "Refresh Rate");
	printQueryResults($results, $attributes, $headers, "Phones");
	
?>