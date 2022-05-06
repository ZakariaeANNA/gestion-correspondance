<?php

namespace App\Repositories;

use App\Interfaces\FeedbackRepositoryInterface;
use App\Models\FeedBack;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use App\Jobs\ProcessFeedback;
use App\Models\IncomingEmail;


class FeedbackRepository implements FeedbackRepositoryInterface 
{
    public function create($data){
        error_log('create function works but not inserting');
        try{
            $feedback = FeedBack::create([
                'idReceiver'=>$data['idReceiver'],
                'mail_id'=>$data['mail_id'],
                'idSender'=>$data['idSender'],
                'message'=>$data['message']??NULL,
                'isConfirmation'=>$data['isConfirmation']??0
            ]);
            if(!empty($data['file'])){
                error_log('even thought the data is not empty');
                foreach($data['file'] as $file){
                    $attachement = $file->store('feedback');
                    $feedback->Attachement()->create([
                        "attachement" => $attachement,
                        "filename" => $file->getClientOriginalName(),
                        "type" => $file->getClientOriginalExtension()
                    ]);
                }
            }
        }catch(QueryException $e){
            return response(["create_feedback/error_input"],500);
        }
        $feedback = $feedback->fresh();
        return response([$feedback],200);
    }
    public function getFeedbackByCorrespondence($mail_id){
        return FeedBack::where('mail_id','=',$mail_id)->get();
    }
    public function sent($mail_id,$idSender){
        return FeedBack::where([['mail_id','=',$mail_id],['idReceiver','=',$idSender]])->get();
    }
    public function received($mail_id,$idReceiver){
        return FeedBack::where([['mail_id','=',$mail_id],['idSender','=',$idReceiver]])->get();
    }
    public function updateFeedBackStatus($idReceiver,$mail_id){
        return FeedBack::where([['idReceiver','=',$idReceiver],["mail_id","=",$mail_id],["status","=",0]])->update(["status"=>1]);
    }
    public function getFeedbackByMailAndBySenderAndByReceiver($mail_id,$idReceiver,$idSender){
        return FeedBack::where([['mail_id','=',$mail_id],['idSender','=',$idSender],['idReceiver','=',$idReceiver]])->orWhere([['mail_id','=',$mail_id],['idSender','=',$idReceiver],['idReceiver','=',$idSender]])->get();
    }
}