<?php

namespace App\Repositories;

use App\Interfaces\CorrespondenceRepositoryInterface;
use App\Models\Mail;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use App\Models\IncomingEmail;
use App\Http\Resources\CorrespondenceToExportResource;
use App\Http\Resources\ImportResource;
use App\Models\User;
use App\Jobs\ProcessSend;
use App\Http\Resources\IncomingResource;

class CorrespondenceRepository implements CorrespondenceRepositoryInterface 
{
    public function create($data,$receiver)
    {
        $mail = new Mail();
        if($file = $data['file']){
            try{
                $mail = Mail::create([
                    'title'=>$data['title'],
                    'message'=> $data['message'],
                    'sender'=>$data['sender'],
                    'attachement'=>$file->store('mail'),
                    'type'=>$file->getClientOriginalExtension(),
                    'fileName'=>$file->getClientOriginalName(),
                    'references'=>$data['references'],
                    'concerned'=>$data['concerned'],
                    'number'=>$data["number"],
                    'notes'=>$data['notes'],
                    'achevementdate'=>$data['dateachevement']
                ]);
                try{
                    if($receiver){
                        $query = $mail->IncomingEmail()->createMany($receiver);
                    }
                }catch(QueryException $e){
                    $mail->forceDelete();
                    return response("correspondence_add/user_not_found",500);
                }
                ProcessSend::dispatch($data['codegresa']??NULL,$data['department']??NULL,$mail,$data["depRoles"]??NULL);
            }catch(Exception $e){
                error_log($e->getMessage());
                $mail->delete();
                return response("correspondence_add/informations_incorrects",500);
            }
            return response([]);
        }
    }
    public function destroy($id){
        try{
            $correspondance = Mail::find($id);
            $correspondance->delete($id);
        }catch(QueryException $e){
            return response("correspondance_delete/notFound");
        }
    }
    public function getMailBySenderByDoti($doti){
        return CorrespondenceToExportResource::collection(Mail::with('IncomingEmail')->where('sender','=',$doti)->orderBy('created_at','desc')->paginate(5));
    }
    public function getMailByReceiverByDoti($doti){
        return ImportResource::collection(IncomingEmail::with('Mail')->where('idReceiver','=',$doti)->paginate(5));
    }
    public function confirmMailBySender($idReceiver,$mail_id,$state){
        return IncomingEmail::where([['mail_id','=',$mail_id],["idReceiver","=",$idReceiver]])->update(["senderConfirmation"=>$state]);
    }
    public function confirmMailByReceiver($idReceiver,$mail_id,$state){
        return IncomingEmail::where([['mail_id','=',$mail_id],['idReceiver','=',$idReceiver]])->update(["receiverConfirmation"=>$state]);
    }
    public function updateMailByStatus($mail_id){
        return IncomingEmail::where('mail_id','=',$mail_id)->update(["status"=>1]);
    }
    public function DeleteImportation($id){
        return IncomingEmail::where("mail_id","=",$id)->delete();
    }
    public function getReceiversbyMail($id){
        return IncomingResource::collection(IncomingEmail::where("mail_id","=",$id)->get());
    }
    public function getReceiverByMailIdAndDoti($id,$receiver){
        return IncomingResource::collection(IncomingEmail::where([['mail_id','=',$id],['idReceiver','=',$receiver]])->get());
    }
    public function checkIfUserInMail($id,$doti){
        if( Mail::where([["sender","=",$doti],["id","=",$id]])->orWhereRelation("IncomingEmail",[["idReceiver","=",$doti],["mail_id","=",$id]])->exists())
            return Response([],200);
        return Response([],404);
    }
}