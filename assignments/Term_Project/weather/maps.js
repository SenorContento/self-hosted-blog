var po = org.polymaps;

var map = po.map()
    .container(document.getElementById("ung-map").appendChild(po.svg("svg")))
    .zoomRange([0, 9])
    .zoom(9)
    .center({lat: 34.5280380153, lon: -83.9901673794})
    .add(po.image().url("https://s3.amazonaws.com/com.modestmaps.bluemarble/{Z}-r{Y}-c{X}.jpg"))
    .add(po.interact())
    .add(po.compass().pan("none"))
    .add(po.grid());