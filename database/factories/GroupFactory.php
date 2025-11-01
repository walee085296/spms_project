<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * اسم الموديل المرتبط بهذه الـ Factory
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * الحالة الافتراضية للنموذج (Default State)
     *
     * تُستخدم لتوليد بيانات تجريبية (Fake Data) عند إنشاء سجلات في جدول groups.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // رقم المشروع المرتبط بالمجموعة (يمكن أن يكون null أو رقم عشوائي)
            // هنا نولّد رقم مشروع عشوائي بين 1 و 20 (مثلاً لو عندك 20 مشروع في الجدول)
            'project_id' => $this->faker->numberBetween(1, 20),

            // حالة المجموعة (state)
            // مثلاً: looking for members | full | closed
            'state' => $this->faker->randomElement([
                'looking for members',
                'full',
                'closed'
            ]),

            // التخصص (spec) — مثل: "Computer Science", "Information Systems", إلخ
            'spec' => $this->faker->randomElement([
                'Computer Science',
                'Information Systems',
                'Software Engineering',
                'None'
            ]),

            // نوع المشروع (project_type) — مثل: "senior" أو "junior"
            'project_type' => $this->faker->randomElement([
                'senior',
                'junior'
            ]),
        ];
    }
}
