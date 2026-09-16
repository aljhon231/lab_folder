@extends('layouts.app')

@section('title', 'Add Subject | Student Portal')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Add Subject</h2>
            <p class="text-muted mb-0">Create a new IT course or subject.</p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Back to courses</a>
    </div>

    <div class="card dashboard-card">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. IT-113" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">IT Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Instructor</label>
                        <input type="text" name="instructor" class="form-control" value="{{ old('instructor') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', 'IT Department') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Students</label>
                        <input type="number" name="enrolled_students" class="form-control" min="0" value="{{ old('enrolled_students', 0) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity', 40) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Units</label>
                        <input type="number" name="units" class="form-control" min="1" max="6" value="{{ old('units', 3) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
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
                    <button type="submit" class="btn btn-primary">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
@endsection
