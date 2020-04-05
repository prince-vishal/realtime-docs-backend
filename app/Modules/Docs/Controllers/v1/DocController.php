<?php

namespace App\Modules\Docs\Controllers\v1;

use App\Events\NewViewer;
use App\Http\Controllers\Controller;
use App\Modules\Docs\Models\Doc;
use App\Modules\Docs\Requests\CreateDocRequest;
use App\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;

class DocController extends Controller
{

    /**
     * List all docs created by currently authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allDocs()
    {
        $docs = Doc::where(['owner_id' => Auth::id()])->orderBy('updated_at', 'desc')->get();
        return (new SuccessResponse($docs))->send();
    }

    /**
     * Show a doc by its id
     *
     * @param Doc $doc
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Doc $doc)
    {
        if (Auth::id() != $doc->owner_id) {
            broadcast(new NewViewer($doc, Auth::user()))->toOthers();
        }
        return (new SuccessResponse($doc))->send();
    }

    public function showViewers(Doc $doc)
    {
        return (new SuccessResponse($doc->viewers))->send();
    }


    /**
     * List all docs viewed by currently authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewedDocs()
    {
        $user = Auth::user();
        return (new SuccessResponse($user->viewedDocs))->send();
    }

    /**
     * Create a new doc for currently authenticated user
     *
     * @param CreateDocRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateDocRequest $request)
    {

        $request = $request->validated();


        if (isset($request['data'])) {
            $request['data'] = json_encode($request['data']);
        }
        if (isset($request['metadata'])) {
            $request['metadata'] = json_encode($request['metadata']);
        }

        $user = Auth::user();
        $request['owner_id'] = $user->id;
        $doc = Doc::create($request);

        return (new SuccessResponse($doc))->send();
    }

}
