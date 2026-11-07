<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\PayMongoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberPortalController extends Controller
{
    /**
     * Show the member pass lookup / portal home.
     */
    public function index(): View
    {
        return view('portal.lookup');
    }

    /**
     * Search for a member by phone number or QR code.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:100'],
        ]);

        $search = trim($validated['query']);

        $member = Member::query()
            ->where('qr_code', $search)
            ->orWhere('phone', $search)
            ->orWhere('email', $search)
            ->first();

        if (! $member) {
            return back()
                ->withInput()
                ->with('error', 'No member record found matching that code, phone number, or email.');
        }

        return redirect()->route('portal.pass', ['qr_code' => $member->qr_code]);
    }

    /**
     * Render the member's digital card and pass.
     */
    public function showPass(string $qrCode): View
    {
        $member = Member::query()
            ->with([
                'gym',
                'activeSubscription.plan',
                'latestSubscription.plan',
                'checkIns' => fn ($query) => $query->latest('scanned_at')->limit(5),
            ])
            ->where('qr_code', $qrCode)
            ->firstOrFail();

        $qrSvg = QrCode::format('svg')
            ->size(220)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($member->passUrl());

        $subscription = $member->activeSubscription ?? $member->latestSubscription;
        $outstanding = $member->outstandingSubscription();

        return view('portal.pass', [
            'member' => $member,
            'gym' => $member->gym,
            'qrSvg' => $qrSvg,
            'subscription' => $subscription,
            'outstanding' => $outstanding,
            'checkIns' => $member->checkIns,
        ]);
    }

    /**
     * Initiate GCash payment for outstanding dues.
     */
    public function pay(string $qrCode, PayMongoService $payMongo): RedirectResponse
    {
        $member = Member::where('qr_code', $qrCode)->firstOrFail();
        $outstanding = $member->outstandingSubscription();

        if (! $outstanding || $outstanding->amountDue() <= 0) {
            return redirect()
                ->route('portal.pass', ['qr_code' => $qrCode])
                ->with('info', 'You do not have any outstanding dues at this time.');
        }

        try {
            $payment = $payMongo->createGcashCheckout($outstanding);

            if ($payment->checkout_url) {
                return redirect()->away($payment->checkout_url);
            }

            return redirect()
                ->route('portal.pass', ['qr_code' => $qrCode])
                ->with('error', 'Unable to generate checkout URL. Please pay at the front desk.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('portal.pass', ['qr_code' => $qrCode])
                ->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }
}
