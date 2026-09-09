@extends('layouts.app')
@section('content')
<div x-data="ticketDetail({{ $id ?? 0 }})" x-init="load" x-cloak>
    <div class="bg-white p-6 rounded shadow mb-4" x-show="ticket">
        <h1 class="text-xl font-semibold" x-text="ticket?.subject"></h1>
        <p class="text-gray-600 mt-2" x-text="ticket?.description"></p>

        <div class="flex gap-2 mt-4 text-sm">
            <span class="px-2 py-1 bg-gray-100 rounded" x-text="'Status: ' + ticket?.status"></span>
            <span class="px-2 py-1 bg-gray-100 rounded" x-text="'Priority: ' + (ticket?.priority || 'pending AI')"></span>
            <span class="px-2 py-1 bg-gray-100 rounded" x-text="'Category: ' + (ticket?.category || 'pending AI')"></span>
        </div>
        <p class="text-sm text-blue-600 mt-2" x-show="ticket?.ai_summary">🤖 <span x-text="ticket?.ai_summary"></span></p>

        <div x-show="role !== 'customer'" class="mt-4 flex gap-2">
            <select x-model="newStatus" class="border rounded p-2 text-sm">
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
            <button @click="updateStatus" class="bg-black text-white px-3 py-2 rounded text-sm">Update Status</button>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="font-medium mb-3">Replies</h2>
        <template x-for="r in ticket?.replies || []" :key="r.id">
            <div class="border-b py-2 text-sm">
                <b x-text="r.user.name"></b>: <span x-text="r.message"></span>
            </div>
        </template>

        <textarea x-model="replyMsg" placeholder="Write a reply..." class="w-full border rounded p-2 mt-3" rows="2"></textarea>
        <button @click="sendReply" class="bg-black text-white px-4 py-2 rounded mt-2 text-sm">Send Reply</button>
    </div>
</div>

<script>
function ticketDetail(id) {
    return {
        id, ticket: null, newStatus: '', replyMsg: '',
        role: localStorage.getItem('role') || '',
        async load() {
            const res = await api.call('GET', '/tickets/' + this.id);
            this.ticket = res.data;
            this.newStatus = this.ticket.status;
        },
        async updateStatus() {
            await api.call('PUT', '/tickets/' + this.id, { status: this.newStatus });
            this.load();
        },
        async sendReply() {
            if (!this.replyMsg.trim()) return;
            await api.call('POST', '/tickets/' + this.id + '/replies', { message: this.replyMsg });
            this.replyMsg = '';
            this.load();
        }
    }
}
</script>
@endsection
