<?php

namespace App\Enums;

enum GroupState: string
{
    case Recruiting = 'يجري البحث عن أعضاء';
    case Invites = 'بالدعوات فقط';
    case Full = 'مكتمل ';
}
