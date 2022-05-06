<?php

namespace App\Repositories;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Models\Departement;
use Illuminate\Http\Response;
use App\Http\Resources\DepartmentResource;


class DepartmentRepository implements DepartmentRepositoryInterface 
{
    public function create($data) 
    {
        $department = Departement::create([
            'nomar' => $data['nomar'],
            'nomla' => $data['nomla'],
            'delegation' => $data['delegation'],
            'type' =>$data['type'],
        ]);

        return response();
    }

    public function getAll()
    {
        return DepartmentResource::collection(Departement::all());
    }

}