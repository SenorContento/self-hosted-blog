<%@ Page Language="C#" %>
<%
  bool validated = true;
  if(Request.Form["first_name"] == "" || Request.Form["first_name"] == null) {
    validated = false;
    valid.Text = String.Format("{0}", "First Name!!!");
  } else if(Request.Form["last_name"] == "" || Request.Form["last_name"] == null) {
    validated = false;
    valid.Text = String.Format("{0}", "Last Name!!!");
  } else if(Request.Form["color"] == "" || Request.Form["color"] == null) {
    validated = false;
    valid.Text = String.Format("{0}", "Color!!!");
  } else if(Request.Form["food"] == "" || Request.Form["food"] == null) {
    validated = false;
    valid.Text = String.Format("{0}", "Food!!!");
  }

  if(validated) {
    valid.Text = String.Format("{0}", "Validated!!!");
  }
%>

  <!-- https://stackoverflow.com/questions/389149/how-to-access-html-form-input-from-asp-net-code-behind -->
  <!-- https://stackoverflow.com/a/3063456/6828099 -->

  <h1><asp:Label runat="server" id="valid"></asp:Label></h1>

  <link rel="stylesheet" href="assignment9.css">
  <!--Example Input
  Key: 'first_name' Value: 'Alex'
  Key: 'last_name' Value: 'Contento'
  Key: 'color' Value: 'blue'
  Key: 'food' Value: 'hot-food'
  Key: 'language-python' Value: 'python'
  Key: 'language-php' Value: 'php'
  Key: 'language-html' Value: 'html'-->

  <form method="post">
    <label class="form-label-first_name">First Name: </label><input name="first_name" type="text"><br>
    <label class="form-label-last_name">Last Name: </label><input name="last_name" type="text">

    <br><br>

    <label class="form-label-color">Pick a Color: </label>
    <select id="option-color" name="color">
        <option value="blank" disabled selected></option>
        <option value="red">Red</option>
        <option value="green">Green</option>
        <option value="blue">Blue</option>
    </select>

    <br><br>

    <label>Preferred Food Temp:</label><br>
    <label class="form-label-food">Hot Food </label><input id="radio-hot-food" class="radio-food" name="food" value="hot-food" type="radio" checked="checked"><br>
    <label class="form-label-food">Cold Food </label><input id="radio-cold-food" class="radio-food" name="food" value="cold-food" type="radio">

    <br><br>


    <label>Favorite Languages:</label><br>
    <label class="form-label-language">Python </label><input type="checkbox" name="language-python" value="python" checked><br>
    <label class="form-label-language">PHP </label><input type="checkbox" name="language-php" value="php"><br>
    <label class="form-label-language">HTML </label><input type="checkbox" name="language-html" value="html">

    <br><br>

    <input type="submit" value="Submit">
  </form>