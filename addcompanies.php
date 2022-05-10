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
	
	// prints a subtable of rows with ids in $ids
	function printSubTable($table, $ids, $idName, $conn)
	{
		echo "<table class='inner'>";
		echo "<tr> <th>ID</th> <th>Name</th> </tr>";
		$query = "SELECT $idName as ID, Name FROM $table WHERE $idName IN ('" . implode("', '", $ids) . "')";
		$result = $conn->query($query);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				echo "<tr> <td>" . $row["ID"] . "</td> <td>" . $row["Name"] . "</td> </tr>";
			}
		}
		
		echo "</table>";
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
	
	// get all of the values from the input form
	$id = $_POST['ID'];
	$name = $_POST['Name'];
	$plans = NULL;
	if (isset($_POST['Plans']))
	{
		$plans = $_POST['Plans'];
	}
	
	echo "<center>";
	echo "<table>";
	echo "<tr> <th>ID</th> <th>Name</th> <th>Offers</th> <tr>";
	echo "<tr> <td>$id</td> <td>$name</td>";
	echo "<td>";
	if (isset($plans))
	{
		printSubTable("Plans", $plans, "PlanID", $conn);
	}
	echo "</td> </tr> </center>";
	
	$idCheck = $conn->query("SELECT * FROM Plans WHERE PlanID = '$id'");
	if ($idCheck->num_rows < 1)
	{
		$query = "INSERT INTO Companies (CompanyID, Name) VALUES ('$id', '$name')";
		if ($conn->query($query) === TRUE)
		{
			$failed = FALSE;
			if (isset($companies))
			{
				$query = "INSERT INTO Offers (PlanID, CompanyID) VALUES ('" . implode("', '$id'), ('", $plans) . "', '$id')";
				if ($conn->query($query) !== TRUE)
				{
					$failed = TRUE;
				}
			}
			
			if (!$failed)
			{
				echo "<center> <h1>Row Added Successfully</h1> </center>";
			}
			else
			{
				$conn->query("DELETE FROM Companies WHERE CompanyID = '$id'");
				$conn->query("DELETE FROM Offers WHERE CompanyID = '$id'");
				echo "<center> <h1>Error: Row Was Not Added To The Table</h1> </center>";
			}
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
	
	$attributes = array("CompanyID", "Name");
	printTable("Companies", $attributes, $conn);
	
?>