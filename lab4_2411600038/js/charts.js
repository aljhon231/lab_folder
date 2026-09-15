const Charts = (() => {
  const instances = {};

  function makeChart(id, config) {
    if (instances[id]) instances[id].destroy();
    const canvas = document.getElementById(id);
    if (!canvas) return;
    instances[id] = new Chart(canvas, config);
  }

  function update(data) {
    if (typeof Chart === "undefined") return;

    const categorySummary = DataManager.getCategorySummary(data);
    const stats = DataManager.getStockStatistics(data);

    makeChart("categoryValueChart", {
      type: "bar",
      data: {
        labels: categorySummary.map(x => x.category),
        datasets: [{
          label: "Inventory Value (₱)",
          data: categorySummary.map(x => x.value),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });

    makeChart("stockStatusChart", {
      type: "doughnut",
      data: {
        labels: ["In Stock", "Low Stock", "Out of Stock"],
        datasets: [{ data: [stats.inStock, stats.lowStock, stats.outOfStock] }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    const top = [...data]
      .sort((a, b) => b.inventory_value - a.inventory_value)
      .slice(0, 5);

    makeChart("topProductsChart", {
      type: "bar",
      data: {
        labels: top.map(p => p.name),
        datasets: [{
          label: "Inventory Value (₱)",
          data: top.map(p => p.inventory_value),
          borderWidth: 1
        }]
      },
      options: {
        indexAxis: "y",
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true } }
      }
    });

    makeChart("categoryQuantityChart", {
      type: "bar",
      data: {
        labels: categorySummary.map(x => x.category),
        datasets: [{
          label: "Quantity",
          data: categorySummary.map(x => x.quantity),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  return { update };
})();
