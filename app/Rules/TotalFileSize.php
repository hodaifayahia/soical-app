<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TotalFileSize implements Rule
{
    protected $maxTotalSize;

    public function __construct($maxTotalSize)
    {
        // Max total size in bytes (1 GB = 1073741824 bytes)
        $this->maxTotalSize = $maxTotalSize;
    }

    public function passes($attribute, $value)
    {
        $totalSize = 0;

        foreach ($value as $file) {
            $totalSize += $file->getSize();
        }

        return $totalSize <= $this->maxTotalSize;
    }

    public function message()
    {
        return 'The total size of all files should not exceed ' . ($this->maxTotalSize / 1048576) . ' MB.';
    }
}
