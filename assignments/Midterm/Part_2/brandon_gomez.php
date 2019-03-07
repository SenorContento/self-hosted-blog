<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<?php
		// This login only exists on my localhost server
		// and was made for the sole purpose of the second part of the midterm.
		// It will not work on my real site.
		//$username = "midterm_two";
		//$password = "33vmlp0r8VgmliiL";

		$username = getenv('alex.server.phpmyadmin.username');
		$password = getenv('alex.server.phpmyadmin.password');
		$database = "brandon_gomez_midterm";

		try {
			$conn = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$checkTableSQL = "SELECT count(*)
          FROM information_schema.TABLES
          WHERE (TABLE_SCHEMA = '$database') AND (TABLE_NAME = 'form')
        ";

			/*
			“id” field as primary key with auto-increment
			“name” field as varchar with a size of 25
			“fav” field as varchar with a size of 100
			“otherpet” field as varchar with a size of 25
			“number” field as int
			“cats” field as varchar with a size of 3
			“catcount” field as int
			*/

			$sql = "CREATE TABLE form (
	          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	          name VARCHAR(25) NOT NULL,
	          fav VARCHAR(100) NOT NULL,
	          otherpet VARCHAR(25) NOT NULL,
	          number INT NOT NULL,
						cats VARCHAR(3) NOT NULL,
						catscount INT NOT NULL
	        )";

	    $tableExists = false;

	    foreach ($conn->query($checkTableSQL) as $row) {
	    	if($row['count(*)'] > 0)
	      	$tableExists = true;
	    }

	   	if(!$tableExists) {
	    	$conn->exec($sql);
	    }
		}
		catch(PDOException $e) {
			print($e->getMessage());
		}

		if(!empty($_REQUEST)) {
			// array(5) { ["name"]=> string(0) "" ["otherpet"]=> string(0) ""
			//["petcount"]=> string(0) "" ["catcount"]=> string(0) ""
			//["submit"]=> string(6) "Submit" }
      //var_dump($_REQUEST);

			/*
			array(7) { ["name"]=> string(6) "justin"
			["fav"]=> array(1) { [0]=> string(16) "" }
			["otherpet"]=> string(0) ""
			["petcount"]=> string(1) "4"
			["cats"]=> string(3) "yes"
			["catcount"]=> string(3) "999"
			["submit"]=> string(6) "Submit" }
			*/

			/*
			$id = isset($_REQUEST["id"]) ? (int) $_REQUEST["id"] : NULL;
			$bytes = isset($_REQUEST["bytes"]) ? $_REQUEST["bytes"] : NULL;
			$analyze = isset($_REQUEST["analyze"]) ? filter_var($_REQUEST["analyze"], FILTER_VALIDATE_BOOLEAN) : false;
			$retrieve = isset($_REQUEST["retrieve"]) ? filter_var($_REQUEST["retrieve"], FILTER_VALIDATE_BOOLEAN) : false;
			$format = isset($_REQUEST["format"]) ? $_REQUEST["format"] : "json";
			*/

			/*
			$sql = "CREATE TABLE form (
	          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	          name VARCHAR(25) NOT NULL,
	          fav VARCHAR(100) NOT NULL,
	          otherpet VARCHAR(25) NOT NULL,
	          number INT NOT NULL,
						cats VARCHAR(3) NOT NULL,
						catscount INT NOT NULL
	        )";
			*/

			$name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : NULL;
			$fav = isset($_REQUEST["fav"]) ? $_REQUEST["fav"] : []; // Array
			$otherpet = isset($_REQUEST["otherpet"]) ? $_REQUEST["otherpet"] : NULL; // No
			$petcount = isset($_REQUEST["petcount"]) ? $_REQUEST["petcount"] : NULL;
			$cats = isset($_REQUEST["cats"]) ? $_REQUEST["cats"] : NULL;
			$catcount = isset($_REQUEST["catcount"]) ? $_REQUEST["catcount"] : NULL;

			$validate = true;

			if((3 > strlen($name)) || (strlen($name) > 25)) {
				//print("Name");
				print('<script>
							$(document).ready(function() {
								$("#name").css("border","2px solid red");
								$("#name").css("border-radius","4px");
							});
							</script>
						');
				$validate = false;
			} else {
				print('<script>
							$(document).ready(function() {
								$("#name").text(' . $name . ');
							});
							</script>
						');
			}

			if(count($fav) < 1) {
				//print("FAV");
				print('<script>
							$(document).ready(function() {
								$("#fav").css("border","2px solid red");
								$("#fav").css("border-radius","4px");
							});
							</script>
						');
				$validate = false;
			} else {
				/*print('<script>
							$(document).ready(function() {
								$("#fav").text(' . $fav . ');
							});
							</script>
						');*/
			}

			foreach($fav as $favorite) {
				//print($favorite);
				if($favorite === "Other" && (3 > strlen($otherpet)) || (strlen($otherpet) > 25)) {
					//print("otherpet");
					$validate = false;
					print('<script>
                $(document).ready(function() {
									$("#otherpet").css("border","2px solid red");
									$("#otherpet").css("border-radius","4px");
                });
								</script>
              ');
				}
			}

			if(!filter_var($petcount, FILTER_VALIDATE_INT)) {
				//print("petcount");
				print('<script>
							$(document).ready(function() {
								$("#petcount").css("border","2px solid red");
								$("#petcount").css("border-radius","4px");
							});
							</script>
						');
				$validate = false;
			} else {
				print('<script>
							$(document).ready(function() {
								$("#petcount").text(' . $petcount . ');
							});
							</script>
						');
			}

			if($cats === "yes" || $cats === "no") {
				print('<script>
							$(document).ready(function() {
								$("#cats").text(' . $cats . ');
							});
							</script>
						');
			} else {
				//print("cats");
				print('<script>
							$(document).ready(function() {
								$("#cats").css("border","2px solid red");
								$("#cats").css("border-radius","4px");
							});
							</script>
						');
				$validate = false;
			}

			if($cats === "yes" && !filter_var($catcount, FILTER_VALIDATE_INT)) {
				print('<script>
							$(document).ready(function() {
								$("#catcount").css("border","2px solid red");
								$("#catcount").css("border-radius","4px");
							});
							</script>
						');
				//print("catcount");
				$validate = false;
			}

			if($validate) {
				print("Success!!!");

				try {
				$conn = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $conn->prepare("INSERT INTO form (name, fav, otherpet, number, cats, catscount)
                                     VALUES (:name, :fav, :otherpet, :number, :cats, :catscount)");

        $statement->execute([
          'name' => $name,
          'fav' => implode(", ", $fav),
          'otherpet' => $otherpet,
          'number' => (int) $petcount,
					'cats' => $cats,
          'catscount' => (int) $catcount,
        ]);
      	} catch(PDOException $e) {
          echo "<p>Insert Data into Table Failed: " . $e->getMessage() . "</p>";
      	}
			}
    }

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Midterm Part 2</title>
<link type="text/css" href="brandon_gomez.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="brandon_gomez.js"></script>
</head>

<body>
	<h1>Starting Point for Midterm Part 2</h1>
	<form method="post">
		<label for="name">First Pet Name </label><input type="text" name="name" id="name"><br>

		Favorite Types of Pets:
		<?php
		$favPets = array("Dog", "Cat", "Bird", "Mouse", "Snake", "Other");
		foreach($favPets as $pet)
		{
		?>
			<label for="fav<?php echo $pet; ?>"><?php echo $pet; ?></label><input type="checkbox" name="fav[]" id="fav<?php echo $pet; ?>" value="<?php echo $pet; ?>">
		<?php
		}
		?>
		<br>

		<div id="othercontainer">
		<label for="otherpet">What other types </label><input type="text" name="otherpet" id="otherpet"><br>
		</div>

		<label for="petcount">Number of pets </label><input type="number" name="petcount" id="petcount"><br>

		Do you like cats?
		<label for="catsyes">Yes </label><input type="radio" name="cats" id="catsyes" value="yes">
		<label for="catsno">No </label><input type="radio" name="cats" id="catsno" value="no">
		<br>

		<div id="catcountcontainer">
		<label for="catcount">How many cats do you have </label>
			<select name="catcount" id="catcount">
				<option value="">Choose a number</option>
				<?php
				for($i = 0; $i <= 20; $i++)
				{
				?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php
				}
				?>
				<option value="999">A lot!</option>
			</select>
		<br>
		</div>
		<input type="submit" name="submit" id="submit" value="Submit"><br>
	</form>
	<h2>Collected Data</h2>
	<table class="output">
		<thead>
			<tr>
				<th>First Pet Name</th>
				<th>Favorite Types of Pets</th>
				<th>Number of Pets</th>
				<th>Other Pets</th>
				<th>Have a Cat?</th>
				<th>How Many Cats?</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Rumpus</td>
				<td>Cats</td>
				<td>2</td>
				<td>No</td>
				<td>No</td>
				<td>0</td>
			</tr>
			<tr>
				<td>Tiger</td>
				<td>Cats;Dogs</td>
				<td>1</td>
				<td>No</td>
				<td>No</td>
				<td>0</td>
			</tr>
			<tr>
				<td>Martini</td>
				<td>Dogs</td>
				<td>8</td>
				<td>No</td>
				<td>No</td>
				<td>0</td>
			</tr>
			<?php
			// This login only exists on my localhost server
			// and was made for the sole purpose of the second part of the midterm.
			// It will not work on my real site.
			//$username = "midterm_two";
			//$password = "33vmlp0r8VgmliiL";

			$username = getenv('alex.server.phpmyadmin.username');
			$password = getenv('alex.server.phpmyadmin.password');
			$database = "brandon_gomez_midterm";

			try {
				$conn = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				/*
				$statement->execute([
					'name' => $name,
					'fav' => implode(", ", $fav),
					'otherpet' => $otherpet,
					'number' => (int) $petcount,
					'cats' => $cats,
					'catscount' => (int) $catcount,
				]);*/

	        //$sql = "SELECT * FROM form"; // Display Everything
	        $sql = "SELECT * FROM form ORDER BY number, name";
	        foreach ($conn->query($sql) as $row) {

						// This code was in the original midterm.
	          /*print("
	          <tr>
	            <td>" . $row['name'] . "</td>
	            <td>" . $row['fav'] . "</td>
							<td>" . $row['number'] . "</td>
	            <td>" . $row['otherpet'] . "</td>
	            <td>" . $row['cats'] . "</td>
							<td>" . $row['catscount'] . "</td>
	          </tr>");*/

						/* This is the XSS patched code now that
						 * I am uploading the midterm to my online site
						 * as opposed to testing on localhost */
						print("
	          <tr>
	            <td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>
	            <td>" . htmlspecialchars($row['fav'], ENT_QUOTES, 'UTF-8') . "</td>
							<td>" . htmlspecialchars($row['number'], ENT_QUOTES, 'UTF-8') . "</td>
	            <td>" . htmlspecialchars($row['otherpet'], ENT_QUOTES, 'UTF-8') . "</td>
	            <td>" . htmlspecialchars($row['cats'], ENT_QUOTES, 'UTF-8') . "</td>
							<td>" . htmlspecialchars($row['catscount'], ENT_QUOTES, 'UTF-8') . "</td>
	          </tr>");
	        }
	      } catch(PDOException $e) {
	          echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
	      }?>
		</tbody>
	</table>
</body>
</html>