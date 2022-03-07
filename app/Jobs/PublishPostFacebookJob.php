<?php

namespace App\Jobs;

use App\Models\CompanyAccount;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PublishPostFacebookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->post->accounts as $account_id) {
            $account = CompanyAccount::find($account_id);

            $data = [
                'message' => $this->post->message,
                'access_token' => $account->access_token,
            ];

            if (app()->environment('local')) {
                $data['published'] = 'false';
            }

            $response = Http::post('https://graph.facebook.com/' . $account->account_id . '/feed', $data);

            $this->post->response = $response->json();
            $this->post->save();
        }
    }
}
