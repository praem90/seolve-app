<?php

namespace App\Http\Controllers;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use App\Models\PostAccount;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('accounts');

        $posts->when(request('query'), function ($q) {
            $q->where('message', 'like', '%' . request('query') . '%');
        });

        return $posts->paginate(request('limit', 25));
    }


    public function post(Request $request, $company_id)
    {
        $validated = $this->validate($request, [
            'accounts' => ['required', 'array'],
            'accounts.*' => ['required', 'integer'],
            'message' => ['required'],
            'attachements' => ['array'],
            'scheduled_at' => ['date'],
        ]);

        $post = new Post();

        $post->user_id = auth()->id();
        $post->company_id = $company_id;
        $post->accounts = $validated['accounts'];
        $post->message = $validated['message'];

        if (request()->has('scheduled_at')) {
            $post->scheduled_at = $validated['scheduled_at'];
        }

        // TODO: attachements
        $post->save();

		$postAccounts = [];
		foreach ($validated['accounts'] as $account_id) {
			$postAccounts[] = new PostAccount([
				'company_account_id' => $account_id
			]);
		}

		$post->postAccounts()->saveMany($postAccounts);

        $job = PublishPostJob::dispatch($post);

        if ($post->scheduled_at) {
            $job->delay($post->scheduled_at);
        }

        return response()->json([
            'message' => 'Posed Successfully',
            'post' => $post
        ]);
    }
}
