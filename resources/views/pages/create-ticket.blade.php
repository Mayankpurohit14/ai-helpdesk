@extends('layouts.app')
@section('content')
<div x-data="createTicket()" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">New Ticket</h1>

    <input x-model="subject" placeholder="Subject" class="w-full border rounded p-2 mb-3">
    <textarea x-model="description" placeholder="Describe the issue..." rows="5" class="w-full border rounded p-2 mb-3"></textarea>

    <button @click="submit" class="bg-black text-white px-4 py-2 rounded" :disabled="loading">
        <span x-text="loading ? 'Submitting...' : 'Submit Ticket'"></span>
    </button>
    <p x-show="error" x-text="error" class="text-red-600 text-sm mt-2"></p>
</div>

<script>
function createTicket() {
    return {
        subject: '', description: '', error: '', loading: false,
        async submit() {
            this.loading = true; this.error = '';
            try {
                const res = await api.call('POST', '/tickets', { subject: this.subject, description: this.description });
                window.location.href = '/tickets/' + res.data.id;
            } catch (e) {
                this.error = JSON.stringify(e.data.errors || e.data.message);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
