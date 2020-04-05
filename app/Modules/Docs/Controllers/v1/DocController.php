<?php

namespace App\Modules\Docs\Controllers\v1;

use App\Events\NewViewer;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\Role;
use App\Modules\Docs\Helpers\DocHelper;
use App\Modules\Docs\Models\Doc;
use App\Modules\Docs\Models\DocUser;
use App\Modules\Docs\Requests\ShareDocRequest;
use App\Responses\FailedResponse;
use App\Responses\ForbiddenResponse;
use App\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;

class DocController extends Controller
{

    private $docHelper;

    public function __construct(DocHelper $docHelper)
    {
        $this->docHelper = $docHelper;
    }

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
        if ($doc->owner_id == Auth::id() || $this->isAuthorized($doc)) {
            if (Auth::id() != $doc->owner_id) {
                broadcast(new NewViewer($doc, Auth::user()))->toOthers();
            }

            return (new SuccessResponse($doc))->send();

        }
        return (new ForbiddenResponse("You dont have permissions to view this document"))->send();
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
     * @param ShareDocRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ShareDocRequest $request)
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

    /**
     * Share a doc to user, if current authenticated user is the owner of this doc
     *
     * @param ShareDocRequest $request
     *
     * @param Doc             $doc
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRolesToUserForADoc(ShareDocRequest $request, Doc $doc)
    {

        if (!$doc) {
            return (new FailedResponse("No such Document exists"))->send();
        }

        $request = $request->validated();
        return $this->docHelper->shareDocToUser($doc, $request);

    }

    /**
     * @param Doc $doc
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfAuthorized(Doc $doc)
    {

        if ($this->docHelper->isAuthorized($doc)) {
            return (new SuccessResponse(["authorized" => true]))->send();;
        }

        return (new ForbiddenResponse("Unauthorized"))->send();
    }

}
