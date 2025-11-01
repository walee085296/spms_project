<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role; // استدعاء نموذج Role لإدارة الأدوار
use Spatie\Permission\Models\Permission; // استدعاء نموذج Permission لإدارة الصلاحيات
use Illuminate\Support\Facades\DB; // لاستخدام عمليات قاعدة البيانات المباشرة

class RoleController extends Controller
{
    /**
     * إنشاء Middleware للتحكم في الصلاحيات
     */
    function __construct()
    {
        // السماح بالوصول للـ index و store فقط لمن لديهم أي صلاحية من هذه
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);

        // السماح بالوصول لإنشاء دور فقط لمن لديهم صلاحية create
        $this->middleware('permission:role-create', ['only' => ['create','store']]);

        // السماح بالوصول لتعديل دور فقط لمن لديهم صلاحية edit
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);

        // السماح بالوصول لحذف دور فقط لمن لديهم صلاحية delete
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * عرض قائمة الأدوار
     */
    public function index(Request $request)
    {
        // جلب الأدوار مع إمكانية البحث
        $roles = Role::where([
            ['name','!=',Null], // جلب الأدوار التي اسمها غير فارغ
            [function ($query) use ($request){
                if (($search = $request->search)){
                    // البحث داخل أسماء الأدوار
                    $query->orWhere('name' , 'LIKE' , '%' . $search .'%')->get();
                }
            }]
        ])
        ->orderBy('id','DESC') // ترتيب الأدوار من الأحدث
        ->paginate(15) // تقسيم الصفحات كل 15 دور
        ->withQueryString(); // الاحتفاظ بباراميترات البحث في الروابط

        // تمرير البيانات للواجهة
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5); // لحساب رقم الصف
    }

    /**
     * عرض نموذج إنشاء دور جديد
     */
    public function create()
    {
        $permissions = Permission::get(); // جلب كل الصلاحيات المتاحة
        return view('roles.create',compact('permissions')); // تمرير الصلاحيات للواجهة
    }

    /**
     * تخزين دور جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {   
        // التحقق من صحة البيانات
        $this->validate($request, [
            'name' => 'required|unique:roles,name', // اسم الدور مطلوب ويجب أن يكون فريد
            'permission' => 'required', // يجب اختيار صلاحيات للدور
        ]);

        // إنشاء الدور الجديد
        $role = Role::create(['name' => $request->input('name')]);

        // تعيين الصلاحيات للدور
        $role->syncPermissions($request->input('permission'));

        // إعادة توجيه مع رسالة نجاح
        return redirect()->route('roles.index')
                        ->with('success','تم إنشاء الدور بنجاح');
    }

    /**
     * عرض نموذج تعديل الدور
     */
    public function edit($id)
    {
        $role = Role::find($id); // جلب بيانات الدور
        $permissions = Permission::get(); // جلب كل الصلاحيات
        // جلب صلاحيات الدور الحالي
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        // تمرير البيانات للواجهة
        return view('roles.edit',compact('role','permissions','rolePermissions'));
    }

    /**
     * تحديث بيانات الدور
     */
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $this->validate($request, [
            'name' => 'required', // اسم الدور مطلوب
            'permission' => 'required' // يجب اختيار صلاحيات
        ]);

        $role = Role::find($id); // جلب الدور حسب id
        $role->name = $request->input('name'); // تحديث الاسم
        $role->save(); // حفظ التغييرات

        // تحديث الصلاحيات الجديدة
        $role->syncPermissions($request->input('permission'));

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('roles.index')
                        ->with('success','تم تحديث الدور بنجاح');
    }

    /**
     * حذف الدور
     */
    public function destroy($id)
    {
        $role = DB::table("roles")->where('id',$id); // البحث عن الدور حسب id
        $role->delete(); // حذف الدور

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('roles.index')
                        ->with('success','تم حذف الدور بنجاح');
    }
}
