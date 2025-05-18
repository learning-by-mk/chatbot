<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    /**
     * Khởi tạo giao dịch thanh toán PayPal
     */
    public function createPayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('payment.success'),
                "cancel_url" => route('payment.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => $request->price
                    ],
                    "description" => "Thanh toán cho đơn hàng #" . $request->order_id
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            // Lưu thông tin giao dịch vào database
            // $this->saveTransaction($response['id'], $request->order_id);

            // Chuyển hướng người dùng đến trang thanh toán PayPal
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tạo đơn thanh toán.');
    }

    /**
     * Xử lý khi thanh toán thành công
     */
    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // Cập nhật trạng thái đơn hàng thành công
            // $this->updateTransaction($response['id']);

            return redirect()->route('payment.completed')->with('success', 'Thanh toán thành công!');
        }

        return redirect()->route('payment.failed')->with('error', 'Thanh toán thất bại!');
    }

    /**
     * Xử lý khi hủy thanh toán
     */
    public function paymentCancel()
    {
        return redirect()->route('payment.failed')->with('error', 'Bạn đã hủy giao dịch thanh toán.');
    }
}
