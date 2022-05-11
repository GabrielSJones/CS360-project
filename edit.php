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
	
	// outputs a table of items from $table that are going to be added to a plan
	function displayPlanItems($table, $relation, $id, $idName, $ids, $idsName, $conn)
	{
		$query = "";
		if ($relation == "Provides")
		{
			$itemType = "";
			$itemIDName = "";
			if ($table == "Phones")
			{
				$itemType = "phone";
				$itemIDName = "PhoneID";
			}
			elseif ($table == "Televisions")
			{
				$itemType = "television";
				$itemIDName = "TVID";
			}
			elseif ($table == "Internet")
			{
				$itemType = "internet";
				$itemIDName = "InternetID";
			}
			$query = "SELECT $table.Name as Name, $table.$itemIDName as ID FROM $table, $relation WHERE $relation.$idName = '$id' AND $relation.$idsName IN ('" . implode("', '", $ids) . "') AND $relation.$idsName = $table.$itemIDName AND $relation.ItemType = '$itemType'";
		}
		else
		{
			$query = "SELECT $table.Name as Name, $table.$idsName as ID FROM $table, $relation WHERE $relation.$idName = '$id' AND $relation.$idsName IN ('" . implode("', '", $ids) . "') AND $relation.$idsName = $table.$idsName";
		}
		$results = $conn->query($query);
		
		if ($results->num_rows > 0)
		{
			if ($relation == "Provides")
			{
				echo "<center> <h1>$table</h1> </center>";
			}
			
			echo "<center> <table class='inner'>";
			
			if ($relation == "Provides")
			{
				echo "<tr> <th>Name</th> <th>ItemID</th> </tr>";
				while ($row = $results->fetch_assoc())
				{
					echo "<tr> <td>" . $row["Name"] . "</td> <td>" . $row["ID"] . "</td> </tr>";
				}
			}
			else
			{
				echo "<tr> <th>ID</th> <th>Name</th> </tr>";
				while ($row = $results->fetch_assoc())
				{
					echo "<tr> <td>" . $row["ID"] . "</td> <td>" . $row["Name"] . "</td> </tr>";
				}
			}
			
			echo "</table> </center>";
		}
	}
	
	// prints out a row that is going to be updated in a table
	function displayRow($table, $header_atts, $values, $conn)
	{
		echo "<center>";
		echo "<table>";
		
		echo "<tr>";
		foreach ($header_atts as $att)
		{
			echo "<th> $att </th>";
		}
		echo "</tr>";
		
		echo "<tr>";
		for ($i = 0; $i < count($values); $i++)
		{
			if ($i == 3 and $table == "Plans")
			{
				echo "<td>";
				displayPlanItems("Companies", "Offers", $values[0], "PlanID", $values[$i], "CompanyID", $conn);
				echo "</td>";
			}
			elseif ($i > 3 and $table == "Plans")
			{
				echo "<td>";
				displayPlanItems("Phones", "Provides", $values[0], "PlanID", $values[$i], "ItemID", $conn);
				displayPlanItems("Televisions", "Provides", $values[0], "PlanID", $values[$i], "ItemID", $conn);
				displayPlanItems("Internet", "Provides", $values[0], "PlanID", $values[$i], "ItemID", $conn);
				echo "</td>";
				$i = count($values);
			}
			elseif ($i > 1 and $table == "Companies")
			{
				echo "<td>";
				displayPlanItems("Plans", "Offers", $values[0], "CompanyID", $values[$i], "PlanID", $conn);
				echo "</td>";
			}
			else
			{
				echo "<td> $values[$i] </td>";
			}
		}
		echo "</tr>";
		
		echo "</table>";
		echo "</center.";
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
	
	$table = $_POST['Table'];
	$attributes = array();
	$header_atts = array();
	$values = array();
	if ($table == "Phones")
	{
		$attributes = array("PhoneID", "Name", "ScreenSize", "Manufacturer", "RefreshRate");
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['ScreenSize'], $_POST['Manufacturer'], $_POST['RefreshRate']);
		array_push($header_atts, "ID", "Name", "Screen Size", "Manufacturer", "Refresh Rate");
	}
	elseif ($table == "Televisions")
	{
		$attributes = array("TVID", "Name", "Size", "ScreenType", "HDR", "Resolution", "RefreshRate", "Manufacturer");
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['Size'], $_POST['ScreenType'], $_POST['HDR'], $_POST['Resolution'], $_POST['RefreshRate'], $_POST['Manufacturer']);
		array_push($header_atts, "ID", "Name", "Size", "Screen Type", "HDR", "Resolution", "Refresh Rate", "Manufacturer");
	}
	elseif ($table == "Internet")
	{
		$attributes = array("InternetID", "Name", "DownloadSpeed", "UploadSpeed", "DataCap", "InternetType");
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['DownloadSpeed'], $_POST['UploadSpeed'], $_POST['DataCap'], $_POST['InternetType']);
		array_push($header_atts, "ID", "Name", "Download Speed", "Upload Speed", "Data Cap", "Internet Type");
	}
	elseif ($table == "Plans")
	{
		$attributes = array("PlanID", "Name", "Price");
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['Price'], $_POST['Companies'], $_POST['Phones'], $_POST['Televisions'], $_POST['Internet']);
		array_push($header_atts, "ID", "Name", "Price", "Company / Companies", "Provides");
	}
	elseif ($table == "Companies")
	{
		$attributes = array("CompanyID", "Name");
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['Plans']);
		array_push($header_atts, "ID", "Name", "Offers");
	}
	
	displayRow($table, $header_atts, $values, $conn);
	
?>