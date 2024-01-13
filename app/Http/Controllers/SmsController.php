<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Helpers\ResponseHelper;
use App\Enums\HttpStatusCode;
use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use App\Exceptions\InvalidArgumentException;

class SmsController extends Controller
{
    public function __construct(
        protected SmsService $smsService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/send-sms",
     *     tags={"SMS"},
     *     summary="Send an SMS",
     *     description="Sends an SMS to a specified phone number",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\JsonContent(
     *             required={"phone_number","message"},
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="message", type="string", example="Your SMS message here")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SMS sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="SMS başarıyla gönderildi.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     * 
     * @param Request $request
     * 
     * @return mixed
     * 
     */
    public function send(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone_number' => 'required',
                'message' => 'required'
            ]);
            $user = $request->user(); 
            $sendSmsResponse = $this->smsService->sendSMS(
                $user->id,
                $request->phone_number,
                $request->message
            );

            return ResponseHelper::success($sendSmsResponse, 'SMS başarıyla gönderildi.', code: HttpStatusCode::OK->value);
        } catch (InvalidArgumentException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::BAD_REQUEST->value);
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/send-bulk-sms",
     *     summary="Bulk SMS send",
     *     description="Sends SMS messages to multiple recipients",
     *     operationId="sendBulk",
     *     tags={"SMS"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         description="List of messages to be sent",
     *         @OA\JsonContent(
     *             required={"messages"},
     *             @OA\Property(
     *                 property="messages",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="phone_number",
     *                         type="string",
     *                         example="1234567890"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Your SMS message here"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bulk SMS sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Bulk SMS başarıyla gönderildi."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     * 
     */
    public function sendBulk(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'messages' => 'required|array',
                'messages.*.phone_number' => 'required|string',
                'messages.*.message' => 'required|string',
            ]);
            $user = $request->user(); 
            $sendBulkSMSResponse = $this->smsService->sendBulkSMS(
                $user->id,
                $request->messages
            );

            return ResponseHelper::success($sendBulkSMSResponse, 'Bulk SMS başarıyla gönderildi.', code: HttpStatusCode::OK->value);
        } catch (InvalidArgumentException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::BAD_REQUEST->value);
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }

    /** 
     * @OA\Get(
     *     path="/api/sms-report",
     *     tags={"SMS"},
     *     summary="Get SMS Report",
     *     description="Returns SMS report for a given user, filtered by status, start date, end date, and message content.",
     *     operationId="getReport",
     * 
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="sent"
     *         ),
     *         description="The status of the SMS messages to filter by."
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-01-01"
     *         ),
     *         description="The start date for the report period."
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-06-01"
     *         ),
     *         description="The end date for the report period."
     *     ),
     *     @OA\Parameter(
     *         name="message",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="message"
     *         ),
     *         description="Part of the SMS message content to filter by."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     * 
     */
    public function getReport(Request $request): JsonResponse
    {
        try {
            $user = $request->user(); 
            $userId = $user['id'];
            $status = $request->query('status');
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $message = $request->query('message');
            $report = $this->smsService->getSmsReport($userId, $status, $startDate, $endDate, $message);

            return ResponseHelper::success($report, 'SMS Report');
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/sms-report-details",
     *     tags={"SMS"},
     *     summary="Get SMS Statistics",
     *     description="Returns statistical data for a user's SMS reports.",
     *     operationId="getSmsStatistics",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_sms", type="integer", example=100),
     *             @OA\Property(property="delivered_sms", type="integer", example=80),
     *             @OA\Property(property="failed_sms", type="integer", example=20),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     * 
     */
    public function getDetail(Request $request): JsonResponse
    {
        try {
            $user = $request->user(); 
            $statistics = $this->smsService->getDetail($user->id);

            return ResponseHelper::success($statistics, 'SMS Statistics');
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }
}
