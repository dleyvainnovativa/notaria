<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PaymentsController extends Controller
{
    public function index()
    {
        $user = User::find(session('user_id'));
        $payments = $user->payments()->get();
        $amount = 0;
        foreach ($payments as $key => $payment) {
            $amount += $payment->amount ?? 0;
        }
        $data["amount"] = $amount;
        $data["payments"] = $payments->count();
        return view('admin.pages.payments', $data);
    }
    public function invoice($id)
    {
        $user = User::find(session('user_id'));
        $payment = Payment::find("$id");
        $data["payment"] = $payment;
        $data["memorial"] = $payment->memorial;
        return view('admin.pages.invoice', $data);
    }
    public function download($id)
    {
        $user = User::find(session('user_id'));
        $payment = Payment::find("$id");
        $name = $payment->folio;
        $data = $payment->toArray();
        $data["created_at"] = $payment->created_at;
        $data["memorial_client_name"] = $payment->memorial->user->name;
        $data["memorial_client_email"] = $payment->memorial->user->email;
        $pdf = Pdf::loadView('templates/invoice', $data)->setPaper('a4', 'portrait');
        // dd(json_encode($data));
        return $pdf->download("$name" . " Factura.pdf");
        return view('templates/invoice', $data);
    }
}
