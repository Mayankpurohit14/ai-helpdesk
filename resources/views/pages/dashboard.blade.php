@extends('layouts.app')
@section('content')
<div x-data="dashboard()" x-init="load">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Tickets (<span x-text="role"></span> view)</h1>
        <a href="/tickets/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Ticket</a>
    </div>

    <div class="space-y-3">
        <template x-for="t in tickets" :key="t.id">
            <a :href="'/tickets/' + t.id" class="block bg-white p-4 rounded shadow hover:shadow-md">
                <div class="flex justify-between">
                    <span class="font-medium" x-text="t.subject"></span>
                    <span class="text-xs px-2 py-1 rounded bg-gray-100" x-text="t.status"></span>
                </div>
                <p class="text-sm text-gray-500 mt-1" x-text="t.ai_summary || 'AI classifying...'"></p>
                <div class="text-xs text-gray-400 mt-2 flex gap-3">
                    <span x-show="t.priority">Priority: <b x-text="t.priority"></b></span>
                    <span x-show="t.category">Category: <b x-text="t.category"></b></span>
                    <span x-text="t.created_at"></span>
                </div>
            </a>
        </template>
        <p x-show="tickets.length === 0" class="text-gray-400 text-sm">No tickets yet.</p>
    </div>
</div>

<script>
function dashboard() {
    return {
        tickets: [], role: localStorage.getItem('role') || '',
        async load() {
            if (!api.token()) { window.location.href = '/login'; return; }
            const res = await api.call('GET', '/tickets');
            this.tickets = res.data;
        }
    }
}
</script>
@endsection
