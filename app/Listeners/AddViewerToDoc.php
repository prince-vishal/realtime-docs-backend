<?php

namespace App\Listeners;

use App\Events\NewViewer;
use App\Modules\Docs\Models\DocViewer;

class AddViewerToDoc
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NewViewer $event
     *
     * @return void
     */
    public function handle(NewViewer $event)
    {

        $doc = $event->doc;
        $viewer = $event->viewer;

        $data = [
            "doc_id" => $doc->id,
            'user_id' => $viewer->id
        ];

        $alreadyViewed = DocViewer::where($data)->first();

        if ($alreadyViewed) {
            // If already viewed, update timestamps
            $alreadyViewed->touch();
        } else {
            DocViewer::create($data);
        }

    }
}
