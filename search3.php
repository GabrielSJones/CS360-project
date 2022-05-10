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
	function printTable($table, $attributes, $conn, $query)
	{
		// submits the query to to get all rows from $table
		
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
	
	$PriceAmount = $_POST["PriceAmount"];
	$PriceQuantifier = $_POST["PriceQuantifier"];
	$PhonesQuantifier = $_POST["PhonesQuantifier"];
	$TelevisionsQuantifier = $_POST["TelevisionsQuantifier"];
	$InternetQuantifier = $_POST["InternetQuantifier"];
	$NumOfPhones = $_POST["NumOfPhones"];
	$NumOfTelevisions = $_POST["NumOfTelevisions"];
	$DownloadSpeed = $_POST["DownloadSpeed"];	
	
	
	// gets the user inputs from the search bar
	//$name = $_POST['name'];
	
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
	$totalplan3queryRevised = "SELECT *
					FROM plans ";
	$totalplan3query = "SELECT *
					FROM plans NATURAL JOIN
	(SELECT PlanID FROM 
        (SELECT PlanID, count(ItemType) as num
        FROM provides
        WHERE ItemType = 'phone'
        GROUP BY (PlanID)) as counterFunc
    WHERE num $PhonesQuantifier $NumOfPhones ) as phoneCountID
    NATURAL JOIN
    (SELECT PlanID FROM 
        (SELECT PlanID, count(ItemType) as num
        FROM provides
        WHERE ItemType = 'television'
        GROUP BY (PlanID)) as counterFunc2
	WHERE num $TelevisionsQuantifier $NumOfTelevisions ) as televisionCountID
	NATURAL JOIN    
    (SELECT PlanID FROM 
        (SELECT InternetID as ItemID
        FROM internet
        WHERE downloadSpeed $InternetQuantifier $DownloadSpeed) as counterFunc3
     NATURAL JOIN (SELECT * FROM provides WHERE ItemType = 'internet') as netSub) as internetCountID
	 WHERE Price $PriceQuantifier $PriceAmount
	 ORDER BY `plans`.`Price` ASC";
	 
	
	
	if($PhonesQuantifier != 'ANY') {
		//echo "hello";
		$totalplan3queryRevised = $totalplan3queryRevised . "NATURAL JOIN
	(SELECT PlanID FROM 
        (SELECT PlanID, count(ItemType) as num
        FROM provides
        WHERE ItemType = 'phone'
        GROUP BY (PlanID)) as counterFunc
    WHERE num $PhonesQuantifier $NumOfPhones ) as phoneCountID ";
	}
	if($TelevisionsQuantifier != 'ANY') {
		$totalplan3queryRevised = $totalplan3queryRevised . "NATURAL JOIN
    (SELECT PlanID FROM 
        (SELECT PlanID, count(ItemType) as num
        FROM provides
        WHERE ItemType = 'television'
        GROUP BY (PlanID)) as counterFunc2
	WHERE num $TelevisionsQuantifier $NumOfTelevisions ) as televisionCountID ";
	}
	if($InternetQuantifier != 'ANY') {
		$totalplan3queryRevised = $totalplan3queryRevised . "NATURAL JOIN    
    (SELECT PlanID FROM 
        (SELECT InternetID as ItemID
        FROM internet
        WHERE downloadSpeed $InternetQuantifier $DownloadSpeed) as counterFunc3
     NATURAL JOIN (SELECT * FROM provides WHERE ItemType = 'internet') as netSub) as internetCountID ";
	}
	if($PriceQuantifier != 'ANY') {
		$totalplan3queryRevised = $totalplan3queryRevised . "WHERE Price $PriceQuantifier $PriceAmount ";
	}
	$totalplan3queryRevised = $totalplan3queryRevised . "ORDER BY `plans`.`Price` ASC";
	
	
	//echo $totalplan3queryRevised;
	
	
	$totalplan3result = $conn->query($totalplan3queryRevised);
	$attributes = array("PlanID", "Name", "Price");
	
	
	if ($totalplan3result->num_rows > 0) {
		printTable("Plans", $attributes, $conn, $totalplan3queryRevised);
		
		/*
		echo "<br><center><h2>Plans that match the criteria:</h2></center>";
		while ($row = $totalplan3result->fetch_assoc()) {
			echo "<center><h3><tr><td>" . $row["Name"] . "</td><td>" . $row["Price"] . "</td><td></h3></center>";
		}
		*/
		
	}
	else {
		echo "<center>";
		echo "<h3>No plans available that meet that criteria.";
		echo "<br>Please try widening search parameters.</h3>";
		echo "</center>";
	}
	
	/*
	$companiesthatofferquery = "SELECT companies.Name
	FROM companies NATURAL JOIN
	(SELECT CompanyID
    FROM offers NATURAL JOIN plans
    WHERE lower(plans.Name) = lower('$name') ) as offeringCompanies";
	
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
	
	
	$companiesthatofferresult = $conn->query($companiesthatofferquery);
	$phonesresult = $conn->query($phonequery);
	$internetresult = $conn->query($internetquery);
	$televisionresult = $conn->query($televisionquery);
	
	
	
	
	echo "<center><h1>'$name' detailed information</h1></center>";
	
	if ($companiesthatofferresult->num_rows > 0) {
		echo "<br><center><h2>Companies that offer the '$name':</h2></center>";
		while ($row = $companiesthatofferresult->fetch_assoc()) {
			echo "<center><h3>". $row["Name"] . "</h3></center>";
		}
		
	}
		
	
	
	echo "<br><center>";
	echo "<h2>PHONES INCLUDED:</h2>";
	echo "</center>";
	if ($phonesresult->num_rows > 0)
	{
		echo "<center><table><tr><th>Name</th><th>Screen size (in)</th><th>Manufacturer</th><th>Refresh Rate</th><th>PhoneID</th></tr>";
		while ($row = $phonesresult->fetch_assoc())
		{
			
			echo "<tr><td>" . $row["Name"] . "</td><td>" . $row["ScreenSize"] . "</td><td>" . $row["Manufacturer"] . "</td><td>" . $row["RefreshRate"] . "</td><td>". $row["PhoneID"] . "</td></tr>";
			
		}
		echo "</table></center>";
	}
	else
	{	
		echo "<center>";
		echo "No phone(s) included in this plan";
		echo "</center>";
	}
	
	echo "<br>";
	echo "<center>";
	echo "<h2>INTERNET INCLUDED:</h2>";
	echo "</center>";
	if ($internetresult->num_rows > 0)
	{
		echo "<center><table><tr><th>Name</th><th>Download Speed (mbps)</th><th>Upload Speed (mbps)</th><th>Data Cap (GBs)</th><th>Internet Type</th></td></tr>";
		while ($row = $internetresult->fetch_assoc())
		{
			
			echo "<tr><td>" . $row["Name"] . "</td><td>" . $row["DownloadSpeed"] . "</td><td>" . $row["UploadSpeed"] . "</td><td>" . $row["DataCap"] . "</td><td>". $row["InternetType"] . "</td></tr>";
			
		}
		echo "</table></center>";
		
		
	}
	else
	{	
		echo "<center>";
		echo "No internet provided in this plan";
		echo "</center>";
	}
	
	echo "<br>";
	echo "<center>";
	echo "<h2>TELEVISIONS INCLUDED:</h2>";
	echo "</center>";
	if ($televisionresult->num_rows > 0)
	{		
		echo "<center><table><tr><th>Name</th><th>Resolution</th><th>Size (in)</th><th>Screen Type</th><th>Manufacturer</th><th>HDR Compatible</th><th>Refresh Rate</th></td></tr>";
		while ($row = $televisionresult->fetch_assoc())
		{
			
			echo "<tr><td>" . $row["Name"] . "</td><td>" . $row["Resolution"] . "</td><td>" . $row["Size"] . "</td><td>" . $row["ScreenType"] . "</td><td>" . $row["Manufacturer"] . "</td><td>". $row["HDR"] . "</td><td>". $row["RefreshRate"] . "</td></tr>";
			
		}
		echo "</table></center>";
	}
	else
	{	
		echo "<center>";
		echo "No television(s) included in this plan";
		echo "</center>";
	}
	*/
	
	$conn->close();
	
?>