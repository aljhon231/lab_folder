<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::query()->where('email', 'student@school.local')->first();

        $courses = [
            ['name' => 'Information Management', 'code' => 'IT-101', 'category' => 'Database', 'enrolled_students' => 28, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Reyes', 'department' => 'IT Department', 'description' => 'Data, records, and information systems for IT students.'],
            ['name' => 'Database Management Systems', 'code' => 'IT-102', 'category' => 'Database', 'enrolled_students' => 40, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Santos', 'department' => 'IT Department', 'description' => 'Database design, SQL, and data modeling.'],
            ['name' => 'Computer Networks', 'code' => 'IT-103', 'category' => 'Networking', 'enrolled_students' => 18, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Cruz', 'department' => 'IT Department', 'description' => 'Networking technologies and communication systems.'],
            ['name' => 'Web Development', 'code' => 'IT-104', 'category' => 'Web', 'enrolled_students' => 32, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Dela Cruz', 'department' => 'Programming Lab', 'description' => 'Frontend and backend web programming.'],
            ['name' => 'Software Engineering', 'code' => 'IT-105', 'category' => 'Programming', 'enrolled_students' => 12, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Garcia', 'department' => 'Software Lab', 'description' => 'Software lifecycle and engineering principles.'],
            ['name' => 'Operating Systems', 'code' => 'IT-106', 'category' => 'Systems', 'enrolled_students' => 22, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Lim', 'department' => 'Computer Lab', 'description' => 'Process, memory, and operating system architecture.'],
            ['name' => 'Discrete Mathematics', 'code' => 'IT-107', 'category' => 'Programming', 'enrolled_students' => 14, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Tan', 'department' => 'IT Department', 'description' => 'Logic, sets, and problem-solving methods for computing.'],
            ['name' => 'Algorithms', 'code' => 'IT-108', 'category' => 'Programming', 'enrolled_students' => 9, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Ramos', 'department' => 'Computer Science Dept', 'description' => 'Design and analysis of efficient algorithms.'],
            ['name' => 'Data Structures', 'code' => 'IT-109', 'category' => 'Programming', 'enrolled_students' => 25, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Mendoza', 'department' => 'Programming Lab', 'description' => 'Core structures and data organization.'],
            ['name' => 'Information Security', 'code' => 'IT-110', 'category' => 'Security', 'enrolled_students' => 7, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Navarro', 'department' => 'IT Department', 'description' => 'Cybersecurity fundamentals and secure computing.'],
            ['name' => 'Human Computer Interaction', 'code' => 'IT-111', 'category' => 'Web', 'enrolled_students' => 16, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Villanueva', 'department' => 'IT Department', 'description' => 'User interface design and usability for IT systems.'],
            ['name' => 'Mobile Application Development', 'code' => 'IT-112', 'category' => 'Programming', 'enrolled_students' => 21, 'capacity' => 40, 'units' => 3, 'instructor' => 'Prof. Bautista', 'department' => 'Programming Lab', 'description' => 'Building mobile apps for Android and related platforms.'],
        ];

        foreach ($courses as $item) {
            $course = Course::query()->updateOrCreate(
                ['code' => $item['code']],
                $item
            );

            if ($student && $course->enrollments()->doesntExist() && $course->enrolled_students > 0) {
                Enrollment::create([
                    'course_id' => $course->id,
                    'user_id' => $student->id,
                    'type' => 'add',
                    'quantity' => $course->enrolled_students,
                    'reference' => 'SEED-ENROLL',
                ]);
            }
        }
    }
}
