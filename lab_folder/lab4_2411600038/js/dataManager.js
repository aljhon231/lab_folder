const DataManager = (() => {
  let products = [];
  let changes = [];
  const API_URLS = [
    "http://127.0.0.1:8000/api/products",
    "/lab_folder/lab5_2411600038/public/api/products"
  ];

  const fallbackProducts = [
    { id: 1, name: "2x4 Pine Lumber 8ft", sku: "LUM-001", category: "Lumber", supplier: "ForestLine Mills", unit_price: 185, quantity: 48, reorder_level: 20 },
    { id: 2, name: "Claw Hammer 16oz", sku: "TLS-001", category: "Tools", supplier: "ProBuild Tools", unit_price: 390, quantity: 22, reorder_level: 6 },
    { id: 3, name: "Hinges 4-inch Pair", sku: "HDW-003", category: "Hardware", supplier: "SteelFix Hardware", unit_price: 145, quantity: 0, reorder_level: 6 }
  ];

  const statusOf = p => {
    if (Number(p.quantity) === 0) return "out of stock";
    if (Number(p.quantity) <= Number(p.reorder_level)) return "low stock";
    return "in stock";
  };

  const normalize = p => ({
    ...p,
    unit_price: Number(p.unit_price),
    quantity: Number(p.quantity),
    reorder_level: Number(p.reorder_level),
    status: p.status || statusOf(p),
    inventory_value: Number(p.inventory_value ?? (Number(p.quantity) * Number(p.unit_price)))
  });

  async function fetchFromLaravel() {
    for (const url of API_URLS) {
      try {
        const response = await fetch(url, { headers: { Accept: "application/json" } });
        if (!response.ok) continue;
        const payload = await response.json();
        const rows = Array.isArray(payload.data) ? payload.data : payload;
        if (rows.length) return rows.map(normalize);
      } catch (error) {
        console.warn("Inventory API not available at", url, error);
      }
    }
    return null;
  }

  async function initializeData() {
    const live = await fetchFromLaravel();
    products = live || fallbackProducts.map(normalize);
    changes = [{
      time: new Date(),
      message: live
        ? "Inventory loaded from Laravel MySQL (Lab 5)."
        : "Laravel API was offline. Showing fallback sample products."
    }];
    return getProducts();
  }

  function getProducts() {
    return products.map(p => normalize(p));
  }

  function getProductById(id) {
    const p = products.find(item => item.id === Number(id));
    return p ? normalize(p) : null;
  }

  function getLowStockProducts() {
    return getProducts().filter(p => p.status === "low stock" || p.status === "out of stock");
  }

  function getStockStatistics(data = getProducts()) {
    return {
      totalProducts: data.length,
      totalValue: data.reduce((sum, p) => sum + p.inventory_value, 0),
      lowStock: data.filter(p => p.status === "low stock").length,
      outOfStock: data.filter(p => p.status === "out of stock").length,
      inStock: data.filter(p => p.status === "in stock").length
    };
  }

  function getCategorySummary(data = getProducts()) {
    const grouped = {};
    data.forEach(p => {
      if (!grouped[p.category]) grouped[p.category] = { quantity: 0, value: 0 };
      grouped[p.category].quantity += p.quantity;
      grouped[p.category].value += p.inventory_value;
    });
    return Object.entries(grouped).map(([category, values]) => ({
      category,
      quantity: values.quantity,
      value: values.value
    }));
  }

  function applyFilters(filters = {}) {
    let result = getProducts();
    if (filters.category && filters.category !== "all") {
      result = result.filter(p => p.category.toLowerCase() === filters.category.toLowerCase());
    }
    if (filters.status && filters.status !== "all") {
      result = result.filter(p => p.status === filters.status);
    }
    const minVal = filters.minPrice === "" || filters.minPrice == null ? 0 : Number(filters.minPrice);
    const maxVal = filters.maxPrice === "" || filters.maxPrice == null ? Infinity : Number(filters.maxPrice);
    result = result.filter(p => p.unit_price >= minVal && p.unit_price <= maxVal);
    const q = String(filters.search || "").trim().toLowerCase();
    if (q) {
      result = result.filter(p => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q));
    }
    return result;
  }

  function updateProductStock(id, amount) {
    const p = products.find(item => item.id === Number(id));
    if (!p) return null;
    const oldQty = p.quantity;
    p.quantity = Math.max(0, p.quantity + amount);
    changes.unshift({
      time: new Date(),
      message: `${p.name}: stock changed from ${oldQty} to ${p.quantity}.`
    });
    return getProductById(p.id);
  }

  function simulateInventoryUpdate() {
    if (!products.length) return null;
    const index = Math.floor(Math.random() * products.length);
    const amount = Math.random() > 0.5 ? 1 : -1;
    return updateProductStock(products[index].id, amount);
  }

  function getChanges() {
    return [...changes];
  }

  return {
    initializeData,
    getProducts,
    getLowStockProducts,
    getStockStatistics,
    getCategorySummary,
    applyFilters,
    simulateInventoryUpdate,
    getChanges
  };
})();
