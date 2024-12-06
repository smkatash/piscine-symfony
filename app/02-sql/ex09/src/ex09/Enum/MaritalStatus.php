<?php 

namespace App\ex09\Enum;

enum MaritalStatus: string
{
    case SINGLE = 'single';
    case MARRIED = 'married';
    case WIDOWER = 'widower';
    case NA = 'na';
}


?>