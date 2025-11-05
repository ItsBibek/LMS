<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root { --indigo:#4f46e5; --slate:#334155; --border:#e2e8f0; }
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f8fafc;color:#0f172a}
    .container{min-height:100vh;display:grid;place-items:center;padding:24px}
    .card{width:100%;max-width:480px;background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
    .p-6{padding:24px}
    .tabs{display:flex;border:1px solid var(--border);border-radius:10px;overflow:hidden}
    .tab{flex:1;text-align:center;padding:10px 12px;font-weight:600;font-size:14px;cursor:pointer;color:#475569;background:#f8fafc;border-right:1px solid var(--border)}
    .tab:last-child{border-right:0}
    .tab.active{background:#fff;color:var(--indigo)}
    label{display:block;font-size:13px;font-weight:600;color:#475569}
    input{margin-top:6px;width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px}
    .btn{display:inline-flex;justify-content:center;align-items:center;width:100%;border:0;border-radius:10px;background:var(--indigo);color:#fff;font-weight:600;padding:10px 12px;margin-top:4px}
    .mt-4{margin-top:16px}.mt-6{margin-top:24px}.mb-4{margin-bottom:16px}
    .error{color:#dc2626;font-size:13px}
    .header{font-weight:700;font-size:18px;color:var(--slate);margin-bottom:8px}
    .subheader{font-size:13px;color:#64748b;margin-bottom:16px}
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="p-6">
        <div class="header">Sign in</div>
        <div class="subheader">Switch between Admin and Student tabs</div>
        <div class="tabs" role="tablist">
          <button id="tab-admin" class="tab active" type="button" aria-selected="true">Admin</button>
          <button id="tab-student" class="tab" type="button" aria-selected="false">Student</button>
        </div>

        <div id="errors" class="mb-4 error" style="display:none;"></div>

        <div id="panel-admin" class="mt-6" role="tabpanel" aria-labelledby="tab-admin">
          <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mt-4">
              <label>Username</label>
              <input type="text" name="username" value="{{ old('username') }}" required />
            </div>
            <div class="mt-4">
              <label>Password</label>
              <input type="password" name="password" required />
            </div>
            <button type="submit" class="btn mt-6">Sign in as Admin</button>
          </form>
        </div>

        <div id="panel-student" class="mt-6" role="tabpanel" aria-labelledby="tab-student" style="display:none;">
          <form method="POST" action="{{ route('student.login') }}">
            @csrf
            <div class="mt-4">
              <label>Email</label>
              <input type="email" name="email" value="{{ old('email') }}" required />
            </div>
            <div class="mt-4">
              <label>Batch Number</label>
              <input type="text" name="batch_no" value="{{ old('batch_no') }}" required />
            </div>
            <button type="submit" class="btn mt-6">Sign in as Student</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const tabAdmin = document.getElementById('tab-admin');
    const tabStudent = document.getElementById('tab-student');
    const panelAdmin = document.getElementById('panel-admin');
    const panelStudent = document.getElementById('panel-student');

    function activate(tab){
      if(tab === 'admin'){
        tabAdmin.classList.add('active'); tabAdmin.setAttribute('aria-selected','true');
        tabStudent.classList.remove('active'); tabStudent.setAttribute('aria-selected','false');
        panelAdmin.style.display='block'; panelStudent.style.display='none';
      } else {
        tabStudent.classList.add('active'); tabStudent.setAttribute('aria-selected','true');
        tabAdmin.classList.remove('active'); tabAdmin.setAttribute('aria-selected','false');
        panelStudent.style.display='block'; panelAdmin.style.display='none';
      }
    }

    tabAdmin.addEventListener('click', () => activate('admin'));
    tabStudent.addEventListener('click', () => activate('student'));

    // If validation errors exist from backend, show them above forms
    (function(){
      const errors = @json($errors->all());
      if(errors && errors.length){
        const el = document.getElementById('errors');
        el.style.display='block';
        el.innerText = errors[0];
      }
    })();
  </script>
</body>
</html>
