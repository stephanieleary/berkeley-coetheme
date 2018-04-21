var opensans = new FontFaceObserver('Open Sans', {
  weight: 300
});
var lato = new FontFaceObserver('Lato', {
  weight: 400
});
var sourceserifpro = new FontFaceObserver('Source Serif Pro', {
  weight: 400
});

Promise.all([opensans.load(), lato.load(), sourceserifpro.load()]).then(function () {
  document.documentElement.className += " fonts-loaded";
});