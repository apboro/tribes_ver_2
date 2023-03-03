<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiListSubscriptionsRequest;
use App\Http\ApiRequests\ApiStoreSubscriptionRequest;
use App\Http\ApiRequests\ApiUpdateSubscriptionRequest;
use App\Http\ApiResources\SubscriptionCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseList;
use App\Http\Controllers\Controller;
use App\Models\Subscription;

class ApiSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(ApiListSubscriptionsRequest $request): ApiResponseList
    {
        $subscriptions = Subscription::all();
        return ApiResponse::list()->items(SubscriptionCollection::make($subscriptions)->toArray($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\ApiRequests\ApiStoreSubscriptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreSubscriptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\ApiRequests\ApiUpdateSubscriptionRequest  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateSubscriptionRequest $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
