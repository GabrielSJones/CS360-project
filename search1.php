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
		$query = "SELECT $table.Name as Name, provides.ItemID as ItemID FROM $table, provides WHERE provides.ItemType = '$item_type' AND provides.ItemID = $table.$IDName AND provides.PlanID = '$planID'";
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
	
	// outputs a table of rows from a table with their 'Name' attribute equal to $name
	function getRowsWithName($name, $table, $attributes, $conn)
	{
		// submits the query to $table to find all rows with $name
		$query = "SELECT * FROM $table WHERE lower(Name) = lower('$name')";
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
			// if this is the Plans table, then print out an extra column to show what devices / internet the plan provides
			if ($table == "Plans")
			{
				echo "<th>Provides</th>";
			}
			echo "</tr>";
			
			// prints out each row that was returned from the query
			while ($row = $result->fetch_assoc())
			{
				echo "<tr>";
				foreach ($attributes as $a)
				{
					echo "<td>" . $row["$a"] . "</td>";
				}
				// if this is from the plans table, then print out all devices that the plan offers in subtables
				if ($table == "Plans")
				{
					echo "<td>";
					
					getItemsInPlan($row["PlanID"], "Phones", $conn);
					getItemsInPlan($row["PlanID"], "Televisions", $conn);
					getItemsInPlan($row["PlanID"], "Internet", $conn);
					
					echo "</td>";
				}
				// prints out the buttons to edit or delete this row at the end of the table
				echo "<td> <form action='edit.php' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='w-100 btn btn-primary btn-lg'>Edit</button> </form> </td>";
				echo "<td> <form action='delete.php' method='post'> <button name='iteminfo' value='$table+" . $row["$attributes[0]"] . "' type='submit' class='btn btn-secondary'>Delete</button> </form> </td>";
				echo "</tr>";
			}
			
			echo "</table>";
			echo "</center>";
		}
	}
	
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
	
	// searches each table for a 'Name' attribute equal to $name
	$phone_atts = array("PhoneID", "Name", "ScreenSize", "Manufacturer", "RefreshRate");
	getRowsWithName($name, "Phones", $phone_atts, $conn);
	$tv_atts = array("TVID", "Name", "Size", "ScreenType", "HDR", "Resolution", "RefreshRate", "Manufacturer");
	getRowsWithName($name, "Televisions", $tv_atts, $conn);
	$int_atts = array("InternetID", "Name", "DownloadSpeed", "UploadSpeed", "DataCap", "InternetType");
	getRowsWithName($name, "Internet", $int_atts, $conn);
	$plan_atts = array("PlanID", "Name", "Price");
	getRowsWithName($name, "Plans", $plan_atts, $conn);
	
	$conn->close();
	
?>