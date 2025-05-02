<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $referral = Referral::all();
        return response()->json(['referral'=>$referral],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $referral = Referral::create($request->all());
        return response()->json(['referral'=>$referral],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Referral $referral)
    {
        $referral = Referral::find($referral);
        if(!$referral){
            return response()->json(['message'=>'Referral not found'],404);
        }
        return response()->json(['referral'=>$referral],200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Referral $referral)
    {
        $referral->update($request->all());
        return response()->json(['referral'=>$referral],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Referral $referral)
    {
        $referral->delete();
        return response()->json(['message'=>'Referral deleted successfully'],200);
    }
}
