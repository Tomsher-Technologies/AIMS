<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/font/iconsmind-s/css/iconsminds.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font/simple-line-icons/css/simple-line-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.only.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-float-label.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>

<body class="background show-spinner no-footer">
    <nav class="navbar fixed-top">
        <div class="container">
            <a class="navbar-logo m-auto"  href="{{ route('login') }}">
                <span class=" d-block"><img src="{{ asset('assets/images/logo.png') }}" class="login-logo"></span>

            </a>
        </div>
    </nav>
    <main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-6 mt-5">
              <div class="card">
                    <div class="card-header text-center mt-5">
                        <i class="simple-icon-lock" style="font-size: 50px;"></i>
                        <h2><strong>Forgot Password</strong></h2>
                        <h6>No worries! Let us know your email and we'll send you a password reset link.</h6>
                    </div>
                  <div class="card-body mb-5">
  
                    @if (Session::has('message'))
                         <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
  
                      <form action="{{ route('forget.password.post') }}" method="POST" autocomplete="off">
                          @csrf
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" autocomplete="off" required >
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  Send Password Reset Link
                              </button>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/dore.script.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>