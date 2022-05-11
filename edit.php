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
		// submits the query to to get all rows from $table
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
			// if this is the Plans or Companies table, print extra columns for outputing links in relation tables
			if ($table == "Plans")
			{
				echo "<th>Company / Companies</th>";
				echo "<th>Provides</th>";
			}
			elseif ($table == "Companies")
			{
				echo "<th>Offers</th>";
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
				// if this is from the companies table, then print out the plans that this company offers
				elseif ($table == "Companies")
				{
					// prints out the plans that this company offers
					$plan_query = "SELECT Plans.PlanID as ID, Plans.Name as Name FROM Offers, Plans WHERE Offers.CompanyID = '" . $row["CompanyID"] . "' AND Offers.PlanID = Plans.PlanID";
					$plan_result = $conn->query($plan_query);
					echo "<td>";
					if ($plan_result->num_rows > 0)
					{
						echo "<center>";
						echo "<table class='inner'>";
						echo "<tr> <th>ID</th> <th>Name</th> </tr>";
						while ($plan_row = $plan_result->fetch_assoc())
						{
							echo "<tr> <td>" . $plan_row["ID"] . "</td> <td>" . $plan_row["Name"] . "</td> </tr>";
						}
						echo "</table>";
						echo "</center>";
					}
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
	
	// outputs a table of items from $table that are going to be added to a plan
	function displayPlanItems($table, $ids, $idName, $conn)
	{
		$query = $query = "SELECT $table.Name as Name, $table.$idName as ID FROM $table WHERE $table.$idName IN ('" . implode("', '", $ids) . "')";
		$results = $conn->query($query);
		
		if ($results->num_rows > 0)
		{
			if ($table == "Phones" or $table == "Televisions" or $table == "Internet")
			{
				echo "<center> <h1>$table</h1> </center>";
			}
			
			echo "<center> <table class='inner'>";
			
			if ($table == "Phones" or $table == "Televisions" or $table == "Internet")
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
				if (isset($values[$i]))
				{
					displayPlanItems("Companies", $values[$i], "CompanyID", $conn);
				}
				echo "</td>";
			}
			elseif ($i > 3 and $table == "Plans")
			{
				echo "<td>";
				if (isset($values[$i]))
				{
					displayPlanItems("Phones", $values[$i], "PhoneID", $conn);
				}
				$i++;
				if (isset($values[$i]))
				{
					displayPlanItems("Televisions", $values[$i], "TVID", $conn);
				}
				$i++;
				if (isset($values[$i]))
				{
					displayPlanItems("Internet", $values[$i], "InternetID", $conn);
				}
				$i++;
				echo "</td>";
			}
			elseif ($i > 1 and $table == "Companies")
			{
				echo "<td>";
				if (isset($values[$i]))
				{
					displayPlanItems("Plans", $values[$i], "PlanID", $conn);
				}
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
	
	$info = $_POST['Info'];
	$info = explode("+", $info);
	$table = $info[0];
	$id = $info[1];
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
		array_push($values, $_POST['ID'], $_POST['Name'], $_POST['Price']);
		if (isset($_POST['Companies']))
		{
			array_push($values, $_POST['Companies']);
		}
		else
		{
			array_push($values, NULL);
		}
		if (isset($_POST['Phones']))
		{
			array_push($values, $_POST['Phones']);
		}
		else
		{
			array_push($values, NULL);
		}
		if (isset($_POST['Televisions']))
		{
			array_push($values, $_POST['Televisions']);
		}
		else
		{
			array_push($values, NULL);
		}
		if (isset($_POST['Internet']))
		{
			array_push($values, $_POST['Internet']);
		}
		else
		{
			array_push($values, NULL);
		}
		array_push($header_atts, "ID", "Name", "Price", "Company / Companies", "Provides");
	}
	elseif ($table == "Companies")
	{
		$attributes = array("CompanyID", "Name");
		array_push($values, $_POST['ID'], $_POST['Name']);
		if (isset($_POST['Plans']))
		{
			array_push($values, $_POST['Plans']);
		}
		else
		{
			array_push($values, NULL);
		}
		array_push($header_atts, "ID", "Name", "Offers");
	}
	
	displayRow($table, $header_atts, $values, $conn);
	
	$query = "UPDATE $table SET " . $attributes[0] . " = '" . $values[0] . "'";
	for ($i = 1; $i < count($attributes); $i++)
	{
		$query .= ", " . $attributes[$i] . " = '" . $values[$i] . "'";
	}
	$query .= " WHERE " . $attributes[0] . " = '$id'";
	#echo "<center>";
	#echo $query;
	#echo "<br> </center>";
	
	$failed = FALSE;
	if ($conn->query($query) !== TRUE)
	{
		$failed = TRUE;
	}
	
	if (!$failed and $table == "Plans")
	{
		$query = "DELETE FROM Offers WHERE PlanID = '$id'";
		$conn->query($query);
		if (isset($values[3]))
		{
			$query = "INSERT INTO Offers (CompanyID, PlanID) VALUES ('" . implode("', '" . $values[0] . "'), ('", $values[3]) . "', '" . $values[0] . "')";
			$conn->query($query);
		}
		
		$query = "DELETE FROM Provides WHERE PlanID = '$id'";
		$conn->query($query);
		if (isset($values[4]))
		{
			$query = "INSERT INTO Provides (PlanID, ItemID, ItemType) VALUES ('" . $values[0] . "', '" . implode("', 'phone'), ('" . $values[0] . "',' ", $values[4]) . "', 'phone')";
			$conn->query($query);
		}
		if (isset($values[5]))
		{
			$query = "INSERT INTO Provides (PlanID, ItemID, ItemType) VALUES ('" . $values[0] . "', '" . implode("', 'television'), ('" . $values[0] . "',' ", $values[5]) . "', 'television')";
			$conn->query($query);
		}
		if (isset($values[6]))
		{
			$query = "INSERT INTO Provides (PlanID, ItemID, ItemType) VALUES ('" . $values[0] . "', '" . implode("', 'internet'), ('" . $values[0] . "',' ", $values[6]) . "', 'internet')";
			$conn->query($query);
		}
		/*echo "<center>";
		echo $delete1;
		echo "<br>";
		echo $add1;
		echo "<br>";
		echo $delete2;
		echo "<br>";
		echo $add2;
		echo "<br>";
		echo $add3;
		echo "<br>";
		echo $add4;
		echo "<br> </center>";*/
	}
	if (!$failed and $table == "Companies")
	{
		$query = "DELETE FROM Offers WHERE CompanyID = '$id'";
		$conn->query($query);
		if (isset($values[2]))
		{
			$query = "INSERT INTO Offers (PlanID, CompanyID) VALUES ('" . implode("', '" . $values[0] . "'), ('", $values[2]) . "', '" . $values[0] . "')";
			$conn->query($query);
		}
	}
	
	if (!$failed)
	{
		echo "<center> <h1>Row Successfully Updated</h1> </center>";
	}
	else
	{
		echo "<center> <h1>Error: Row was not Updated</h1> </center>";
	}
	
	printTable($table, $attributes, $conn);
	
?>