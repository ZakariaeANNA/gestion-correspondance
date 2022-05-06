<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\FeedBack;

class ProcessFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $feedback;
    protected $data;

    public function __construct($data,$feedback)
    {
        $this->data = $data;
        $this->feedback = $feedback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        error_log("handle method in processfeedback");
        error_log(print_r($this->data,true));
        error_log($this->feedback);
        // foreach($this->data['file'] as $file){
        //     $attachement = $file->store('feedback');
        //     $feedback->Attachement()->create([
        //         "attachement" => $attachement,
        //         "filename" => $file->getClientOriginalName(),
        //         "type" => $file->getClientOriginalExtension()
        //     ]);
        // }
    }
}
