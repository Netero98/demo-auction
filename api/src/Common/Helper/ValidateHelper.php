<?php

declare(strict_types=1);

namespace App\Common\Helper;

final class ValidateHelper
{
    /**
     * @param array<string> $requiredFields
     */
    public function getNotProvidedFields(array $body, array $requiredFields): array
    {
        $notProvidedFields = [];

        foreach ($requiredFields as $field) {
            if (empty($body[$field])) {
                $notProvidedFields[] = $field;
            }
        }

        return $notProvidedFields;
    }
}
