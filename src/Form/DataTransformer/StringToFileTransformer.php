<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
    private $filePrefix;

    public function __construct(string $filePrefix)
    {
        $this->filePrefix = $filePrefix;
    }

    public function transform($value)
    {
        // Transform the file path to a relative path
        if ($value instanceof File) {
            return $value->getFilename();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        // Convert the file path string to a File object
        if ($value) {
            return new File($this->filePrefix . $value);
        }

        return null;
    }
}
