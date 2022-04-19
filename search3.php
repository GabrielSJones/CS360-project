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
	
	$phonequery = "SELECT * FROM phones NATURAL JOIN
	(SELECT PlanID, ItemID as PhoneID
	FROM provides NATURAL JOIN plans
	WHERE lower(Name) = lower('$name') AND lower(ItemType) = lower('phone') ) as inputPlanID";
	
	$internetquery = "SELECT * FROM internet NATURAL JOIN
	(SELECT PlanID, ItemID as InternetID
	FROM provides NATURAL JOIN plans
	WHERE lower(Name) = lower('$name') AND lower(ItemType) = lower('internet') ) as inputPlanID";
	
	$televisionquery = "SELECT * FROM televisions NATURAL JOIN
	(SELECT PlanID, ItemID as TVID
	FROM provides NATURAL JOIN plans
	WHERE lower(Name) = lower('$name') AND lower(ItemType) = lower('television') ) as inputPlanID";
	
	
	$phonesresult = $conn->query($phonequery);
	$internetresult = $conn->query($internetquery);
	$televisionresult = $conn->query($televisionquery);
	
	echo "<center>";
	echo "<h2>PHONES:</h2>";
	echo "</center>";
	if ($phonesresult->num_rows > 0)
	{
		//echo "PHONES:";
		while ($row = $phonesresult->fetch_assoc())
		{
			echo "<center>";
			echo "Screen Size: " . $row["ScreenSize"] . " - Name: " . $row["Name"] . " - Manufacturer: " . $row["Manufacturer"] . " - Refresh Rate: " . $row["RefreshRate"] . "<br>";
			echo "</center>";
		}
	}
	else
	{	
		echo "<center>";
		echo "No phone(s) included in this plan";
		echo "</center>";
	}
	
	echo "<br>";
	echo "<center>";
	echo "<h2>INTERNET:</h2>";
	echo "</center>";
	if ($internetresult->num_rows > 0)
	{
		//echo "INTERNET:";
		while ($row = $internetresult->fetch_assoc())
		{
			echo "<center>";
			echo " - Download Speed: " . $row["DownloadSpeed"] . " - Upload Speed: " . $row["UploadSpeed"] . " - Name: " . $row["Name"] . " - DataCap: " . $row["DataCap"] . " - Internet Type: " . $row["InternetType"] . "<br>";
			echo "</center>";
		}
	}
	else
	{	
		echo "<center>";
		echo "No internet provided in this plan";
		echo "</center>";
	}
	
	echo "<br>";
	echo "<center>";
	echo "<h2>TELEVISIONS:</h2>";
	echo "</center>";
	if ($televisionresult->num_rows > 0)
	{
		//echo "TELEVISIONS:";
		while ($row = $televisionresult->fetch_assoc())
		{
			echo "<center>";
			echo " - Resolution: " . $row["Resolution"] . " - Screen Size (inches): " . $row["Size"] . " - Screen Type: " . $row["ScreenType"] . " - HDR Compatible: " . $row["HDR"] . " - Name: " . $row["Name"] . " - Manufacturer: " . $row["Manufacturer"] . " - Refresh Rate: " . $row["RefreshRate"] . "<br>";
			echo "</center>";
		}
	}
	else
	{	
		echo "<center>";
		echo "No television(s) included in this plan";
		echo "</center>";
	}
	
	$conn->close();
	
?>