<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * اسم الموديل الذي تتبع له هذه الـ Factory.
     * 
     * Factory تعني "مولّد بيانات تجريبية"، تُستخدم لإنشاء سجلات وهمية داخل قاعدة البيانات
     * أثناء عملية الاختبار أو التطوير (seeding).
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * الحالة الافتراضية (Default State) للنموذج User.
     *
     * هنا يتم تحديد القيم العشوائية الافتراضية التي سيتم إدخالها في قاعدة البيانات
     * عند إنشاء مستخدم جديد باستخدام factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // الاسم الأول للمستخدم — يتم توليده عشوائيًا
            'first_name' => $this->faker->firstName(),

            // الاسم الأخير — يتم توليده عشوائيًا
            'last_name' => $this->faker->lastName(),

            // رقم الطالب (Student Serial Number) بين 1,000,000 و 6,999,999
            'stdsn' => $this->faker->numberBetween(1000000, 6999999),

            // رقم القسم (قسم الطالب) — هنا ثابت بقيمة 2 (يمكنك تغييره حسب الحاجة)
            'dept_id' => '2',

            // البريد الإلكتروني — فريد (unique) ويتم توليده تلقائيًا
            'email' => $this->faker->unique()->safeEmail(),

            // وقت التحقق من البريد الإلكتروني (يتم تعيينه إلى الوقت الحالي)
            'email_verified_at' => now(),

            // كلمة المرور مشفرة (bcrypt)
            // القيمة هنا تمثل كلمة المرور "12345678" بعد التشفير
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',

            // رمز التذكّر (Remember Token) لتسجيل الدخول التلقائي
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * حالة خاصة للمستخدم الذي لم يتم التحقق من بريده الإلكتروني بعد.
     *
     * تُستخدم عندما تريد إنشاء مستخدم تجريبي غير مُفعّل (unverified).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null, // لم يتم التحقق من البريد
            ];
        });
    }
}
