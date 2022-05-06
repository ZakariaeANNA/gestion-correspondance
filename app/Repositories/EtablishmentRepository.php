<?php

namespace App\Repositories;

use App\Interfaces\EtablishmentRepositoryInterface;
use App\Models\Etablissement;
use App\Resources\EtablissementResource;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;


class EtablishmentRepository implements EtablishmentRepositoryInterface 
{
    
    public function create($data) 
    {
        $etablissement = Etablissement::create([
            'codegresa'=>$data['codegresa'],
            'nomar'=>$data['nomar'],
            'nomla'=>$data['nomla'],
            'delegation'=>$data['delegation'],
            'type'=>$data['type'],
        ]);
        return response([]);
    }
    public function index(){
        $etablissement = Etablissement::all();
        return $etablissement;
    }
}
