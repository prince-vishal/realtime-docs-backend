<?php

namespace App\Modules\Docs\Controllers\v1;

use App\Events\NewViewer;
use App\Http\Controllers\Controller;
use App\Modules\Docs\Models\Doc;
use App\Modules\Docs\Requests\CreateDocRequest;
use App\Responses\SuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocController extends Controller
{

    public function allDocs()
    {
        $docs = Doc::where(['owner_id' => Auth::id()])->get();
        return (new SuccessResponse($docs))->send();
    }

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
