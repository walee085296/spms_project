<?php

namespace App\Enums;

enum projectSup: string
{
    case  a ='عاطف رسلان';
    case b = 'سهام محسن';
    case c = 'حاتم محمود';
    case d = 'باسم ابانوب';
   
        public function specialization(): Specialization
    {
        return match($this) {
            self::a => Specialization::b,
            self::b => Specialization::d,
            self::c=> Specialization::e,
            self::d => Specialization::c,
        };
    }

}
    // 'sup' => ProjectSup::tryFrom($request->sup)?->value ?? ProjectSup::DEFAULT->value,
