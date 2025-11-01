<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Enums\Specialization; // استدعاء Enum للتخصصات
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role; // استدعاء الـ Role لإدارة الصلاحيات
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash; // لتشفير الباسورد
use Illuminate\Support\Facades\Http; // لإرسال طلبات HTTP
use Illuminate\Validation\Rules\Enum; // للتحقق من صحة الـ Enum
use Laravel\Socialite\Facades\Socialite; // لاستخدام Socialite للتواصل مع GitHub

class UserController extends Controller
{
    /**
     * إنشاء middleware للتحكم في الصلاحيات
     */
    function __construct()
    {
        // السماح بالوصول للـ index و store فقط لمن لديهم أي صلاحية من هذه
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);

        // السماح بالوصول لإنشاء مستخدم فقط لمن لديهم صلاحية create
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);

        // السماح بالوصول لتعديل مستخدم فقط لمن لديهم صلاحية edit
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);

        // السماح بالوصول لحذف مستخدم فقط لمن لديهم صلاحية delete
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        // جلب المستخدمين مع الأدوار الخاصة بهم، مع ترتيبهم من الأحدث
        $users = User::with('roles')->latest()->filter(request(['search', 'spec']))
            ->paginate(10) // تقسيم الصفحات كل 10 مستخدمين
            ->withQueryString(); // الاحتفاظ بباراميترات البحث في الروابط

        $specs = Specialization::cases(); // جلب كل التخصصات
        $roles = Role::pluck('name', 'name')->all(); // جلب كل الأدوار

        // تمرير البيانات إلى صفحة index
        return view('users.index', compact('users', 'specs', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5); // حساب رقم الصف للترقيم
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        $specs = Specialization::cases(); // جلب التخصصات
        $roles = Role::pluck('name', 'name')->all(); // جلب كل الأدوار
        return view('users.create', compact('roles', 'specs')); // تمرير البيانات للواجهة
    }

    /**
     * تخزين مستخدم جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $this->validate($request, [
            'first_name' => 'required', // الاسم الأول مطلوب
            'last_name' => 'required', // الاسم الأخير مطلوب
            'serial_number' => 'nullable|digits:7|unique:users,stdsn', // الرقم التسلسلي اختياري ويجب أن يكون 7 أرقام فريدة
            'spec' => [new Enum(Specialization::class)], // التحقق من صحة التخصص
            'email' => 'required|email|unique:users,email', // البريد الإلكتروني يجب أن يكون فريد وصحيح
            'password' => 'min:8|same:confirm-password', // كلمة المرور مطلوبة وتأكيدها يجب أن يتطابق
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'first_name' => ucwords(strtolower($request->first_name)), // تحويل أول حرف كبير
            'last_name' => ucwords(strtolower($request->last_name)),
            'stdsn' => $request->serial_number,
            'spec' => $request->spec,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password), // تشفير الباسورد
            'avatar' => 'default.jpg' // الصورة الافتراضية
        ]);

        // إذا تم تحديد أدوار، يتم تعيينها للمستخدم
        $user->assignRole($request->roles);

        // إعادة توجيه مع رسالة نجاح
        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * عرض تفاصيل مستخدم محدد
     */
    public function show($id)
    {
        $user = User::find($id); // جلب المستخدم حسب الـ id

        // جلب بيانات GitHub إذا كان مرتبطًا
        if ($user->github_id) {
            try {
                $git = Socialite::driver('github')->$user->github_token;
                     
                // $git = Socialite::driver('github')->userFromToken($user->github_token);
            } catch (Exception) {
                $git = null; // إذا فشل الاتصال، نعتبره غير متوفر
            }
        } else $git = null;

        // تمرير البيانات للواجهة
        return view('users.show', compact('user', 'git'));
    }

    /**
     * عرض نموذج تعديل مستخدم
     */
    public function edit($id)
    {
        $specs = Specialization::cases(); // جلب التخصصات
        $user = User::find($id); // جلب بيانات المستخدم
        $roles = Role::pluck('name', 'name')->all(); // جلب كل الأدوار
        $userRole = $user->roles->pluck('name', 'name')->all(); // جلب أدوار المستخدم الحالي

        return view('users.edit', compact('user', 'roles', 'userRole', 'specs'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, $id)
    {
        // التحقق من البيانات
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id, // يسمح بالبريد نفسه إذا كان للمستخدم نفسه
            'stdsn' => 'unique:users,stdsn,' . $id,
            'spec' => [new Enum(Specialization::class)],
            'password' => 'nullable|min:8|same:confirm-password', // تحديث الباسورد اختياري
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5048' // تحديث الصورة اختياري
        ]);

        $user = User::find($id);
        $input = $request->all();

        // تحديث بيانات المستخدم مع كلمة المرور إذا تم إدخالها
        if (!empty($input['password'])) {
            $user->update([
                'first_name' => ucwords(strtolower($request->first_name)),
                'last_name' => ucwords(strtolower($request->last_name)),
                'stdsn' => $request->serial_number,
                'spec' => $request->spec,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
            ]);
        } else {
            // تحديث البيانات بدون كلمة المرور
            $input = Arr::except($input, array('password'));
            $user->update([
                'first_name' => ucwords(strtolower($request->first_name)),
                'last_name' => ucwords(strtolower($request->last_name)),
                'stdsn' => $request->serial_number,
                'spec' => $request->spec,
                'email' => strtolower($request->email),
            ]);
        }

        // حذف الأدوار القديمة
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        // إعادة تعيين الأدوار الجديدة
        $user->assignRole($request->roles);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        User::find($id)->delete(); // حذف المستخدم

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
