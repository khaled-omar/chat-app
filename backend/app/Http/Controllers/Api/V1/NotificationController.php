<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\NotificationRequest as Request;
use App\Http\Resources\V1\NotificationResource;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $filter['notification']['notifiable_id'] = $user->id;
        $filter['notification']['notifiable_type'] = get_class($user);

        $items = $this->service->retrieveResource($filter);

        return response()->api(NotificationResource::collection($items));
    }

    /**
     * Mark notification as read by the employee.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        $this->service->markAsRead($notification);

        return response()->api();
    }

    /**
     *  Mark notification as unread by the employee.
     */
    public function markAsUnRead(Request $request, Notification $notification)
    {
        $this->service->markAsUnRead($notification);

        return response()->api();
    }

    /**
     *  Mark notification as unread by the employee.
     */
    public function bulkMarkAsUnRead(Request $request)
    {
        $data = $request->validated();
        $this->service->bulkMarkAsUnRead($data['ids']);

        return response()->api();
    }

    /**
     *  Mark notification as unread by the employee.
     */
    public function bulkMarkAsRead(Request $request)
    {
        $data = $request->validated();

        $this->service->bulkMarkAsRead($data['ids']);

        return response()->api();
    }

    /**
     *  Mark notification as unread by the employee.
     */
    public function destroy(Request $request, Notification $notification)
    {
        $this->service->deleteOneById($notification->id);

        return response()->api();
    }

    /**
     *  Mark notification as unread by the employee.
     */
    public function bulkDelete(Request $request)
    {
        $data = $request->validated();
        $this->service->bulkDelete($data['ids']);

        return response()->api();
    }
}
