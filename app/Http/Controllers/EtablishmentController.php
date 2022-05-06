<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\EtablishmentRepositoryInterface;
use Illuminate\Support\Facades\Validator;


class EtablishmentController extends Controller
{


    private EtablishmentRepositoryInterface $etablishementRepository;

    public function __construct(EtablishmentRepositoryInterface $etablishmentRepository) 
    {
        $this->etablishementRepository = $etablishmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->etablishementRepository->index();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(
                'codegresa',
                'nomar',
                'nomla',
                'delegation',
                'type');
        $validator  = Validator::make($data,[
            'codegresa'=>'required|string',
            'nomar'=>'required|string',
            'nomla'=>'required|string',
            'delegation'=>'required|string',
            'type'=>'required|string',
        ]);
        if($validator->fails()){
            return response("Etablishment/fields_required",500);
        }

        return $this->etablishementRepository->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
