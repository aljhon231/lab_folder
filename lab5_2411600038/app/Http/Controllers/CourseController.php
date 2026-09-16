<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::query()->latest();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('instructor', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $courses = $query->get();

        return view('courses.index', [
            'courses' => $courses,
            'categories' => Course::categories(),
            'alertCourses' => $courses->filter(fn (Course $course) => $course->isAtStudentAlert()),
        ]);
    }

    public function create(): View
    {
        return view('courses.create', [
            'categories' => Course::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCourse($request);

        $course = Course::create($data);

        if ($course->enrolled_students > 0) {
            Enrollment::create([
                'course_id' => $course->id,
                'user_id' => $request->user()->id,
                'type' => 'add',
                'quantity' => $course->enrolled_students,
                'reference' => 'Initial enrollment',
            ]);
        }

        $redirect = redirect()->route('courses.index')->with('success', 'IT subject was added to Courses.');

        if ($course->isAtStudentAlert()) {
            $redirect->with('warning', $this->alertMessage($course));
        }

        return $redirect;
    }

    public function show(Course $course): View
    {
        $course->load(['enrollments.user']);

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', [
            'course' => $course,
            'categories' => Course::categories(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $this->validatedCourse($request, $course->id);
        $previousCount = $course->enrolled_students;
        $course->update($data);

        if ((int) $data['enrolled_students'] !== $previousCount) {
            $difference = (int) $data['enrolled_students'] - $previousCount;
            Enrollment::create([
                'course_id' => $course->id,
                'user_id' => $request->user()->id,
                'type' => $difference >= 0 ? 'add' : 'remove',
                'quantity' => abs($difference),
                'reference' => 'Manual student adjustment',
            ]);
        }

        $redirect = redirect()->route('courses.index')->with('success', 'Subject details were updated.');

        if ($course->fresh()?->isAtStudentAlert()) {
            $redirect->with('warning', $this->alertMessage($course->fresh()));
        }

        return $redirect;
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Subject was removed from Courses.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedCourse(Request $request, ?int $courseId = null): array
    {
        $codeRule = 'required|string|max:50|unique:courses,code';

        if ($courseId) {
            $codeRule .= ','.$courseId;
        }

        return $request->validate(
            [
                'name' => 'required|string|max:120',
                'code' => $codeRule,
                'description' => 'nullable|string|max:1000',
                'category' => 'required|string|in:'.implode(',', Course::categories()),
                'instructor' => 'required|string|max:120',
                'department' => 'required|string|max:120',
                'enrolled_students' => 'required|integer|min:0',
                'capacity' => 'required|integer|min:1',
                'units' => 'required|integer|min:1|max:6',
            ],
            [
                'name.required' => 'Please enter the subject name.',
                'code.required' => 'A unique course code is required.',
                'code.unique' => 'That course code is already assigned to another subject.',
                'category.required' => 'Please choose an IT category.',
                'instructor.required' => 'Please enter the instructor name.',
                'department.required' => 'Please enter the department.',
                'enrolled_students.min' => 'Student count cannot be negative.',
            ]
        );
    }

    private function alertMessage(Course $course): string
    {
        return $course->name.' already has '.$course->enrolled_students.' students. Alert: 40 students have been placed in this subject.';
    }
}
