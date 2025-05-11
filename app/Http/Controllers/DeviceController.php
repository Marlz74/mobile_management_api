<?php
namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\LockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|unique:devices',
            'user_name' => 'required|string',
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors());
        }

        $device = Device::create([
            'device_id' => $request->device_id,
            'user_name' => $request->user_name,
            'location' => $request->location,
            'status' => 'unlocked',
        ]);

        return ApiResponse::success(new DeviceResource($device), 'Device registered successfully', 201);
    }

    public function lock(Request $request, $device_id)
    {
        $device = Device::where('device_id', $device_id)->first();

        if (!$device) {
            return ApiResponse::error('Device not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors());
        }

        // Simulate external API call to lock device
        $response = Http::post('https://jsonplaceholder.typicode.com/posts  ', [
            'device_id' => $device_id,
        ]);

        if ($response->successful()) {
            $device->update(['status' => 'locked']);
            LockHistory::create([
                'device_id' => $device_id,
                'action' => 'lock',
                'reason' => $request->reason,
                'action_at' => now(),
            ]);

            return ApiResponse::success(new DeviceResource($device), 'Device locked successfully');
        }

        return ApiResponse::error('Failed to lock device', 500);
    }

    public function unlock(Request $request, $device_id)
    {
        $device = Device::where('device_id', $device_id)->first();

        if (!$device) {
            return ApiResponse::error('Device not found', 404);
        }

        // Simulate external API call to unlock device
        $response = Http::post('https://jsonplaceholder.typicode.com/posts  ', [
            'device_id' => $device_id,
        ]);

     

        $device->update(['status' => 'unlocked']);
        LockHistory::create([
            'device_id' => $device_id,
            'action' => 'unlock',
            'performed_by' => auth()->user()->name ?? 'Admin',
            'action_at' => now(),
        ]);

        return ApiResponse::success(new DeviceResource($device), 'Device unlocked successfully');
    }
}