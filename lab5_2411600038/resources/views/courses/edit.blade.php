@extends('layouts.app')

@section('title', 'Edit Subject | Student Portal')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Subject</h2>
            <p class="text-muted mb-0">Update this IT course and adjust how many students are enrolled.</p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Back to courses</a>
    </div>

    <div class="card dashboard-card">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.update', $course) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $course->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">IT Category</label>
                        <select name="category" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $course->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Instructor</label>
                        <input type="text" name="instructor" class="form-control" value="{{ old('instructor', $course->instructor) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $course->department) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Students</label>
                        <input type="number" name="enrolled_students" class="form-control" min="0" value="{{ old('enrolled_students', $course->enrolled_students) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity', $course->capacity) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Units</label>
                        <input type="number" name="units" class="form-control" min="1" max="6" value="{{ old('units', $course->units) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4 mb-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
@endsection
