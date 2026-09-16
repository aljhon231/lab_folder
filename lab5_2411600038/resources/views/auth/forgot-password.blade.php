<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold mb-3">Forgot password</h2>
                        <p class="text-muted">Enter your email and we will send a reset link. Mail is logged locally in this lab.</p>
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Email reset link</button>
                        </form>
                        <p class="mt-3 mb-0"><a href="{{ route('login') }}">Back to login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
