<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThankYou;
use Illuminate\Http\Request;
class ThankYouController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $thankYou = ThankYou::all();
        return response()->json(['thankYou'=>$thankYou],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $thankYou = ThankYou::create($request->all());
        return response()->json(['thankYou'=>$thankYou],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ThankYou $thankYou)
    {
        $thankYou = ThankYou::find($thankYou);
        if(!$thankYou){
            return response()->json(['message'=>'Referral not found'],404);
        }
        return response()->json(['thankYou'=>$thankYou],200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThankYou $thankYou)
    {
        $thankYou->update($request->all());
        return response()->json(['thankYou'=>$thankYou],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThankYou $thankYou)
    {
        $thankYou->delete();
        return response()->json(['message'=>'Thank You Request deleted successfully'],200);
    }
}
