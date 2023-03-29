var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
  var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.querySelector(".navbar").classList.remove("scrolled");
  } else {
    document.querySelector(".navbar").classList.add("scrolled");
  }
  prevScrollpos = currentScrollPos;
};
