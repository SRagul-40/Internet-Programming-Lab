// Register form: confirm password match
function validateRegister(f) {
  if (f.password.value !== f.confirm.value) { alert("Passwords do not match."); return false; }
  return true;
}
// Room card: click a small photo to show it big
function swapImg(t) {
  var card = t.closest(".gallery");
  card.querySelector(".main").src = t.src;
  card.querySelectorAll(".thumbs img").forEach(function (i) { i.classList.remove("on"); });
  t.classList.add("on");
}
document.addEventListener("DOMContentLoaded", function () {
  var today = new Date().toISOString().split("T")[0];
  document.querySelectorAll('input[type="date"]').forEach(function (d) { d.min = today; });

  // Booking page: rooms needed + live total
  var f = document.getElementById("bookform");
  if (!f) return;
  var price = +f.dataset.price, cap = +f.dataset.cap, foodPrice = +f.dataset.food;
  var i = document.getElementById("in"), o = document.getElementById("out"),
      g = document.getElementById("guests"), fd = document.getElementById("food"),
      t = document.getElementById("total"), m = document.getElementById("roomsmsg");
  function calc() {
    var guests = Math.max(1, parseInt(g.value) || 1), need = Math.ceil(guests / cap);
    m.textContent = need > 1
      ? "This room sleeps only " + cap + ". For " + guests + " members you must book " + need + " rooms of this type."
      : "";
    if (!i.value || !o.value) { t.textContent = ""; return; }
    var n = (new Date(o.value) - new Date(i.value)) / 86400000;
    if (n <= 0) { t.textContent = "Check-out must be after check-in."; return; }
    var total = n * price * need + (fd.value === "1" ? n * guests * foodPrice : 0);
    t.textContent = n + " night(s) · " + need + " room(s) · Total ₹" + total.toLocaleString("en-IN");
  }
  [i, o, g, fd].forEach(function (x) { x.addEventListener("input", calc); x.addEventListener("change", calc); });
  calc();
});
