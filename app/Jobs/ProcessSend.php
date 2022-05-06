<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\IncomingEmail;


class ProcessSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $mail;
    protected $codegresa;
    protected $department;
    protected $departementRoles;

    public function __construct($codegresa,$department,$mail,$departementRoles)
    {
        $this->mail = $mail;
        $this->department = $department;
        $this->codegresa = $codegresa;
        $this->departementRoles = $departementRoles;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!empty($this->codegresa)){
            $i = 0;
            $ecoles = array("primaire", "college", "lycee");
            if($this->codegresa == "all"){
                $user = User::where([['codegresa','!=','NULL'],['doti','!=',$this->mail->sender]])->pluck('doti')->toArray();
            }else if(in_array($this->codegresa,$ecoles)){
                $user = User::whereRelation('Etablissement', 'type', '=', $this->codegresa)->where(['doti','!=',$this->mail->sender])->pluck("doti");
            }else{
                $user = User::where([['codegresa','=',$this->codegresa],['doti','!=',$this->mail->sender]])->pluck('doti')->toArray();
            }
            foreach($user as &$usr){
                $usersEta[$i] = ["idReceiver"=>$usr];
                $i=$i+1;
            }
            $this->mail->IncomingEmail()->createMany($usersEta);
        }
        if(!empty($this->department)){
            if($this->department=="all"){
                if($this->departementRoles == "tous")
                    $userdoti = User::where([['idDepartement','!=','NULL'],['doti','!=',$this->mail->sender]])->pluck('doti')->toArray();
                else
                    $userdoti = User::where([['idDepartement','!=','NULL'],['doti','!=',$this->mail->sender],["roles","=",$this->departementRoles]])->pluck('doti')->toArray();
            }else{
                if($this->departementRoles == "tous")
                    $userdoti = User::where([['idDepartement','=',$this->department],['doti','!=',$this->mail->sender]])->pluck('doti')->toArray();
                else
                    $userdoti = User::where([['idDepartement','=',$this->department],['doti','!=',$this->mail->sender],["roles","=",$this->departementRoles]])->pluck('doti')->toArray();
            }
            $i = 0;
            foreach($userdoti as &$usr){
                $usersDep[$i] = ["idReceiver"=>$usr];
                $i=$i+1;
            }
            $this->mail->IncomingEmail()->createMany($usersDep);
        }
    }
}
