const Charts = (() => {
  const instances = {};

  function makeChart(id, config) {
    if (instances[id]) instances[id].destroy();
    const canvas = document.getElementById(id);
    if (!canvas) return;
    instances[id] = new Chart(canvas, config);
  }

  function updateAcademic() {
    if (typeof Chart === "undefined") return;

    const labels = ["IM 204", "NET 205", "HCI 206", "SE 207", "WS 201", "PF 203", "DB 202", "MIT 208"];
    const grades = [2.25, 2.50, 1.25, 1.75, 1.50, 1.50, 2.00, 1.75];
    const attendance = [94, 91, 100, 97, 96, 98, 95, 93];

    const common = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { labels: { usePointStyle: true, padding: 14 } } }
    };

    makeChart("gradeByCourseChart", {
      type: "bar",
      data: { labels, datasets: [{ label: "Grade", data: grades, backgroundColor: "#73b5d1", borderRadius: 3 }] },
      options: { ...common, scales: { y: { reverse: true, min: 1, max: 3, ticks: { stepSize: .5 } } } }
    });

    makeChart("coursePerformanceChart", {
      type: "doughnut",
      data: { labels: ["Excellent", "Good", "Needs Attention"], datasets: [{ data: [5, 2, 1], backgroundColor: ["#1674b8", "#c44952", "#bd7110"], borderWidth: 0 }] },
      options: { ...common, cutout: "58%" }
    });

    makeChart("attendanceTrendChart", {
      type: "line",
      data: { labels, datasets: [{ label: "Attendance %", data: attendance, borderColor: "#267b9f", backgroundColor: "rgba(38,123,159,.12)", fill: true, tension: .35 }] },
      options: { ...common, scales: { y: { min: 80, max: 100 } } }
    });

    makeChart("topCoursesChart", {
      type: "bar",
      data: { labels: ["HCI 206", "PF 203", "WS 201", "SE 207", "DB 202"], datasets: [{ label: "Best Grade", data: [1.25, 1.50, 1.50, 1.75, 2.00], backgroundColor: "#73b5d1", borderRadius: 3 }] },
      options: { ...common, indexAxis: "y", scales: { x: { reverse: true, min: 1, max: 3 } }, plugins: { legend: { display: false } } }
    });
  }

  return { updateAcademic };
})();
