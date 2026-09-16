@extends('layouts.app')

@section('title', 'Courses | Student Portal')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">IT Courses</h2>
            <p class="text-muted mb-0">Live list of IT subjects, instructors, and enrolled students.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="live-pill"><span></span> Real-time subjects <small id="live-stamp" class="ms-1"></small></span>
            <a href="{{ route('courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Subject
            </a>
        </div>
    </div>

    @if ($alertCourses->isNotEmpty())
        <div class="academic-alert mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Student Capacity Alert</strong>
                <span>
                    {{ $alertCourses->count() }} subject(s) already have 40 students:
                    <b>{{ $alertCourses->pluck('name')->join(', ') }}</b>.
                </span>
            </div>
        </div>
    @endif

    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by subject, code, instructor">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="all">All IT categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card dashboard-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="courses-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Instructor</th>
                            <th>Students</th>
                            <th>Capacity</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="courses-body">
                        @forelse ($courses as $course)
                            <tr class="{{ $course->isAtStudentAlert() ? 'attention-row' : '' }}" data-course-id="{{ $course->id }}">
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->category }}</td>
                                <td>{{ $course->instructor }}</td>
                                <td>{{ $course->enrolled_students }}</td>
                                <td>{{ $course->capacity }}</td>
                                <td>{{ $course->units }}</td>
                                <td>
                                    @if ($course->isAtStudentAlert())
                                        <span class="badge badge-out">Full (40 students)</span>
                                    @elseif ($course->enrolled_students === 0)
                                        <span class="badge badge-low">No Students</span>
                                    @else
                                        <span class="badge badge-ok">{{ $course->enrollmentStatus() }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No IT subjects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const coursesApi = @json(route('api.courses', request()->only(['search', 'category'])));
    const viewBase = @json(url('/courses'));

    function statusBadge(course) {
        if (course.alert) {
            return '<span class="badge badge-out">Full (40 students)</span>';
        }
        if (course.enrolled_students === 0) {
            return '<span class="badge badge-low">No Students</span>';
        }
        return `<span class="badge badge-ok">${course.status}</span>`;
    }

    function renderCourses(courses) {
        const body = document.getElementById('courses-body');
        if (!courses.length) {
            body.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">No IT subjects found.</td></tr>';
            return;
        }

        body.innerHTML = courses.map((course) => `
            <tr class="${course.alert ? 'attention-row' : ''}" data-course-id="${course.id}">
                <td>${course.name}</td>
                <td>${course.code}</td>
                <td>${course.category}</td>
                <td>${course.instructor}</td>
                <td>${course.enrolled_students}</td>
                <td>${course.capacity}</td>
                <td>${course.units}</td>
                <td>${statusBadge(course)}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="${viewBase}/${course.id}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="${viewBase}/${course.id}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    async function refreshCourses() {
        try {
            const response = await fetch(coursesApi, { headers: { 'Accept': 'application/json' } });
            const payload = await response.json();
            renderCourses(payload.data || []);
            const stamp = document.getElementById('live-stamp');
            if (stamp) {
                stamp.textContent = new Date().toLocaleTimeString();
            }
        } catch (error) {
            console.error('Unable to refresh courses', error);
        }
    }

    refreshCourses();
    setInterval(refreshCourses, 4000);
</script>
@endpush
