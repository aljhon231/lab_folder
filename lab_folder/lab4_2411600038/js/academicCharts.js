const AcademicCharts = (() => {
  const instances = {};
  const colors = { blue: "#73b5d1", green: "#1674b8", red: "#c44952", orange: "#bd7110", line: "#267b9f" };

  function makeChart(id, config) {
    if (instances[id]) instances[id].destroy();
    const canvas = document.getElementById(id);
    if (!canvas || typeof Chart === "undefined") return;
    instances[id] = new Chart(canvas, config);
  }

  function update(courses) {
    if (typeof Chart === "undefined") return;
    const labels = courses.map(course => course.code);
    const common = { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { usePointStyle: true, padding: 14 } } } };

    makeChart("gradeByCourseChart", {
      type: "bar",
      data: { labels, datasets: [{ label: "Grade", data: courses.map(course => course.grade), backgroundColor: colors.blue, borderRadius: 3 }] },
      options: { ...common, scales: { y: { reverse: true, min: 1, max: 3, ticks: { stepSize: .5 } } } }
    });
    makeChart("coursePerformanceChart", {
      type: "doughnut",
      data: { labels: ["Excellent", "Good", "Needs Attention"], datasets: [{ data: ["Excellent", "Good", "Needs Attention"].map(status => courses.filter(course => course.status === status).length), backgroundColor: [colors.green, colors.red, colors.orange], borderWidth: 0 }] },
      options: { ...common, cutout: "58%" }
    });
    makeChart("attendanceTrendChart", {
      type: "line",
      data: { labels, datasets: [{ label: "Attendance %", data: courses.map(course => course.attendance), borderColor: colors.line, backgroundColor: "rgba(38,123,159,.12)", fill: true, tension: .35 }] },
      options: { ...common, scales: { y: { min: 80, max: 100 } } }
    });
    const top = [...courses].sort((a, b) => a.grade - b.grade).slice(0, 5);
    makeChart("topCoursesChart", {
      type: "bar",
      data: { labels: top.map(course => course.code), datasets: [{ label: "Best Grade", data: top.map(course => course.grade), backgroundColor: colors.blue, borderRadius: 3 }] },
      options: { ...common, indexAxis: "y", scales: { x: { reverse: true, min: 1, max: 3 } }, plugins: { legend: { display: false } } }
    });
  }

  return { update };
})();
