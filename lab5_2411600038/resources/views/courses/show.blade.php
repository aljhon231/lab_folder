@extends('layouts.app')

@section('title', $course->name . ' | Student Portal')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $course->name }}</h2>
            <p class="text-muted mb-0">Code: {{ $course->code }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('courses.index') }}" class="btn btn-primary">Back to Courses</a>
        </div>
    </div>

    @if ($course->isAtStudentAlert())
        <div class="academic-alert mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Student Capacity Alert</strong>
                <span>{{ $course->name }} already has {{ $course->enrolled_students }} students. This subject has reached 40 students.</span>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Enrollment Summary</h5>
                    <div class="mb-3">
                        <small class="text-muted">Students enrolled</small>
                        <div class="fs-3 fw-bold">{{ $course->enrolled_students }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Capacity</small>
                        <div class="fs-5 fw-semibold">{{ $course->capacity }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            @if ($course->isAtStudentAlert())
                                <span class="badge badge-out">Full (40 students)</span>
                            @elseif ($course->enrolled_students === 0)
                                <span class="badge badge-low">No Students</span>
                            @else
                                <span class="badge badge-ok">{{ $course->enrollmentStatus() }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Remaining slots</small>
                        <div class="fs-5 fw-semibold">{{ $course->remainingSlots() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Subject Details</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Category</dt>
                        <dd class="col-sm-8">{{ $course->category }}</dd>

                        <dt class="col-sm-4">Instructor</dt>
                        <dd class="col-sm-8">{{ $course->instructor }}</dd>

                        <dt class="col-sm-4">Department</dt>
                        <dd class="col-sm-8">{{ $course->department }}</dd>

                        <dt class="col-sm-4">Units</dt>
                        <dd class="col-sm-8">{{ $course->units }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $course->description ?: 'No description provided.' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Add or Adjust Students</h5>
            <form method="POST" action="{{ route('courses.enrollments.store', $course) }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Action</label>
                    <select name="type" class="form-select" required>
                        <option value="add">Add students</option>
                        <option value="remove">Remove students</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Number of students</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="e.g. New enrollees">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div>
            </form>
            @error('quantity')
                <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="card dashboard-card">
        <div class="card-body">
            <h5 class="mb-3">Enrollment History</h5>
            @if ($course->enrollments->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Students</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($course->enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge {{ $enrollment->isAdd() ? 'badge-ok' : 'badge-low' }}">
                                            {{ $enrollment->isAdd() ? 'Added' : 'Removed' }}
                                        </span>
                                    </td>
                                    <td>{{ $enrollment->quantity }}</td>
                                    <td>{{ $enrollment->reference ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No enrollment history available.</p>
            @endif
        </div>
    </div>
@endsection
