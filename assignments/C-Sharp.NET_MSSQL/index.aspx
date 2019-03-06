<%@ Page Language="C#" %>
<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.Data.SqlClient" %>
<%@ Import Namespace="System.Security" %>
<%-- <%@ Import Namespace="System.Data.SqlClient.SqlDataReader" %> --%>
<%
  // Environment.GetEnvironmentVariable("DOCUMENT_ROOT") Does Not Work
  var header_proc = new System.Diagnostics.Process();
  header_proc.StartInfo.FileName = "/usr/bin/php";
  header_proc.StartInfo.Arguments = Server.MapPath("~") + "server_data/header.php";
  header_proc.StartInfo.RedirectStandardOutput = true;
  header_proc.StartInfo.UseShellExecute = false;

  header_proc.StartInfo.EnvironmentVariables.Add("alex.server.page.title", "Mono - ASP.Net");
  //header_proc.StartInfo.EnvironmentVariables.Add("alex.server.type", System.Environment.GetEnvironmentVariable("alex.server.type"));
  // ASP.Net Automatically Passes Environment Variables to External Processes

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
      valid.Text = String.Format("" + "You Are Missing The \"First Name\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["last_name"])) {
      validated = false;
      valid.Text = String.Format("" + "You Are Missing The \"Last Name\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["color"])) {
      validated = false;
      valid.Text = String.Format("" + "You Are Missing The \"Color\" Parameter!!!");
    } else if(string.IsNullOrWhiteSpace(Request.Form["food"])) {
      validated = false;
      valid.Text = String.Format("" + "You Are Missing The \"Food\" Parameter!!!");
    }
  } else {
    validated = false;
  }

  /* Python Equivalent
  request = ''
    for key in query:
        if key.startswith('language-'):
            request = request + query[key] + ", ";

    if request == '':
        request = "None"
    else:
        request = request[0:-2]
  */

  // https://stackoverflow.com/a/4807750/6828099
  StringBuilder languageoutput = new StringBuilder();
  foreach(string key in Request.Form) {
    if (!key.StartsWith("language-")) continue;
    languageoutput.Append(Request.Form[key] + ", ");
  }

  // https://docs.microsoft.com/en-us/dotnet/api/system.text.stringbuilder.remove?view=netframework-4.7.2
  // https://docs.microsoft.com/en-us/dotnet/api/system.text.stringbuilder.length?view=netframework-4.7.2
  try {
    languageoutput.Remove(languageoutput.Length - 2, 2);
  } catch(Exception e) {
    languageoutput.Append("None");
  }

  //valid.Text = String.Format(languageoutput.ToString());
  if(validated) { // if(validated && !validated)
    valid.Text = String.Format("Validated!!!");
    //valid.Text = String.Format("" + "Validated!!! \"" + System.Environment.GetEnvironmentVariable("alex.server.type") + "\"");
    //valid.Text = String.Format("" + "Test: " + Request.Form);

    String pwdstring = System.Environment.GetEnvironmentVariable("alex.server.mssql.password");
    SecureString pwd = new SecureString();
    foreach(char c in pwdstring.ToCharArray()) {
      pwd.AppendChar(c);
    }
    SqlCredential cred = new SqlCredential(System.Environment.GetEnvironmentVariable("alex.server.mssql.username"), pwd);

    // Create the database first using CREATE DATABASE database_name
    //Response.Write("<p style='color: blue;'>");
    using(SqlConnection connection = new SqlConnection("Server=" + System.Environment.GetEnvironmentVariable("alex.server.mssql.host") + ";"
    + "Integrated Security=false;"
    + "Persist Security Info=true;database=" + System.Environment.GetEnvironmentVariable("alex.server.mssql.database.class") + ";", cred)) {
      connection.Open();

      SqlCommand command = connection.CreateCommand();
      SqlTransaction transaction;

      // https://docs.microsoft.com/en-us/dotnet/api/system.data.sqlclient.sqlconnection.begintransaction?view=netframework-4.7.2#System_Data_SqlClient_SqlConnection_BeginTransaction
      // Start a local transaction.
      transaction = connection.BeginTransaction(); //connection.BeginTransaction("SampleTransaction");

      // Must assign both transaction object and connection
      // to Command object for a pending local transaction
      command.Connection = connection;
      command.Transaction = transaction;

      try {
        // https://stackoverflow.com/a/10992101/6828099
        //command.CommandText = "CREATE TABLE Assignment10 (id INT IDENTITY(1,1) PRIMARY KEY, firstname TEXT NOT NULL, lastname TEXT NOT NULL, color TEXT NOT NULL, food TEXT NOT NULL, languages TEXT NOT NULL);";
        //command.ExecuteNonQuery();

        command.CommandText = "Insert into Assignment10 (firstname, lastname, color, food, languages) VALUES ('" + Request.Form["first_name"] + "', '" + Request.Form["last_name"] + "', '" + Request.Form["color"] + "', '" + Request.Form["food"] + "', '" + languageoutput.ToString() + "');";
        command.ExecuteNonQuery();

        StringBuilder tableoutput = new StringBuilder();
        tableoutput.Append("<fieldset><legend>MSSQL Entries</legend>");
        tableoutput.Append("<h1>Limited to Last 10 Entries</h1>");
        tableoutput.Append("<table><thead><tr>");
        tableoutput.Append("<th>ID</th>");
        tableoutput.Append("<th>First Name</th>");
        tableoutput.Append("<th>Last Name</th>");
        tableoutput.Append("<th>Color</th>");
        tableoutput.Append("<th>Food</th>");
        tableoutput.Append("<th>Languages</th>");
        tableoutput.Append("</thead><tbody>");

        // http://csharp.net-informations.com/data-providers/csharp-executereader-executenonquery.htm
        // https://forums.asp.net/post/4075854.aspx
        // https://www.w3schools.com/sql/trysql.asp?filename=trysql_select_top&ss=-1
        //command.CommandText = "Select * From Assignment10;";
        command.CommandText = "SELECT TOP 10 * FROM Assignment10 ORDER BY id DESC;";
        //command.CommandText = "Select * From Assignment10 ORDER BY id DESC LIMIT 10;";
        SqlDataReader reader = command.ExecuteReader();
        while(reader.Read()) {
          //Response.Write(reader["firstname"]);

          tableoutput.Append("<tr>");
          tableoutput.Append("<td>" + reader["id"] + "</td>");
          tableoutput.Append("<td>" + reader["firstname"] + "</td>");
          tableoutput.Append("<td>" + reader["lastname"] + "</td>");
          tableoutput.Append("<td>" + reader["color"] + "</td>");
          tableoutput.Append("<td>" + reader["food"] + "</td>");
          tableoutput.Append("<td>" + reader["languages"] + "</td>");
          tableoutput.Append("</tr>");
        }
        reader.Close();
        tableoutput.Append("</tbody></table>");
        tableoutput.Append("</fieldset>");

        table.Text = tableoutput.ToString();

        // Attempt to commit the transaction.
        transaction.Commit();
        //Response.Write("Both records are written to database.");
      } catch (Exception ex) {
        //Response.Write("Commit Exception Type: " + ex.GetType());
        //Response.Write("  Message: " + ex.Message);
        printerror.Text = "<h1>" + ex.GetType() + " : " + ex.Message + "</h1>";

        // Attempt to roll back the transaction.
        try {
          transaction.Rollback();
        } catch (Exception ex2) {
          // This catch block will handle any errors that may have occurred
          // on the server that would cause the rollback to fail, such as
          // a closed connection.
          //Response.Write("Rollback Exception Type: " + ex2.GetType());
          //Response.Write("  Message: " + ex2.Message);
          printerror.Text = printerror.Text + "<br>" + "<h1>" +
          ex2.GetType() + " : " + ex2.Message + "</h1>";
        }
      }
    }
    //Response.Write("</p>");
  }
%>

<%
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
%>

  <%-- https://stackoverflow.com/questions/389149/how-to-access-html-form-input-from-asp-net-code-behind --%>
  <%-- https://stackoverflow.com/a/3063456/6828099 --%>
  <%-- https://stackoverflow.com/a/17682920/6828099 --%>
  <%-- https://stackoverflow.com/a/1390577/6828099 --%>
  <%-- https://stackoverflow.com/a/14587606/6828099 --%>
  <%-- https://stackoverflow.com/a/36252712/6828099 --%>
  <%-- https://docs.microsoft.com/en-us/dotnet/api/system.diagnostics.processstartinfo.environmentvariables?view=netframework-4.7.2 --%>

  <%-- https://docs.microsoft.com/en-us/aspnet/web-pages/overview/data/5-working-with-data --%>
  <%-- https://www.expertrating.com/courseware/DotNetCourse/DotNet-ASP.Net-4-1.asp --%>
  <%-- https://stackoverflow.com/a/24294824/6828099 --%>
  <%-- https://console.aws.amazon.com/rds/home --%><%-- Make sure to create a new account for free tier and open database to public. URL is endpoint. --%>
  <%-- https://tableplus.io/ --%><%-- Program has a free trial and can access MS SQL from RDS if RDS is set up correctly --%>

  <asp:Literal runat="server" id="header"></asp:Literal>
  <%--<h1><asp:Label runat="server" id="date"></asp:Label></h1>--%>
  <h1><asp:Label runat="server" id="valid"></asp:Label></h1>
  <asp:Literal runat="server" id="printerror"></asp:Literal>
  <asp:Literal runat="server" id="table"></asp:Literal>

  <link rel="stylesheet" href="assignment10.css">
  <!--Example Input
  Key: 'first_name' Value: 'Alex'
  Key: 'last_name' Value: 'Contento'
  Key: 'color' Value: 'blue'
  Key: 'food' Value: 'hot-food'
  Key: 'language-python' Value: 'python'
  Key: 'language-php' Value: 'php'
  Key: 'language-html' Value: 'html'-->

  <fieldset><legend>MSSQL Form</legend>
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
  </fieldset>
  <asp:Literal runat="server" id="footer"></asp:Literal>