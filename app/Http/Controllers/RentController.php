<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\RentService;
use Carbon\Carbon;

class RentController extends Controller
{
    protected $rentService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(RentService $rentService)
    {
        $this->rentService = $rentService;
    }

    public function index() {
        $rent = $this->rentService->getAll();
        return response()->json($rent);
    }

    public function show($id) {
        $rent = $this->rentService->getById($id);
        return response()->json($rent);
    }

    public function store(RentRequest $request) {


        $data = [
            'books' => $request['books'],
            'user_id' => (Auth::user()->role == 'super' && isset($request['user_id'])) ?  $request['user_id'] : Auth::user()->id,
            'rent_date' => Carbon::now()->format('Y-m-d'), // Use o formato 'Y-m-d' para datas
            'scheduled_return' => Carbon::now()->addWeek()->format('Y-m-d'),
            'status' => 'rented'
        ];
        
        $rent = $this->rentService->create($data);
        return response()->json($rent);
    }

    public function update(Request $request, $id) {

        $data = [
            'status' => $request['status']
        ];

        $rent = $this->rentService->update($data, $id);
        return response()->json($rent);
    }

    public function delete($id) {
        $rent = $this->rentService->delete($id);
        return response()->json(null);
    }
}