<x-guest-layout>
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <div class="col-lg-6 d-none d-lg-flex login-brand">
                <div class="brand-content">
                    <span class="brand-mark mb-3"><i class="bi bi-mortarboard"></i></span>
                    <h1>Student Portal</h1>
                    <p>Manage student records, review course offerings, and access academic updates.</p>
                    <div class="brand-features">
                        <div><i class="bi bi-check-circle-fill"></i> Monitor course enrollment and availability</div>
                        <div><i class="bi bi-check-circle-fill"></i> Manage course records and updates</div>
                        <div><i class="bi bi-check-circle-fill"></i> View course reports and alerts</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="login-container">
                    <div class="card login-card">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold">Welcome Back</h2>
                                <p class="text-muted mb-0">Sign in to the Student Portal</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email" required autofocus>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required>
                                        <button class="btn password-toggle" type="button" data-password-toggle="password"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                        <label class="form-check-label" for="remember_me">Remember me</label>
                                    </div>
                                    <a class="small" href="{{ route('password.request') }}">Forgot password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                            </form>

                            <p class="text-center mt-4 mb-3">
                                Need an account? <a href="{{ route('register') }}">Register</a>
                            </p>
                            <div class="demo-box">
                                <strong>Demo login</strong><br>
                                Email: admin@studentportal.local<br>
                                Password: password123
                            </div>
                            <p class="text-center small text-muted mt-3 mb-0">
                                Lab 3 login: <a href="/lab_folder/lab3_2411600038/indext.html">open Lab 3</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
