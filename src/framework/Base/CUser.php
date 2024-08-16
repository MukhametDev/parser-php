<?php

namespace Framework;

use Framework\Models\Model;
use Framework\Enums\CUserEnums as Role;
use Framework\Repository\UserRepository;

class CUser extends Model
{
    protected static string $repository = UserRepository::class;
    protected array $fillable = ["username", "email", "password", "role_id"];

    protected function insertDataArray(): array
    {
        $filteredData = [];

        foreach ($this->fillable as $field) {
            if (isset($this->data[$field])) {
                $filteredData[$field] = $this->data[$field];
            }
        }

        return $filteredData;
    }

    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    protected function getByEmail(string $email)
    {

    }
}
