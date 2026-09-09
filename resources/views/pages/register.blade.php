@extends('layouts.app')
@section('content')
<div x-data="registerForm()" class="max-w-sm mx-auto mt-16 bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Register</h1>

    <input x-model="name" placeholder="Name" class="w-full border rounded p-2 mb-3">
    <input x-model="email" type="email" placeholder="Email" class="w-full border rounded p-2 mb-3">
    <input x-model="password" type="password" placeholder="Password" class="w-full border rounded p-2 mb-3">
    <input x-model="password_confirmation" type="password" placeholder="Confirm Password" class="w-full border rounded p-2 mb-3">

    <select x-model="role" class="w-full border rounded p-2 mb-3">
        <option value="customer">Customer</option>
        <option value="agent">Agent</option>
        <option value="admin">Admin</option>
    </select>

    <button @click="submit" class="w-full bg-black text-white rounded p-2">Register</button>
    <p x-show="error" x-text="error" class="text-red-600 text-sm mt-2"></p>
</div>

<script>
function registerForm() {
    return {
        name: '', email: '', password: '', password_confirmation: '', role: 'customer', error: '',
        async submit() {
            this.error = '';
            try {
                const res = await api.call('POST', '/register', this);
                localStorage.setItem('token', res.token);
                localStorage.setItem('role', Object.values(res.role)[0]);
                window.location.href = '/dashboard';
            } catch (e) {
                this.error = JSON.stringify(e.data.errors || e.data.message);
            }
        }
    }
}
</script>
@endsection
