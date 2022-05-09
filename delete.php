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
	
	// outputs a table of items from $table that are provided by $planID
	function getItemsInPlan($planID, $table, $conn)
	{
		// gets the correct strings to input into the query based on the table it's pulling from
		$IDName = "";
		$item_type = "";
		if ($table == "Phones")
		{
			$IDName = "PhoneID";
			$item_type = "phone";
		}
		elseif ($table == "Televisions")
		{
			$IDName = "TVID";
			$item_type = "television";
		}
		else
		{
			$IDName = "InternetID";
			$item_type = "internet";
		}
		
		// submits the query to $table to find all items provided by $planID
		$query = "SELECT $table.Name AS Name, provides.ItemID AS ItemID FROM $table, provides WHERE provides.ItemType = '$item_type' AND provides.ItemID = $table.$IDName AND provides.PlanID = '$planID'";
		$result = $conn->query($query);
		
		// if there are any results, output them in a table
		if ($result->num_rows > 0)
		{
			echo "<center> <h2>$table</h2> </center>";
			echo "<center>";
			echo "<table class='inner'>";
			
			echo "<tr> <th>Name</th> <th>ItemID</th> </tr>";
			// prints out each row that returned from the query
			while ($row = $result->fetch_assoc())
			{
				echo "<tr> <td>" . $row["Name"] . "</td> <td>" . $row["ItemID"] . "</td> </tr>";
			}
			
			echo "</table>";
			echo "</center>";
		}
	}
	
	// outputs a table of rows from a table in the db
	function printTable($table, $attributes, $conn)
	{
		// submits the query to $table to find all rows that have a name value that matches $name exactly
		$query = "SELECT * FROM $table";
		$result = $conn->query($query);
		
		// if there are any results, output the table
		if ($result->num_rows > 0)
		{
			echo "<center> <h1>$table</h1> </center>";
			echo "<center>";
			echo "<table>";
			
			// prints each attribute name at the top of the table as table headers
			echo "<tr>";
			foreach ($attributes as $a)
			{
				echo "<th>$a</th>";
			}
			// if this is the Plans table, then print out 2 extra columns to show the companies that offers this plan and what devices / internet the plan provides
			if ($table == "Plans")
			{
				echo "<th>Company / Companies</th>";
				echo "<th>Provides</th>";
			}
			// print out an extra column header to label the edit and delete buttons
			echo "<th>Change</th>";
			echo "</tr>";
			// prints out each row that was returned from the query
			while ($row = $result->fetch_assoc())
			{
				echo "<tr>";
				foreach ($attributes as $a)
				{
					echo "<td>" . $row["$a"] . "</td>";
				}
				// if this is from the plans table, then print out the companies that offer this plan and all items that the plan offers in subtables
				if ($table == "Plans")
				{
					// prints out companies that offers this plan
					$comp_query = "SELECT Companies.CompanyID as ID, Companies.Name AS Name FROM Offers, Companies WHERE Offers.PlanID = '" . $row["PlanID"] . "' AND Offers.CompanyID = Companies.CompanyID";
					$comp_result = $conn->query($comp_query);
					echo "<td>";
					if ($comp_result->num_rows > 0)
					{
						echo "<center>";
						echo "<table class='inner'>";
						echo "<tr> <th>ID</th> <th>Name</th> </tr>";
						while ($comp_row = $comp_result->fetch_assoc())
						{
							echo "<tr> <td>" . $comp_row["ID"] . "</td> <td>" . $comp_row["Name"] . "</td> </tr>";
						}
						echo "</table>";
						echo "</center>";
					}
					echo "</td>";
					
					// prints out items that this plan provides
					echo "<td>";
					getItemsInPlan($row["PlanID"], "Phones", $conn);
					getItemsInPlan($row["PlanID"], "Televisions", $conn);
					getItemsInPlan($row["PlanID"], "Internet", $conn);
					echo "</td>";
				}
				// prints out the buttons to edit or delete this row at the end of the table
				echo "<td> <form action='edit.html' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='w-75 btn btn-primary'>Edit</button> </form>";
				echo "<form action='delete.php' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='w-75 btn btn-danger'>Delete</button> </form> </td>";
				echo "</tr>";
			}
			
			echo "</table>";
			echo "</center>";
		}
	}
	
	$itemInfo = $_POST['iteminfo'];
	$itemInfo = explode("+", $itemInfo);
	
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
	
	$itemID = "";
	$itemType = "";
	$attributes = array();
	if ($itemInfo[0] == "Plans")
	{
		$itemID = "PlanID";
		$attributes = array("PlanID", "Name", "Price");
		if ($conn->query("DELETE FROM Provides WHERE PlanID = '$itemInfo[1]'") === TRUE and $conn->query("DELETE FROM Offers WHERE PlanID = '$itemInfo[1]'") === TRUE)
		{
		}
		else
		{
			echo "<h1>Error: Row was not deleted</h1>";
			echo "<br>";
			die("Query Error");
		}
		#echo "DELETE FROM Provides WHERE PlanID = '$itemInfo[1]'";
		#echo "<br>";
		#echo "DELETE FROM Offers WHERE PlanID = '$itemInfo[1]'";
		#echo "<br>";
	}
	elseif ($itemInfo[0] == "Companies")
	{
		$itemID = "CompanyID";
		$attributes = array("CompanyID", "Name");
		if ($conn->query("DELETE FROM Offers WHERE CompanyID = '$itemInfo[1]'") === TRUE)
		{
		}
		else
		{
			echo "<h1>Error: Row was not deleted</h1>";
			echo "<br>";
			die("Query Error");
		}
		#echo "DELETE FROM Offers WHERE CompanyID = '$itemInfo[1]'";
		#echo "<br>";
	}
	else
	{
		if ($itemInfo[0] == "Phones")
		{
			$itemID = "PhoneID";
			$itemType = "phone";
			$attributes = array("PhoneID", "Name", "ScreenSize", "Manufacturer", "RefreshRate");
		}
		elseif ($itemInfo[0] == "Televisions")
		{
			$itemID = "TVID";
			$itemType = "television";
			$attributes = array("TVID", "Name", "Size", "ScreenType", "HDR", "Resolution", "RefreshRate", "Manufacturer");
		}
		elseif ($itemInfo[0] == "Internet")
		{
			$itemID = "InternetID";
			$itemType = "internet";
			$attributes = array("InternetID", "Name", "DownloadSpeed", "UploadSpeed", "DataCap", "InternetType");
		}
		
		if ($conn->query("DELETE FROM Provides WHERE ItemID = '$itemInfo[1]' AND ItemType = '$itemType'") === TRUE)
		{
		}
		else
		{
			echo "<h1>Error: Row was not deleted</h1>";
			echo "<br>";
			die("Query Error");
		}
		#echo "DELETE FROM Provides WHERE ItemID = '$itemInfo[1]' AND ItemType = '$itemType'";
		echo "<br>";
	}
	
	if ($conn->query("DELETE FROM $itemInfo[0] WHERE $itemID = '$itemInfo[1]'") === TRUE)
		{
		}
		else
		{
			echo "<h1>Error: Row was not deleted</h1>";
			echo "<br>";
			die("Query Error");
		}
	#echo "DELETE FROM $itemInfo[0] WHERE $itemID = '$itemInfo[1]'";
	
	printTable($itemInfo[0], $attributes, $conn);
	
?>