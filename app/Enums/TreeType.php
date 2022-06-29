<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static RealTree()
 * @method static static BinaryTree()
 */
final class TreeType extends Enum
{
    const RealTree = 1;
    const BinaryTree = 2;
}
