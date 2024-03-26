<?php
// src/Doctrine/Types/RoleType.php

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class RoleType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('admin', 'etudiant', 'enseignant', 'responsable_societe')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value; // No conversion needed
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value; // No conversion needed
    }

    public function getName()
    {
        return 'role_enum';
    }
}
