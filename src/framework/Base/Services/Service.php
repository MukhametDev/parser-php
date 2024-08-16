<?php

namespace Framework\Services;

use Framework\Repository\Repository;
use Framework\Validators\Validator;

class Service
{
    protected Repository $repository;
    protected Validator $validator;

    public function __construct(Repository $repository, Validator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create()
    {
        
    }
}
