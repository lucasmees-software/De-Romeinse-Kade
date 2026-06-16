document.addEventListener("DOMContentLoaded", function () {
  // Get the order button
  const btn = document.getElementById("open-order");

  // Create popup
  const popup = document.createElement("div");
  popup.id = "order-popup";
  popup.innerHTML = `
        <div class="rk-popup-overlay" id="rk-popup-overlay"></div>
        <div class="rk-popup-panel" role="dialog" aria-modal="true" aria-label="Order menu">
            <div class="rk-popup-header">
                <span>Uw bestelling:</span>
                <button class="rk-popup-close" id="rk-popup-close" aria-label="Sluiten">&times;</button>
            </div>
            <div class="rk-popup-body" id="rk-popup-body">
                <p>Laden...</p>
            </div>
            <div class="rk-popup-footer">
                <a href="/De-Romeinse-Kade/Lucas/pages/menu.php" class="rk-popup-menu-btn">
                    ← Voeg meer items toe
                </a>
            </div>
        </div>
    `;
  document.body.appendChild(popup);

  // Open popup and load order
  btn.addEventListener("click", function () {
    popup.classList.add("rk-popup-active");
    document.body.style.overflow = "hidden";
    loadOrder();
  });

  // Close via X button
  document
    .getElementById("rk-popup-close")
    .addEventListener("click", closePopup);
  document
    .getElementById("rk-popup-overlay")
    .addEventListener("click", closePopup);

  // Close if clicking off screen
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closePopup();
  });

  function closePopup() {
    popup.classList.remove("rk-popup-active");
    document.body.style.overflow = "";
  }

  // Fetch
  function loadOrder() {
    const body = document.getElementById("rk-popup-body");
    body.innerHTML = "<p>Laden...</p>";

    fetch("/De-Romeinse-Kade/Lucas/pages/get_order.php")
      .then((r) => r.json())
      .then((items) => {
        // Empty cart
        if (items.length === 0) {
          body.innerHTML = "<p>Uw winkelwagen is leeg.</p>";
          return;
        }

        // render items
        body.innerHTML = items
          .map(
            (item) => `
                    <div class="rk-order-item" data-item-id="${item.item_id}">
                        <div class="rk-order-info">
                            <span class="rk-order-name">${item.item}</span>
                            <span class="rk-order-price">€${item.prijs}</span>
                        </div>
                        <div class="rk-order-controls">
                            <button class="rk-qty-btn" onclick="changeQty(this, -1)">−</button>
                            <span class="rk-qty">${item.aantal}</span>
                            <button class="rk-qty-btn" onclick="changeQty(this, 1)">+</button>
                        </div>
                    </div>
                `,
          )
          .join("");
      });
  }

  // Change quantity of an item
  window.changeQty = function (btn, delta) {
    const row = btn.closest(".rk-order-item");
    const itemId = row.dataset.itemId;
    const qtyEl = row.querySelector(".rk-qty");
    let aantal = parseInt(qtyEl.textContent) + delta;
    if (aantal < 0) aantal = 0;
    qtyEl.textContent = aantal;

    // Send update to server
    fetch("/De-Romeinse-Kade/Lucas/pages/update_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ item_id: itemId, aantal: aantal }),
    }).then(() => {
      // Remove row if quantity = 0
      if (aantal === 0) row.remove();
      const body = document.getElementById("rk-popup-body");
      if (body.querySelectorAll(".rk-order-item").length === 0) {
        body.innerHTML = "<p>Uw winkelwagen is leeg.</p>";
      }
    });
  };
});
