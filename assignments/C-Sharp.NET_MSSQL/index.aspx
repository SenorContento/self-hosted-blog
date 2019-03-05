<%@ Page Language="C#" %>
<%
  // Environment.GetEnvironmentVariable("DOCUMENT_ROOT") Does Not Work
  var header_proc = new System.Diagnostics.Process();
  header_proc.StartInfo.FileName = "/usr/bin/php";
  header_proc.StartInfo.Arguments = Server.MapPath("~") + "server_data/header.php";
  header_proc.StartInfo.RedirectStandardOutput = true;
  header_proc.StartInfo.UseShellExecute = false;
  header_proc.Start();

  StringBuilder header_string = new StringBuilder();
  while(!header_proc.HasExited) {
    header_string.Append(header_proc.StandardOutput.ReadToEnd());
  }
  header.Text = header_string.ToString();
%>
<%
  // Environment.GetEnvironmentVariable("DOCUMENT_ROOT") Does Not Work
  var footer_proc = new System.Diagnostics.Process();
  footer_proc.StartInfo.FileName = "/usr/bin/php";
  footer_proc.StartInfo.Arguments = Server.MapPath("~") + "server_data/footer.php";
  footer_proc.StartInfo.RedirectStandardOutput = true;
  footer_proc.StartInfo.UseShellExecute = false;
  footer_proc.Start();

  StringBuilder footer_string = new StringBuilder();
  while(!footer_proc.HasExited) {
    footer_string.Append(footer_proc.StandardOutput.ReadToEnd());
  }
  footer.Text = footer_string.ToString();
%>

<%
  bool validated = true;
  if(!string.IsNullOrWhiteSpace(Request.Form.ToString())) {
    if(string.IsNullOrWhiteSpace(Request.Form["first_name"])) {
      validated = false;
      valid.Text = String.Format("{0}", "You Are Missing The \"First Name\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["last_name"])) {
      validated = false;
      valid.Text = String.Format("{0}", "You Are Missing The \"Last Name\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["color"])) {
      validated = false;
      valid.Text = String.Format("{0}", "You Are Missing The \"Color\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["food"])) {
      validated = false;
      valid.Text = String.Format("{0}", "You Are Missing The \"Food\" Parameter!!!");
    }
  } else {
    validated = false;
  }

  if(validated) {
    //valid.Text = String.Format("{0}", "Validated!!!");
    valid.Text = String.Format("{0}", "Validated!!! \"" + System.Environment.GetEnvironmentVariable("alex.server.type") + "\"");
    //valid.Text = String.Format("{0}", "Test: " + Request.Form);

    /*Response.Write("<p style=\"color: blue;\">");
    Response.Write("Request Headers: <br />");
    for (int i = 0; i < Request.Headers.Count; i++) {
      Response.Write(Request.Headers.Keys[i] + ": ");
      Response.Write(Request.Headers[i] + "<br />");
    }
    Response.Write("</p>");

    Response.Write("<p style=\"color: red;\">");
    Response.Write("Server Variables: <br />");
    for (int i = 0; i < Request.ServerVariables.Count; i++) {
      Response.Write(Request.ServerVariables.Keys[i] + ": ");
      Response.Write(Request.ServerVariables[i] + "<br />");
    }
    Response.Write("</p>");

    Response.Write("<p>");
    Response.Write("Environment Variables: <br />");
    foreach(DictionaryEntry e in System.Environment.GetEnvironmentVariables()) {
      Response.Write(e.Key  + " : " + e.Value + "<br />");
    }
    Response.Write("</p>");*/
  }
%>

  <%-- https://stackoverflow.com/questions/389149/how-to-access-html-form-input-from-asp-net-code-behind --%>
  <%-- https://stackoverflow.com/a/3063456/6828099 --%>
  <%-- https://stackoverflow.com/a/17682920/6828099 --%>
  <%-- https://stackoverflow.com/a/1390577/6828099 --%>
  <%-- https://stackoverflow.com/a/14587606/6828099 --%>
  <%-- https://stackoverflow.com/a/36252712/6828099 --%>

  <%-- https://docs.microsoft.com/en-us/aspnet/web-pages/overview/data/5-working-with-data --%>

  <asp:Literal runat="server" id="header"></asp:Literal>
  <%--<h1><asp:Label runat="server" id="date"></asp:Label></h1>--%>
  <h1><asp:Label runat="server" id="valid"></asp:Label></h1>

  <link rel="stylesheet" href="assignment10.css">
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
  <asp:Literal runat="server" id="footer"></asp:Literal>