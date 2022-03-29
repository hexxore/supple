<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Resources;

enum Gender: string{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';
}