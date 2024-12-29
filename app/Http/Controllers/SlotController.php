<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlotRequest;
use App\Http\Resources\SlotResource;
use App\Models\Slot;
use App\Traits\ResponseTrait;

class SlotController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $slots = Slot::paginate(10);
        return self::successResponsePaginate(data: SlotResource::collection($slots)->response()->getData(true));
    }

    public function store(SlotRequest $request)
    {
        Slot::create($request->validated());
        return self::successResponse('created');
    }

    public function update(SlotRequest $request, Slot $slot)
    {
        $slot->update($request->only('is_available'));

        return self::successResponse('updated');
    }
}
