let academicState = { category: "all", status: "all", search: "", sort: "default" };
const academic$ = id => document.getElementById(id);

window.addEventListener("DOMContentLoaded", async () => {
  const username = localStorage.getItem("studentUsername") || "student";
  academic$("navUsername").textContent = username;
  academic$("sidebarUsername").textContent = username;
  academic$("greeting").textContent = `Good Afternoon, ${username}!`;
  academic$("currentDate").textContent = new Date().toLocaleDateString("en-US", { weekday: "long", year: "numeric", month: "long", day: "numeric" });
  await AcademicDataManager.initializeData();
  populateCategoryFilter();
  bindAcademicEvents();
  renderAcademicDashboard();
  startAcademicSimulation();
});

function populateCategoryFilter() {
  const select = academic$("categoryFilter");
  AcademicDataManager.getCategories().forEach(category => {
    const option = document.createElement("option");
    option.value = category;
    option.textContent = category;
    select.appendChild(option);
  });
}

function bindAcademicEvents() {
  academic$("logoutBtn").addEventListener("click", () => {
    localStorage.removeItem("studentLoggedIn");
    localStorage.removeItem("studentUsername");
    window.location.href = "../lab3_2411600038/indext.html";
  });
  academic$("searchInput").addEventListener("input", readAcademicFilters);
  ["categoryFilter", "statusFilter", "sortFilter", "semesterFilter"].forEach(id => academic$(id).addEventListener("change", readAcademicFilters));
  academic$("resetBtn").addEventListener("click", resetAcademicFilters);
  academic$("exportBtn").addEventListener("click", exportAcademicCSV);
  academic$("simulateBtn").addEventListener("click", () => {
    AcademicDataManager.addSimulatedActivity();
    renderAcademicDashboard();
    showAcademicToast("Attendance data updated in real time.");
  });
  const navLinks = document.querySelectorAll(".sidebar .nav-link");
  const updateActive = () => navLinks.forEach(link => link.classList.toggle("active", link.getAttribute("href") === window.location.hash || (!window.location.hash && link.getAttribute("href") === "#dashboard")));
  navLinks.forEach(link => link.addEventListener("click", updateActive));
  window.addEventListener("hashchange", updateActive);
  updateActive();
}

function readAcademicFilters() {
  academicState = { category: academic$("categoryFilter").value, status: academic$("statusFilter").value, search: academic$("searchInput").value, sort: academic$("sortFilter").value, semester: academic$("semesterFilter").value };
  renderAcademicDashboard();
}

function resetAcademicFilters() {
  academicState = { category: "all", status: "all", search: "", sort: "default", semester: "First Semester, 2026" };
  academic$("categoryFilter").value = "all";
  academic$("statusFilter").value = "all";
  academic$("searchInput").value = "";
  academic$("sortFilter").value = "default";
  academic$("semesterFilter").value = "First Semester, 2026";
  renderAcademicDashboard();
}

function renderAcademicDashboard() {
  const courses = AcademicDataManager.applyFilters(academicState);
  const stats = AcademicDataManager.getAcademicStatistics(courses);
  academic$("gpaValue").textContent = stats.gpa;
  academic$("courseCount").textContent = stats.courses;
  academic$("attendanceValue").textContent = stats.attendance;
  academic$("academicAlert").querySelector("span").innerHTML = stats.attention.length ? `${stats.attention.length} course(s) need attention: <b>${stats.attention.map(course => course.code).join(", ")}</b>.` : "All courses are currently on track.";
  renderCourses(courses);
  renderActivities();
  AcademicCharts.update(courses);
}

function renderCourses(courses) {
  const body = academic$("courseBody");
  const query = academicState.search.trim();
  academic$("courseResultSummary").textContent = `Showing ${courses.length} course${courses.length === 1 ? "" : "s"}`;
  if (!courses.length) {
    body.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No courses found. Try another search or filter.</td></tr>';
    return;
  }
  body.innerHTML = courses.map(course => `<tr class="${course.status === "Needs Attention" ? "attention-row" : ""}"><td>${highlightCourseText(course.name, query)}</td><td>${highlightCourseText(course.code, query)}</td><td>${academicEscape(course.category)}</td><td>${course.grade.toFixed(2)}</td><td>${course.attendance}%</td><td><span class="badge ${course.status === "Needs Attention" ? "text-bg-danger" : course.status === "Good" ? "text-bg-warning" : "text-bg-success"}">${course.status}</span></td></tr>`).join("");
}

function highlightCourseText(value, query) {
  const escaped = academicEscape(value);
  if (!query) return escaped;
  const pattern = query.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  return escaped.replace(new RegExp(`(${pattern})`, "ig"), "<mark>$1</mark>");
}

function renderActivities() {
  const body = academic$("activityBody");
  if (!body) return;
  body.innerHTML = AcademicDataManager.getActivities().map(item => `<tr><td>${academicEscape(item.activity)}</td><td>${academicEscape(item.course)}</td><td>${item.date}</td><td><span class="badge ${item.status === "Pending" ? "text-bg-warning" : "text-bg-success"}">${item.status}</span></td></tr>`).join("");
}

function exportAcademicCSV() {
  const csv = AcademicDataManager.exportToCSV(AcademicDataManager.applyFilters(academicState));
  const link = document.createElement("a");
  link.href = URL.createObjectURL(new Blob(["\ufeff" + csv], { type: "text/csv;charset=utf-8" }));
  link.download = `student_courses_${new Date().toISOString().slice(0, 10)}.csv`;
  link.click();
  URL.revokeObjectURL(link.href);
  showAcademicToast("Course report exported successfully.");
}

function startAcademicSimulation() { setInterval(() => { AcademicDataManager.addSimulatedActivity(); renderAcademicDashboard(); }, 30000); }
function showAcademicToast(message) { const toast = document.createElement("div"); toast.className = "academic-toast"; toast.textContent = message; document.body.appendChild(toast); setTimeout(() => toast.remove(), 3500); }
function academicEscape(value) { return String(value ?? "").replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;").replaceAll('"', "&quot;").replaceAll("'", "&#039;"); }
