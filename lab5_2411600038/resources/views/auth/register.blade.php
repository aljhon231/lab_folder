<x-guest-layout>
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <div class="col-lg-6 d-none d-lg-flex login-brand">
                <div class="brand-content">
                    <h1>Create an account</h1>
                    <p>Register an account to manage student records, course updates, and academic reports.</p>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="login-container">
                    <div class="card login-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="fw-bold mb-4">Register</h2>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name</label>
                                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input id="password" class="form-control" type="password" name="password" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="password_confirmation">Confirm password</label>
                                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                                </div>
                                <button class="btn btn-primary w-100 py-2" type="submit">Register</button>
                            </form>
                            <p class="text-center mt-4 mb-0">
                                Already registered? <a href="{{ route('login') }}">Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
