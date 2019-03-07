<html>
<head>
	<title>I am a title...</title>
	<link rel="stylesheet" href="brandon_gomez.css">
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="brandon_gomez.js"></script>
</head>
<body>
	<h1>Header 1</h1>
	<h3>Header 3</h3>

	<form id="midterm-form" method="POST">
  	<label id="car-model-lable">First Car Model: </label><input type="text" id="model"><br>
		<label id="car-color-label">First Car Color: </label><input type="text" id="color"><br>

		<label>Favorite Car Maker: </label>
		<select name="car-maker">
			<option value="blank"></option>
			<option value="1">Maker 1</option>
			<option value="2">Maker 2</option>
			<option value="3">Maker 3</option>
			<option value="4">Maker 4</option>
			<option value="5">Maker 5</option>
		</select>

		<br>

		<label>Do You Like Sun Roofs? </label>
		<label>Yes: </label><input id="sun-roof-yes" name="sun-roof" value="yes" type="radio" checked="checked">
    <label>No: </label><input id="sun-roof-no" name="sun-roof" value="no" type="radio">

		<br>

		<label class="own-sunroof">Number of Cars You Have Owned With A Sun Roof: </label>
		<select class="own-sunroof" name="own-sunroof">
			<option value="blank"></option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
		</select>

		<br><br>

  	<button id="submit">Submit</button>
	</form>

  <table class="table">
    <thead>
      <tr>
        <th class="table-header">First Car Model</th>
        <th class="table-header">Favorite Car Maker</th>
				<th class="table-header">Number of Cars Owned</th>
      </tr>
      </thead>
      <tbody>
        <tr>
          <td>Model 1</td>
          <td>BMW</td>
					<td>10</td>
        </tr>
        <tr>
          <td>Model 2</td>
          <td>Ford</td>
					<td>20</td>
        </tr>
				<tr>
          <td>Model 3</td>
          <td>Chevy</td>
					<td>9001</td>
        </tr>
      </tbody>
    </table>
		<!-- No TFoot -->
	</body>
</html>