<?php

namespace App\Enums;

enum ProjectState: string
{
    case Proposition = 'اقتراح';
    case Approving = 'في انتظار الموافقة';
    case Rejected = 'مرفوض';
    case Incomplete = 'قيد التطوير / غير مكتمل';
    case Evaluating = 'قيد التقييم';
    case Complete = 'مكتمل';
}

