// Paste all your JavaScript code here, for example:
const scrollBtn = document.getElementById("scrollToTopBtn");
window.addEventListener("scroll", () => {
  if (window.scrollY > 200) {
    scrollBtn.style.display = "block";
  } else {
    scrollBtn.style.display = "none";
  }
});
scrollBtn.addEventListener("click", () => {
  scrollBtn.animate(
    [
      { transform: "rotate(0deg) scale(1)" },
      { transform: "rotate(360deg) scale(1.25)" },
      { transform: "rotate(390deg) scale(0.96)" },
      { transform: "rotate(360deg) scale(1)" },
    ],
    {
      duration: 600,
      easing: "cubic-bezier(0.51,1.02,0,1.08)",
    }
  );
  window.scrollTo({ top: 0, behavior: "smooth" });
});
/* Accessibility: Show focus outline only on keyboard navigation */
function handleFirstTab(e) {
  if (e.key === "Tab") {
    document.body.classList.add("user-is-tabbing");
    window.removeEventListener("keydown", handleFirstTab);
  }
}
window.addEventListener("keydown", handleFirstTab);

function updateRealVisitorStat() {
  fetch("real_visit.php")
    .then(resp => resp.ok ? resp.text() : Promise.resolve("—"))
    .then(count => {
      // Only set numeric counts, otherwise show dash
      document.getElementById("visitors-count").textContent =
        /^\d+$/.test(count.trim()) ? count : "—";
    })
    .catch(() => {
      document.getElementById("visitors-count").textContent = "—";
    });
}
updateRealVisitorStat();
setInterval(updateRealVisitorStat, 40000);

