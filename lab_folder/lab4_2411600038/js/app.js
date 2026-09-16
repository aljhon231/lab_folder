let state = {
  category: "all",
  status: "all",
  minPrice: "",
  maxPrice: "",
  search: ""
};

const $ = id => document.getElementById(id);

document.addEventListener("DOMContentLoaded", () => {
  const username = localStorage.getItem("studentUsername") || "student";
  $("navUsername").textContent = username;
  $("sidebarUsername").textContent = username;
  $("greeting").textContent = `Good Afternoon, ${username}!`;
  $("currentDate").textContent = new Date().toLocaleDateString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric"
  });
  updateAcademicAlert();
  Charts.updateAcademic();
  $("logoutBtn").addEventListener("click", () => {
    localStorage.removeItem("studentLoggedIn");
    localStorage.removeItem("studentUsername");
    window.location.href = "../lab3_2411600038/indext.html";
  });

  const navLinks = document.querySelectorAll(".sidebar .nav-link");
  const setActiveLink = () => {
    navLinks.forEach(link => link.classList.toggle("active", link.getAttribute("href") === window.location.hash || (!window.location.hash && link.getAttribute("href") === "#dashboard")));
  };
  navLinks.forEach(link => link.addEventListener("click", setActiveLink));
  window.addEventListener("hashchange", setActiveLink);
  setActiveLink();
});

function updateAcademicAlert() {
  const attentionCourses = ["NET 205"];
  const alert = $("academicAlert");
  if (!alert || !attentionCourses.length) return;
  alert.querySelector("span").innerHTML = `${attentionCourses.length} course(s) need attention: <b>${attentionCourses.join(", ")}</b>.`;
}

function populateCategories() {
  const select = $("categoryFilter");
  const categories = [...new Set(DataManager.getProducts().map(p => p.category))].sort();
  categories.forEach(category => {
    const option = document.createElement("option");
    option.value = category;
    option.textContent = category;
    select.appendChild(option);
  });
}

function bindEvents() {
  $("logoutBtn").addEventListener("click", () => {
    localStorage.removeItem("studentLoggedIn");
    localStorage.removeItem("studentUsername");
    window.location.href = "../lab3_2411600038/indext.html";
  });
  $("applyBtn").addEventListener("click", readFiltersAndRender);
  $("resetBtn").addEventListener("click", resetFilters);
  $("exportBtn").addEventListener("click", exportCurrentCSV);
  $("simulateBtn").addEventListener("click", () => {
    const changed = DataManager.simulateInventoryUpdate();
    showToast(changed ? `${changed.name} stock updated.` : "No update performed.");
    render();
  });

  $("searchInput").addEventListener("input", readFiltersAndRender);
  ["categoryFilter", "statusFilter", "minPrice", "maxPrice"].forEach(id => {
    $(id).addEventListener("change", readFiltersAndRender);
  });
}

function readFiltersAndRender() {
  state = {
    category: $("categoryFilter").value,
    status: $("statusFilter").value,
    minPrice: $("minPrice").value,
    maxPrice: $("maxPrice").value,
    search: $("searchInput").value
  };
  render();
}

function resetFilters() {
  state = { category: "all", status: "all", minPrice: "", maxPrice: "", search: "" };
  $("categoryFilter").value = "all";
  $("statusFilter").value = "all";
  $("minPrice").value = "";
  $("maxPrice").value = "";
  $("searchInput").value = "";
  render();
}

function render() {
  const data = DataManager.applyFilters(state);
  const stats = DataManager.getStockStatistics(data);

  $("totalProducts").textContent = stats.totalProducts;
  $("totalValue").textContent = peso(stats.totalValue);
  $("lowStockCount").textContent = stats.lowStock;
  $("outOfStockCount").textContent = stats.outOfStock;
  $("resultCount").textContent = `${data.length} result${data.length === 1 ? "" : "s"}`;
  $("lastUpdated").textContent = `Last updated: ${new Date().toLocaleTimeString()}`;

  renderAlerts();
  renderTable(data);
  renderChanges();
  Charts.update(data);
}

function renderTable(data) {
  const body = $("inventoryBody");
  body.innerHTML = "";

  data.forEach(product => {
    const tr = document.createElement("tr");
    if (product.status === "low stock") tr.classList.add("low-stock-row");
    if (product.status === "out of stock") tr.classList.add("out-stock-row");

    tr.innerHTML = `
      <td>${escapeHTML(product.name)}</td>
      <td>${escapeHTML(product.sku)}</td>
      <td>${escapeHTML(product.category)}</td>
      <td>${escapeHTML(product.supplier)}</td>
      <td>${peso(product.unit_price)}</td>
      <td>${product.quantity}</td>
      <td>${product.reorder_level}</td>
      <td><span class="badge ${statusClass(product.status)} status-badge">${titleCase(product.status)}</span></td>
      <td>${peso(product.inventory_value)}</td>
    `;
    body.appendChild(tr);
  });
}

function renderAlerts() {
  const low = DataManager.getLowStockProducts();
  const area = $("alertArea");
  const list = $("lowStockList");

  area.innerHTML = "";
  list.innerHTML = "";

  if (low.length) {
    const alert = document.createElement("div");
    alert.className = "alert alert-warning";
    alert.innerHTML = `<strong>Low Stock Alert:</strong> ${low.length} product(s) are at or below their reorder level.`;
    area.appendChild(alert);
  } else {
    const alert = document.createElement("div");
    alert.className = "alert alert-success";
    alert.textContent = "Inventory is currently above all reorder levels.";
    area.appendChild(alert);
  }

  if (!low.length) {
    list.innerHTML = '<div class="text-muted">No low-stock products.</div>';
    return;
  }

  low.forEach(p => {
    const item = document.createElement("div");
    item.className = "alert alert-warning d-flex justify-content-between align-items-center";
    item.innerHTML = `
      <span><strong>${escapeHTML(p.name)}</strong> (${escapeHTML(p.sku)}) — ${p.quantity} unit(s) left; reorder level: ${p.reorder_level}</span>
      <span class="badge text-bg-warning">${titleCase(p.status)}</span>
    `;
    list.appendChild(item);
  });
}

function renderChanges() {
  const container = $("changesList");
  container.innerHTML = "";
  DataManager.getChanges().slice(0, 8).forEach(change => {
    const item = document.createElement("div");
    item.className = "list-group-item";
    item.innerHTML = `<small class="text-muted">${change.time.toLocaleTimeString()}</small><br>${escapeHTML(change.message)}`;
    container.appendChild(item);
  });
}

function exportCurrentCSV() {
  const data = DataManager.applyFilters(state);
  const headers = ["Product", "SKU", "Category", "Supplier", "Unit Price", "Quantity", "Reorder Level", "Status", "Inventory Value"];
  const rows = data.map(p => [
    p.name, p.sku, p.category, p.supplier, p.unit_price,
    p.quantity, p.reorder_level, p.status, p.inventory_value
  ]);

  const csv = [headers, ...rows]
    .map(row => row.map(csvEscape).join(","))
    .join("\r\n");

  const blob = new Blob(["\ufeff" + csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.href = url;
  link.download = `inventory_export_${new Date().toISOString().slice(0,10)}.csv`;
  document.body.appendChild(link);
  link.click();
  link.remove();
  URL.revokeObjectURL(url);
  showToast("CSV export created successfully.");
}

function startRealtimeSimulation() {
  setInterval(() => {
    const changed = DataManager.simulateInventoryUpdate();
    if (changed) {
      showToast(`Real-time simulation: ${changed.name} is now ${changed.quantity} unit(s).`);
      render();
    }
  }, 15000);
}

function showToast(message) {
  const area = $("toastArea");
  const alert = document.createElement("div");
  alert.className = "alert alert-info alert-dismissible fade show shadow-sm";
  alert.innerHTML = `${escapeHTML(message)} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  area.prepend(alert);
  setTimeout(() => alert.remove(), 5000);
}

function peso(value) {
  return new Intl.NumberFormat("en-PH", { style: "currency", currency: "PHP" }).format(value);
}

function statusClass(status) {
  if (status === "in stock") return "text-bg-success";
  if (status === "low stock") return "text-bg-warning";
  return "text-bg-danger";
}

function titleCase(text) {
  return text.replace(/\b\w/g, c => c.toUpperCase());
}

function csvEscape(value) {
  const text = String(value ?? "");
  return `"${text.replaceAll('"', '""')}"`;
}

function escapeHTML(value) {
  return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}
