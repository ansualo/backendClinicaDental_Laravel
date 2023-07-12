<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TreatmentController extends Controller
{
    public function getAllTreatments()
    {
        try {
            $treatments = Treatment::get();

            return response()->json([
                'message' => 'Treatments retrieved',
                'data' => $treatments
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting treatments' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving treatments',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createTreatment(Request $request)
    {
        try {
           $validator = Validator::make($request->all(),[
                'name'=> 'required|string',
                'price'=> 'required|integer'
           ]);
            
            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            };

            $validData = $validator->validated();

            $treatment = Treatment::create([
                'name' => $validData['name'],
                'price' => $validData['price'],
            ]);

            return response()->json([
                'message' => 'Treatment created succesfully',
                'data' => $treatment
            ], Response::HTTP_OK);


        } catch (\Throwable $th) {
            Log::error('Error creating treatment' . $th->getMessage());

            return response()->json([
                'message' => 'Error creating treatment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTreatment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'=>'required',
                'name'=> 'string',
                'price'=> 'integer'
            ]);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $treatment = Treatment::find($validData['id']);

            if(!$treatment){
                return response()->jason([
                    'message' => 'Treatment cannot be found'
                ], Response::HTTP_OK);
            }

            if(isset($validData['name'])){
                $treatment->name = $validData['name'];
            }

            if(isset($validData['price'])){
                $treatment->price = $validData['price'];
            }

            $treatment->save();

            return response()->json([
                'message' => 'Treatment updated',
                'data' => $treatment
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error updating treatment' . $th->getMessage());

            return response()->json([
                'message' => 'Error updating treatment'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTreatment($id)
    {
        try {
            Treatment::destroy($id);

            return response()->json([
                'message' => 'Treatment deleted succesfully',
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error deleting treatment' . $th->getMessage());

            return response()->json([
                'message' => 'Error deleting treatment'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
