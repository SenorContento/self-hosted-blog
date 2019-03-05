<!DOCTYPE html>
<html>
<body>
     <h1><asp:Label runat="server" id="HelloWorldLabel"></asp:Label></h1>
     <p>The Time is @DateTime.Now</p>
</body>
</html>
<% HelloWorldLabel.Text = "Hello World!"; %>