/*window.onload = function () {
  //alert("Page is loaded");
  console.log("Page Loaded!!!");
  document.getElementById("original").onload = function() {
    console.log("Image Loaded!!!");
    this.src = '/images/jpg/that-red-tree/crayon.jpg';
  }
}*/

var loaded = [];

function loadImage(self, image) {
  var load = new Promise(function(resolve, reject) {
    setTimeout(function() {
      resolve([self, image]);
    }, 300);
  });

  load.then(function(array) {
    self = array[0];
    image = array[1];

    // https://love2dev.com/blog/javascript-includes/
    if(!window.loaded.includes(self.src)) {
      console.log("Image \"" + self.src + "\" Loaded!!!");
      console.log("Replacing With: '" + image + "'");

      //alert(this.src);

      self.src = image;
      self.classList.remove("blur");

      //alert(this.src);
      // https://appdividend.com/2018/10/08/javascript-array-push-example-array-prototype-tutorial/
      window.loaded.push(self.src);
    }
  });
}