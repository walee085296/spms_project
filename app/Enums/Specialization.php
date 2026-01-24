<?php

namespace App\Enums;

enum Specialization: string
{
    case None = 'غير محدد';
    case Software = 'تطبيقات الويب'; 
    case Communication = 'تطبيقات الهااتف';
    case AI = "واجهاات المسخدم";
    case Security = "ديسكتوب ";
}
