<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Helpdesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b px-6 py-3 flex justify-between items-center">
        <a href="/" class="font-bold text-lg">AI Helpdesk</a>
        <div id="nav-auth"></div>
    </nav>
    <main class="max-w-4xl mx-auto p-6">
        @yield('content')
    </main>

    <script>
        // Tiny global helper — all pages use this to call the API
        const api = {
            base: '/api',
            token: () => localStorage.getItem('token'),
            headers: () => ({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(api.token() ? { Authorization: `Bearer ${api.token()}` } : {})
            }),
            async call(method, url, body = null) {
                const res = await fetch(api.base + url, {
                    method,
                    headers: api.headers(),
                    body: body ? JSON.stringify(body) : null
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw { status: res.status, data };
                return data;
            }
        };
    </script>
</body>
</html>
