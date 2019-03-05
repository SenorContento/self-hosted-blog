<%@ Page Language="C#" %>
<% HelloWorldLabel.Text = "Hello World!"; %>
<!DOCTYPE html>
<html>
<body>
     <h1><asp:Label runat="server" id="HelloWorldLabel"></asp:Label></h1>
</body>
</html>
<!-- I tested this code in visual studio and got the result
  ... <h1><span id="HelloWorldLabel">Hello World!</span></h1> ... -->