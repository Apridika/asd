// tombol hamburger menu
const navbarNav = document.querySelector(".navbar-nav");

document.querySelector("#hamburger").onclick = () => {
  navbarNav.classList.toggle("active");
};

// klik luar nav menghilangkan nav

const hamburger = document.querySelector("#hamburger");

document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }
});
