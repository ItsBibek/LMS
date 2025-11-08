<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academia Library - Admin Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root { --indigo:#4f46e5; --slate:#334155; --border:#e2e8f0; }
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f8fafc;color:#0f172a;position:relative}
    body::before{content:"";position:fixed;inset:0;background:url('{{ asset('storage/background/bg.jpg') }}') center/cover no-repeat;opacity:.25;z-index:0}
    .container{min-height:100vh;display:grid;place-items:center;padding:24px;position:relative;z-index:1}
    .card{width:100%;max-width:480px;background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
    .p-6{padding:24px}
    label{display:block;font-size:13px;font-weight:600;color:#475569}
    input{margin-top:6px;width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px}
    .btn{display:inline-flex;justify-content:center;align-items:center;width:100%;border:0;border-radius:10px;background:var(--indigo);color:#fff;font-weight:600;padding:10px 12px;margin-top:4px}
    .mt-4{margin-top:16px}.mt-6{margin-top:24px}.mb-2{margin-bottom:8px}.mb-4{margin-bottom:16px}
    .error{color:#dc2626;font-size:13px}
    .title{font-weight:700;font-size:18px;color:var(--slate);margin-bottom:8px;text-align:center}
    .link{display:inline-block;margin-top:16px;font-weight:600;color:var(--indigo);text-decoration:underline;text-align:center;width:100%;font-size:12px}
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="p-6">
        <div class="title">Academia Library - Admin Portal</div>
        @if ($errors->any())
          <div class="mb-4 error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login') }}">
          @csrf
          <div class="mt-4">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
          </div>
          <div class="mt-4">
            <label>Password</label>
            <input type="password" name="password" required />
          </div>
          <button type="submit" class="btn mt-6">Login</button>
        </form>
        <a class="link" href="{{ route('admin.password.request') }}">Forgot password?</a>
        <a class="link" href="{{ route('student.login.form') }}">STUDENT LOGIN</a>
      </div>
    </div>
  </div>
</body>
</html>
