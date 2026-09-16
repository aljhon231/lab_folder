const AcademicDataManager = (() => {
  let courses = [];
  let activities = [
    { activity: "Submitted Laboratory Exercise 4", course: "Web Systems and Technologies", date: "2026-09-07", status: "Completed" },
    { activity: "Submitted Database Quiz", course: "Information Management", date: "2026-09-06", status: "Completed" },
    { activity: "Networking Assignment", course: "Networking Fundamentals", date: "2026-09-05", status: "Pending" },
    { activity: "Attendance Recorded", course: "Human Computer Interaction", date: "2026-09-04", status: "Completed" },
    { activity: "Project Proposal", course: "Software Engineering", date: "2026-09-03", status: "Pending" }
  ];

  const fallbackCourses = [
    { id: 1, name: "Information Management", code: "IM 204", category: "Database", grade: 2.25, attendance: 94, status: "Good", semester: "First Semester, 2026" },
    { id: 2, name: "Networking Fundamentals", code: "NET 205", category: "Networking", grade: 2.50, attendance: 91, status: "Needs Attention", semester: "First Semester, 2026" },
    { id: 3, name: "Human Computer Interaction", code: "HCI 206", category: "Core", grade: 1.25, attendance: 100, status: "Excellent", semester: "First Semester, 2026" },
    { id: 4, name: "Software Engineering", code: "SE 207", category: "Programming", grade: 1.75, attendance: 97, status: "Excellent", semester: "First Semester, 2026" },
    { id: 5, name: "Web Systems and Technologies", code: "WS 201", category: "Major", grade: 1.50, attendance: 96, status: "Excellent", semester: "First Semester, 2026" },
    { id: 6, name: "Programming Fundamentals", code: "PF 203", category: "Programming", grade: 1.50, attendance: 98, status: "Excellent", semester: "First Semester, 2026" },
    { id: 7, name: "Database Management Systems", code: "DB 202", category: "Database", grade: 2.00, attendance: 95, status: "Good", semester: "First Semester, 2026" },
    { id: 8, name: "Mathematics in Information Technology", code: "MIT 208", category: "Core", grade: 1.75, attendance: 93, status: "Good", semester: "First Semester, 2026" }
  ];

  const clone = value => JSON.parse(JSON.stringify(value));

  async function initializeData() {
    try {
      let response = await fetch("api/courses.php", { cache: "no-store" });
      if (!response.ok) throw new Error(`Course request failed: ${response.status}`);
      const payload = await response.json();
      courses = payload.data;
      if (!Array.isArray(courses) || !courses.length) throw new Error("Course data is empty.");
    } catch (error) {
      try {
        const response = await fetch("data/courses.json", { cache: "no-store" });
        courses = await response.json();
        if (!Array.isArray(courses) || !courses.length) throw new Error("Course JSON is empty.");
      } catch (fallbackError) {
        courses = clone(fallbackCourses);
        console.warn("Using embedded course data:", fallbackError.message);
      }
    }
    return getCourses();
  }

  function getCourses() { return clone(courses); }
  function getCourseById(id) { return courses.find(course => course.id === Number(id)) || null; }
  function getCategories() { return [...new Set(courses.map(course => course.category))].sort(); }
  function getStatuses() { return ["Excellent", "Good", "Needs Attention"]; }
  function getAcademicStatistics(data = courses) {
    const averageGrade = data.length ? data.reduce((sum, course) => sum + course.grade, 0) / data.length : 0;
    const averageAttendance = data.length ? data.reduce((sum, course) => sum + course.attendance, 0) / data.length : 0;
    return {
      courses: data.length,
      gpa: averageGrade.toFixed(2),
      attendance: `${Math.round(averageAttendance)}%`,
      attention: data.filter(course => course.status === "Needs Attention")
    };
  }
  function filterByCategory(data, category) { return !category || category === "all" ? data : data.filter(course => course.category === category); }
  function filterByStatus(data, status) { return !status || status === "all" ? data : data.filter(course => course.status === status); }
  function searchCourses(data, query) {
    const value = String(query || "").trim().toLowerCase();
    return value ? data.filter(course => `${course.name} ${course.code}`.toLowerCase().includes(value)) : data;
  }
  function sortCourses(data, sort) {
    return [...data].sort((a, b) => sort === "grade" ? a.grade - b.grade : sort === "name" ? a.name.localeCompare(b.name) : a.id - b.id);
  }
  function applyFilters(filters = {}) {
    let result = getCourses();
    result = filterByCategory(result, filters.category);
    result = filterByStatus(result, filters.status);
    if (filters.semester && filters.semester !== "all") {
      result = result.filter(course => course.semester === filters.semester);
    }
    result = searchCourses(result, filters.search);
    return sortCourses(result, filters.sort);
  }
  function getActivities() { return clone(activities); }
  function addSimulatedActivity() {
    const course = courses[Math.floor(Math.random() * courses.length)];
    const previous = course.attendance;
    course.attendance = Math.min(100, course.attendance + (Math.random() > .5 ? 1 : -1));
    activities.unshift({ activity: "Attendance Updated", course: course.name, date: new Date().toISOString().slice(0, 10), status: "Completed" });
    return { course, previous };
  }
  function exportToCSV(data) {
    const headers = ["Subject", "Code", "Category", "Grade", "Attendance", "Status", "Semester"];
    return [headers, ...data.map(course => [course.name, course.code, course.category, course.grade, `${course.attendance}%`, course.status, course.semester])]
      .map(row => row.map(value => `"${String(value).replaceAll('"', '""')}"`).join(",")).join("\r\n");
  }

  return { initializeData, getCourses, getCourseById, getCategories, getStatuses, getAcademicStatistics, filterByCategory, filterByStatus, searchCourses, applyFilters, getActivities, addSimulatedActivity, exportToCSV };
})();
