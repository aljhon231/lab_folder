<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate(
            [
                'type' => 'required|in:add,remove',
                'quantity' => 'required|integer|min:1',
                'reference' => 'nullable|string|max:120',
            ],
            [
                'type.in' => 'Choose either add students or remove students.',
                'quantity.min' => 'Student count must be at least 1.',
            ]
        );

        if ($data['type'] === 'remove' && $data['quantity'] > $course->enrolled_students) {
            return back()->withErrors([
                'quantity' => 'You cannot remove more students than are currently enrolled.',
            ])->withInput();
        }

        $course->enrolled_students = $data['type'] === 'add'
            ? $course->enrolled_students + $data['quantity']
            : $course->enrolled_students - $data['quantity'];
        $course->save();

        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reference' => $data['reference'] ?: strtoupper($data['type']).'-'.now()->format('YmdHis'),
        ]);

        $redirect = back()->with('success', $data['type'] === 'add'
            ? 'Students were added to this subject.'
            : 'Student count was adjusted for this subject.');

        if ($course->isAtStudentAlert()) {
            $redirect->with('warning', $course->name.' now has '.$course->enrolled_students.' students. Alert: 40 students have been placed in this subject.');
        }

        return $redirect;
    }
}
