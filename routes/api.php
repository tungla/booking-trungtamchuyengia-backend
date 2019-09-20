<?php

use Illuminate\Http\Request;
use App\Booking;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('booking', function(Request $request) {
	$resquest_data = $request->all();
	$date = date('Y-m-d', strtotime($resquest_data['booking_time']));
	$same_date_booked_count = Booking::where('phone','=',$resquest_data['phone'])->whereRaw("DATE(booking_time) = '{$date}'")->count();
	// $same_date_booked_count = 0;
	if ($same_date_booked_count > 0) {
		return response()->json(['message' => 'Bạn đã đặt chỗ vào ngày này rồi'], 400);
	} else {
		return Booking::create($request->all());
	}
});

Route::post('booked', function(Request $request) {
	$resquest_data = $request->all();
	return Booking::where('phone','=',$resquest_data['phone'])->whereRaw("DATE(booking_time) >= CURDATE()")->get();
	// $same_date_booked_count = 0;
});

Route::post('booked-by-date', function(Request $request) {
	$resquest_data = $request->all();
	return Booking::whereRaw("DATE(booking_time) = '{$resquest_data['date']}'")->get();
	// $same_date_booked_count = 0;
});

Route::delete('booked/{id}', function($id) {
	$booked_time = Booking::find($id);
	$booked_time->delete();
});