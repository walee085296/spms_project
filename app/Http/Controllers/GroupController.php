<?php

namespace App\Http\Controllers; // تحديد الـ namespace الخاص بالـ Controller

use Exception; // استدعاء كلاس Exception لمعالجة الأخطاء
use App\Models\User; // استدعاء موديل المستخدم
use App\Models\Group; // استدعاء موديل الجروب
use App\Enums\GroupState; // استدعاء Enum حالات الجروب
use App\Enums\ProjectType; // استدعاء Enum أنواع المشاريع
use App\Models\GroupRequest; // استدعاء موديل طلبات الانضمام للجروب
use Illuminate\Http\Request; // استدعاء Request للتعامل مع بيانات الطلب
use App\Enums\Specialization; // استدعاء Enum التخصصات
use App\Http\Controllers\Controller; // استدعاء الكلاس الأب Controller
use Illuminate\Support\Facades\Auth; // استدعاء Facade الـ Auth
use Illuminate\Validation\Rules\Enum; // استدعاء Rule للتحقق من الـ Enum
use Laravel\Socialite\Facades\Socialite; // استدعاء Socialite للتعامل مع GitHub

class GroupController extends Controller
{
    // Constructor للتحكم بالـ middleware
    function __construct()
    {
        // Middleware للتحقق من صلاحية عرض الجروبات (index, show)
        $this->middleware('permission:group-list', ['only' => ['index', 'show']]);
    }

    // عرض قائمة الجروبات
    public function index(Request $request)
    {
        $user = $request->user(); // جلب المستخدم الحالي
        // جلب الجروبات مع المشاريع والمطورين
        $groups = Group::with('project','developers')
            ->filter(request(['search'])) // دعم الفلترة بالبحث
            ->latest() // ترتيب حسب الأحدث
            ->paginate(15) // تقسيم النتائج على صفحات
            ->withQueryString(); // الاحتفاظ بقيم البحث في روابط الصفحات

        // عرض الصفحة وتمرير البيانات
        return view('groups.index', compact('groups'))
            ->with('i', (request()->input('page', 1) - 1) * 5); // حساب رقم الصف لكل صفحة
    }

    // عرض نموذج إنشاء جروب جديد
    public function create(Request $request)
    {
        $this->authorize('create', Group::class); // التحقق من صلاحية المستخدم
        $states = GroupState::cases(); // جلب كل الحالات الممكنة للجروب
        $specs = Specialization::cases(); // جلب كل التخصصات
        $project_types = ProjectType::cases(); // جلب كل أنواع المشاريع
        $users = User::role('student')->except(request()->user())->get(); // جلب كل الطلاب باستثناء المستخدم الحالي

        // عرض صفحة إنشاء الجروب مع تمرير البيانات
        return view('groups.create', compact('specs', 'users', 'states', 'project_types'));
    }

    // حفظ جروب جديد في قاعدة البيانات
    public function store(Request $request)
    {
        $this->authorize('create', Group::class); // التحقق من صلاحية المستخدم
        // إذا لم يكن لدى المستخدم تخصص محدد
        if (request()->user()->spec === Specialization::None) {
            return redirect()->back()->with('error', 'Request a specialization before creating a group!');
        }

        // تحقق من صحة المدخلات باستخدام Enum
        $this->validate($request, [
            'state' => [new Enum(GroupState::class)], // تحقق من حالة الجروب
            'spec' => [new Enum(Specialization::class)], // تحقق من التخصص
            'project_type' => [new Enum(ProjectType::class)], // تحقق من نوع المشروع
        ]);

        // التحقق من أن التخصص الذي يريد إنشاء الجروب به يطابق تخصص المستخدم
        if (Specialization::from(request()->spec) !== Specialization::None) {
            if (Specialization::from(request()->spec)->name !== request()->user()->spec->name) {
                return redirect()->back()->withError('Cannot create a group of specialization ' . $request->spec . '!')->withInput();
            }
        }

        // إنشاء الجروب في قاعدة البيانات
        $group = Group::create([
            'state' => $request->state,
            'spec' => $request->spec,
            'project_type' => $request->project_type,
        ]);

        // إضافة المستخدم الحالي إلى الجروب كمطور
        $request->user()->groups()->attach($group);

        // إعادة التوجيه بعد نجاح الإنشاء مع رسالة نجاح
        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    // عرض صفحة جروب محدد
    public function show(Group $group)
    {
        // جلب جميع طلبات الانضمام المعلقة لهذا الجروب
        $groupRequests = GroupRequest::where('group_id', $group->id)
            ->where('status', 'pending')->get();

        // التحقق إذا كان المستخدم الحالي أرسل طلب انضمام
        $requested = $groupRequests->where('sender_id', Auth::id());

        // عرض صفحة تفاصيل الجروب
        return view('groups.show', compact('group', 'groupRequests', 'requested'));
    }

    // عرض نموذج تعديل الجروب
    public function edit(Group $group)
    {
        $this->authorize('edit', $group); // التحقق من صلاحية التعديل
        $states = GroupState::cases(); // جلب كل حالات الجروب
        $specs = Specialization::cases(); // جلب كل التخصصات
        $users = User::role('student')->get(); // جلب كل الطلاب
        $project_types = ProjectType::cases(); // جلب كل أنواع المشاريع

        // عرض صفحة تعديل الجروب
        return view('groups.edit', compact('group', 'users', 'states', 'specs', 'project_types'));
    }

    // تحديث بيانات الجروب
    public function update(Request $request, Group $group)
    {
        $this->authorize('edit', $group); // التحقق من صلاحية المستخدم
        $this->validate($request, [
            'state' => [new Enum(GroupState::class)], // تحقق من صحة الحالة
            'spec' => [new Enum(Specialization::class)], // تحقق من صحة التخصص
            'project_type' => [new Enum(ProjectType::class)], // تحقق من صحة نوع المشروع
        ]);

        // التأكد من أن المستخدم لا ينشئ أو يغير الجروب لتخصص مختلف
        if (Specialization::from(request()->spec) !== Specialization::None) {
            if (Specialization::from(request()->spec)->name !== request()->user()->spec->name) {
                return redirect()->back()->withError('Cannot create a group of specialization ' . $request->spec . '!')->withInput();
            }
        }

        // تحديث بيانات الجروب
        $group->update($request->all());

        // إعادة التوجيه بعد النجاح
        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully');
    }

    // حذف جروب
    public function destroy(Group $group)
    {
        $this->authorize('destroy', $group); // التحقق من صلاحية الحذف
        $group->delete(); // حذف الجروب
        return redirect()->route('groups.index')
            ->with('success', 'group deleted successfully');
    }

    // مغادرة الجروب من قبل المستخدم
    public function leaveGroup($id)
    {
        $group = Group::find($id); // جلب الجروب حسب الـ id
        if (count($group->developers) == 1) { // إذا كان آخر عضو
            $group->delete(); // حذف الجروب بالكامل
        }
        // فصل المستخدم من الجروب
        $group->developers()->detach(auth()->user());
        return redirect()->route('groups.index')->with('success', 'Left group successfully');
    }
}

