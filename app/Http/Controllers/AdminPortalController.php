<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Server;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPortalController extends Controller
{
    public function dashboard(): View
    {
        $customers = User::query()->where('role', 'client')->latest()->get();
        $projects = Project::query()->with('customer')->latest()->get();
        $services = Service::query()->with(['customer', 'project'])->latest()->get();
        $invoices = Invoice::query()->with('customer')->latest()->get();
        $tickets = Ticket::query()->with(['customer', 'service'])->latest()->get();
        $servers = Server::query()->with(['sites' => fn ($query) => $query->latest()->limit(4)])->latest()->get();

        return view('preview.control-center', [
            'serverStats' => [
                ['label' => 'Aktif sunucu', 'value' => (string) $servers->count(), 'delta' => $servers->where('status', 'active')->count().' aktif'],
                ['label' => 'Izlenen site', 'value' => (string) $servers->sum(fn ($server) => $server->sites->count()), 'delta' => 'Agent ile gelen domainler'],
                ['label' => 'Acik ticket', 'value' => (string) $tickets->where('status', 'open')->count(), 'delta' => 'Destek kuyrugu anlik'],
                ['label' => 'Bekleyen tahsilat', 'value' => 'TL '.number_format((float) $invoices->whereIn('status', ['sent', 'overdue'])->sum('amount'), 0, ',', '.'), 'delta' => 'Tahsilat baskisi'],
            ],
            'deployments' => $servers,
            'finance' => $invoices,
            'tickets' => $tickets,
            'customers' => $customers,
            'projects' => $projects,
            'services' => $services,
            'servers' => $servers,
            'serverTimeline' => collect()
                ->merge($tickets->take(3)->map(fn (Ticket $ticket) => [
                    'time' => optional($ticket->created_at)->format('H:i') ?? '--:--',
                    'event' => $ticket->subject.' ticketi '.$ticket->status.' durumunda.',
                ]))
                ->merge($invoices->take(3)->map(fn (Invoice $invoice) => [
                    'time' => optional($invoice->updated_at)->format('H:i') ?? '--:--',
                    'event' => $invoice->customer?->name.' icin '.$invoice->invoice_number.' faturasi '.$invoice->status.' durumda.',
                ]))
                ->take(6),
        ]);
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $password = $validated['password'] ?? 'Temp123456!';

        User::create([
            'name' => $validated['name'],
            'display_name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'client',
            'password' => $password,
        ]);

        return back()->with('status', 'Musteri olusturuldu. Varsayilan sifre: '.$password);
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'priority' => ['required', 'string', 'max:50'],
            'summary' => ['nullable', 'string'],
            'starts_on' => ['nullable', 'date'],
            'due_on' => ['nullable', 'date'],
        ]);

        Project::create([
            ...$validated,
            'progress' => 0,
        ]);

        return back()->with('status', 'Proje eklendi.');
    }

    public function storeService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'plan' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'billing_cycle' => ['required', 'string', 'max:50'],
            'monthly_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        Service::create($validated);

        return back()->with('status', 'Hizmet kaydi eklendi.');
    }

    public function storeInvoice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'issued_on' => ['required', 'date'],
            'due_on' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        Invoice::create([
            ...$validated,
            'invoice_number' => 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(4)),
            'paid_amount' => 0,
        ]);

        return back()->with('status', 'Fatura olusturuldu.');
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'subject' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:50'],
            'priority' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string'],
        ]);

        $ticket = Ticket::create([
            'customer_id' => $validated['customer_id'],
            'service_id' => $validated['service_id'] ?? null,
            'subject' => $validated['subject'],
            'department' => $validated['department'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'assigned_to' => $request->user()->id,
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'author_id' => $request->user()->id,
            'visibility' => 'internal',
            'body' => $validated['message'],
        ]);

        return back()->with('status', 'Ticket acildi.');
    }

    public function storeServer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'provider' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'ip'],
            'os_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        Server::create([
            ...$validated,
            'agent_status' => 'awaiting-install',
        ]);

        return back()->with('status', 'Sunucu kaydi eklendi.');
    }

    public function updateInvoiceStatus(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $invoice->update([
            'status' => $validated['status'],
            'paid_amount' => $validated['paid_amount'] ?? $invoice->paid_amount,
            'paid_at' => $validated['status'] === 'paid' ? now() : null,
        ]);

        return back()->with('status', 'Fatura guncellendi.');
    }

    public function updateTicketStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
            'first_replied_at' => $ticket->first_replied_at ?? now(),
        ]);

        return back()->with('status', 'Ticket durumu guncellendi.');
    }

    public function updateServerStatus(Request $request, Server $server): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'agent_status' => ['required', 'string', 'max:50'],
        ]);

        $server->update($validated);

        return back()->with('status', 'Sunucu durumu guncellendi.');
    }
}
