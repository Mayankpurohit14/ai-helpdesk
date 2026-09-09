@extends('layouts.app')
@section('content')
<div x-data="loginForm()" class="max-w-sm mx-auto mt-16 bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Login</h1>

    <input x-model="email" type="email" placeholder="Email" class="w-full border rounded p-2 mb-3">
    <input x-model="password" type="password" placeholder="Password" class="w-full border rounded p-2 mb-3">

    <button @click="submit" class="w-full bg-black text-white rounded p-2">Login</button>

    <p x-show="error" x-text="error" class="text-red-600 text-sm mt-2"></p>
    <p class="text-sm mt-3">No account? <a href="/register" class="underline">Register</a></p>
</div>

<script>
function loginForm() {
    return {
        email: '', password: '', error: '',
        async submit() {
            this.error = '';
            try {
                const res = await api.call('POST', '/login', { email: this.email, password: this.password });
                localStorage.setItem('token', res.token);
                localStorage.setItem('role', Object.values(res.role)[0]);
                window.location.href = '/dashboard';
            } catch (e) {
                this.error = e.data.message || 'Login failed';
            }
        }
    }
}
</script>
@endsection
