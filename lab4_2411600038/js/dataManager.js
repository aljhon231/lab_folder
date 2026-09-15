const DataManager = (() => {
  let products = [];
  let changes = [];

  const initialProducts = [
    { id: 1, name: "Laptop Pro 14", sku: "SKU-001", category: "Electronics", supplier: "TechSource", unit_price: 52000, quantity: 12, reorder_level: 5 },
    { id: 2, name: "Wireless Mouse", sku: "SKU-002", category: "Electronics", supplier: "TechSource", unit_price: 850, quantity: 8, reorder_level: 10 },
    { id: 3, name: "Mechanical Keyboard", sku: "SKU-003", category: "Electronics", supplier: "KeyWorks", unit_price: 3200, quantity: 18, reorder_level: 6 },
    { id: 4, name: "Office Chair", sku: "SKU-004", category: "Furniture", supplier: "FurniHub", unit_price: 7800, quantity: 4, reorder_level: 5 },
    { id: 5, name: "Standing Desk", sku: "SKU-005", category: "Furniture", supplier: "FurniHub", unit_price: 12500, quantity: 9, reorder_level: 4 },
    { id: 6, name: "Printer Paper A4", sku: "SKU-006", category: "Office Supplies", supplier: "PaperPlus", unit_price: 280, quantity: 35, reorder_level: 15 },
    { id: 7, name: "Ink Cartridge Black", sku: "SKU-007", category: "Office Supplies", supplier: "PrintMax", unit_price: 1450, quantity: 3, reorder_level: 5 },
    { id: 8, name: "USB-C Hub", sku: "SKU-008", category: "Accessories", supplier: "GadgetPro", unit_price: 2100, quantity: 0, reorder_level: 5 },
    { id: 9, name: "Webcam HD", sku: "SKU-009", category: "Accessories", supplier: "GadgetPro", unit_price: 2900, quantity: 14, reorder_level: 6 },
    { id: 10, name: "Monitor 24-inch", sku: "SKU-010", category: "Electronics", supplier: "VisionTech", unit_price: 9800, quantity: 7, reorder_level: 5 },
    { id: 11, name: "External SSD 1TB", sku: "SKU-011", category: "Electronics", supplier: "StorageWorks", unit_price: 6200, quantity: 5, reorder_level: 6 },
    { id: 12, name: "Filing Cabinet", sku: "SKU-012", category: "Furniture", supplier: "FurniHub", unit_price: 6500, quantity: 2, reorder_level: 3 }
  ];

  const statusOf = p => {
    if (p.quantity === 0) return "out of stock";
    if (p.quantity <= p.reorder_level) return "low stock";
    return "in stock";
  };

  const clone = data => JSON.parse(JSON.stringify(data));

  async function initializeData() {
    try {
      const response = await fetch("data/products.json", { cache: "no-store" });
      if (!response.ok) throw new Error(`Data request failed: ${response.status}`);
      const loadedProducts = await response.json();
      if (!Array.isArray(loadedProducts) || !loadedProducts.length) {
        throw new Error("Product data is empty.");
      }
      products = clone(loadedProducts);
    } catch (error) {
      products = clone(initialProducts);
      console.warn("Using embedded inventory data:", error.message);
    }
    changes = [{
      time: new Date(),
      message: "Inventory data initialized successfully."
    }];
    return getProducts();
  }

  function getProducts() {
    return products.map(p => ({ ...p, status: statusOf(p), inventory_value: p.quantity * p.unit_price }));
  }

  function getProductById(id) {
    const p = products.find(item => item.id === Number(id));
    return p ? { ...p, status: statusOf(p), inventory_value: p.quantity * p.unit_price } : null;
  }

  function getProductsByCategory(category) {
    if (!category || category === "all") return getProducts();
    return getProducts().filter(p => p.category.toLowerCase() === category.toLowerCase());
  }

  function getLowStockProducts() {
    return getProducts().filter(p => p.quantity <= p.reorder_level);
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

  function filterByCategory(data, category) {
    if (!category || category === "all") return data;
    return data.filter(p => p.category.toLowerCase() === category.toLowerCase());
  }

  function filterByStockStatus(data, status) {
    if (!status || status === "all") return data;
    return data.filter(p => p.status === status);
  }

  function filterByPriceRange(data, min, max) {
    const minVal = min === "" || min == null ? 0 : Number(min);
    const maxVal = max === "" || max == null ? Infinity : Number(max);
    return data.filter(p => p.unit_price >= minVal && p.unit_price <= maxVal);
  }

  function searchProducts(data, query) {
    const q = String(query || "").trim().toLowerCase();
    if (!q) return data;
    return data.filter(p =>
      p.name.toLowerCase().includes(q) ||
      p.sku.toLowerCase().includes(q)
    );
  }

  function applyFilters(filters = {}) {
    let result = getProducts();
    result = filterByCategory(result, filters.category);
    result = filterByStockStatus(result, filters.status);
    result = filterByPriceRange(result, filters.minPrice, filters.maxPrice);
    result = searchProducts(result, filters.search);
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
    getProductById,
    getProductsByCategory,
    getLowStockProducts,
    getStockStatistics,
    getCategorySummary,
    filterByCategory,
    filterByStockStatus,
    filterByPriceRange,
    searchProducts,
    applyFilters,
    updateProductStock,
    simulateInventoryUpdate,
    getChanges
  };
})();
