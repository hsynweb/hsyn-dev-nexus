<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentNotification;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $services = $user->services()->latest()->get();
        $billing = $user->invoices()->latest()->get();
        $projects = $user->projects()->latest()->get();
        $tickets = $user->tickets()->with('messages.author')->latest()->get();
        $paymentNotifications = $user->paymentNotifications()->latest()->get();

        return view('preview.client-hub', compact(
            'services',
            'billing',
            'projects',
            'tickets',
            'paymentNotifications'
        ));
    }

    public function storePaymentNotification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'channel' => ['required', 'string', 'max:50'],
            'reference_code' => ['nullable', 'string', 'max:255'],
            'payload' => ['nullable', 'string'],
        ]);

        $invoice = Invoice::query()
            ->whereKey($validated['invoice_id'])
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        PaymentNotification::create([
            ...$validated,
            'invoice_id' => $invoice->id,
            'customer_id' => $request->user()->id,
            'status' => 'pending-review',
        ]);

        return back()->with('status', 'Odeme bildirimi gonderildi.');
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string'],
        ]);

        $ticket = Ticket::create([
            'customer_id' => $request->user()->id,
            'service_id' => $validated['service_id'] ?? null,
            'subject' => $validated['subject'],
            'department' => 'support',
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'author_id' => $request->user()->id,
            'visibility' => 'public',
            'body' => $validated['message'],
        ]);

        return back()->with('status', 'Destek talebi olusturuldu.');
    }

    public function replyTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        abort_unless($ticket->customer_id === $request->user()->id, 403);

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'author_id' => $request->user()->id,
            'visibility' => 'public',
            'body' => $validated['body'],
        ]);

        $ticket->update([
            'status' => 'open',
        ]);

        return back()->with('status', 'Ticket mesaji eklendi.');
    }
}
