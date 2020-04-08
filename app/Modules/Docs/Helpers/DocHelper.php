<?php

namespace App\Modules\Docs\Helpers;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Docs\Models\Doc;
use App\Modules\Docs\Models\DocUser;
use App\Responses\FailedResponse;
use App\Responses\ForbiddenResponse;
use App\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;

class DocHelper
{
    /**
     * Share this doc to an user
     *
     * @param $doc
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareDocToUser(Doc $doc, $request)
    {


        if (Auth::id() !== $doc->owner_id) {
            return (new ForbiddenResponse("You dont have permissions to share this document"))->send();

        }

        $accessRole = $request['accessRole'];
        $role = Role::where("name", $accessRole)->first();

        if (!$role) {
            return (new FailedResponse("No such role exists"))->send();
        }

        $invalidEmails = [];
        $sharedTo = [];
        $sharingToEmails = $request['sharingTo'];
        foreach ($sharingToEmails as $email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $invalidEmails[] = $email;
                continue;
            }
            if ($user->id == $doc->owner_id) {
                continue;
            }

            $sharedDocUser = new DocUser();
            $existingDocUser = DocUser::where([
                "user_id" => $user->id,
                "doc_id" => $doc->id,
            ])->first();
            // Update role if it already exists
            if ($existingDocUser) {
                $sharedDocUser = $existingDocUser;
            }

            $sharedDocUser->doc_id = $doc->id;
            $sharedDocUser->role_id = $role->id;
            $sharedDocUser->user_id = $user->id;
            $sharedDocUser->save();
            $sharedTo[] = $email;
        }

        return (new SuccessResponse([
            "shared" => true,
            "not_shared_to" => $invalidEmails,
            "shared_to" => $sharedTo
        ]))->send();
    }


    /**
     * Check if the authenticated user is authorized to view this doc
     *
     * @param Doc $doc
     *
     * @return bool
     */
    public function isAuthorized(Doc $doc)
    {
        if ($doc->owner_id == Auth::id()) {
            return true;
        }
        $editRole = Role::where('name', 'edit')->first();
        $viewRole = Role::where('name', 'view')->first();

        $docUser = DocUser::whereIn(
            "role_id", [$editRole->id, $viewRole->id]
        )->where(
            [
                "doc_id" => $doc->id,
                "user_id" => Auth::id()
            ]
        )->first();
        if ($docUser) {
            return true;
        }
        return false;
    }

}
