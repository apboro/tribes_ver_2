<?php

namespace App\Http\Controllers\APIv3\Subscription;

use App\Domain\Entity\User\Service\SubscriptionService;
use App\Http\ApiRequests\ApiAssignSubscriptionRequest;
use App\Http\ApiRequests\Subscription\ApiEditSubscriptionRequest;
use App\Http\ApiRequests\Subscription\ApiListSubscriptionsRequest;
use App\Http\ApiRequests\Subscription\ApiShowSubscriptionRequest;
use App\Http\ApiRequests\Subscription\ApiStoreSubscriptionRequest;
use App\Http\ApiRequests\Subscription\ApiUpdateSubscriptionRequest;
use App\Http\ApiResources\SubscriptionResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseList;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Subscription\SubscriptionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiSubscriptionController extends Controller
{

    private SubscriptionRepository $subscriptionRepository;
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionRepository $subscriptionRepository, SubscriptionService $subscriptionService)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionService = $subscriptionService;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index(ApiListSubscriptionsRequest $request): ApiResponseList
    {
        $subscriptions = Subscription::all()->sortBy('id');
        return ApiResponse::list()->items($subscriptions);
    }

    public function check(Response $response): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $expiredStatus = $this->subscriptionService->isExpiredDate($user);

        return new JsonResponse([
            'expired_status' => $expiredStatus
        ], Response::HTTP_OK);
    }

    public function userShowSubscription()
    {
        /** @var User $user */
        $user = Auth::user();

        $subscription = $user->subscription ?? null;

        return new JsonResponse(['subscription' => $subscription], Response::HTTP_OK);
    }

    /**  Show subscription information*/
    public function show(ApiShowSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionRepository->findSubscriptionBySlug($request);

        return ApiResponse::common(SubscriptionResource::make($subscription)->toArray($request));
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
     * @param  \App\Http\ApiRequests\Subscription\ApiStoreSubscriptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreSubscriptionRequest $request)
    {
        //
    }

    public function edit(ApiEditSubscriptionRequest $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\ApiRequests\Subscription\ApiUpdateSubscriptionRequest  $request
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
