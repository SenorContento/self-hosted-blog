// http://polymaps.org/docs/
var po = org.polymaps;

var map = po.map()
    .container(document.getElementById("ung-map").appendChild(po.svg("svg"))) // Map Container
    .zoomRange([3, 9]) // Minimum is 0.
    .zoom(4) // Default Zoom
    .center({lat: 34.5280380153, lon: -83.9901673794}) // Default Coordinates
    .add(po.image().url("https://s3.amazonaws.com/com.modestmaps.bluemarble/{Z}-r{Y}-c{X}.jpg")) // Map Image // Any image can be pasted here!!!
    //.add(po.interact()) // Allows User to Manipulate Map
    .add(po.compass().pan("none")); // Adds Controlling Map From Graphical Buttons
    //.add(po.grid()); // Adds CSS Customizable Grid