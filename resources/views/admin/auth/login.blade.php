<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }}</title>
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
            <a class="navbar-logo m-auto" href="#">
                <span class=" d-block"><img src="{{ asset('assets/images/logo.png') }}" class="login-logo"></span>

            </a>
        </div>
    </nav>
    <main>
        <div class="container">
            <div class="row h-100">
                <div class="col-12 col-md-4 mx-auto my-auto">
                    <div class="card auth-card">
                      
                        <div class="form-side">
                            <h6 class="mb-1">admin login</h6>
                            <p>If you have an AIMS account, please log in below.</p>
                            <form method="POST" action="{{ route('login.custom') }}">
                                @csrf
                                <label class="form-group has-float-label mb-4">
                                    <input class="form-control" type="text" value="{{ old('email') }}" name="email" id="email" autofocus/>
                                    <span>E-mail</span>
                                </label>
                            
                                <label class="form-group has-float-label mb-1">
                                    <input class="form-control" autocomplete="new-password" value="{{ old('password') }}" type="password" id="password" name="password" />
                                    <span>Password</span>
                                </label>
                                <div class="justify-content-between align-items-center mb-1">
                                    @if ($errors->has('email'))
                                    <div class="alert alert-danger"><strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                    
                                    @if ($errors->has('password'))
                                        <div class="alert alert-danger"><strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                    @if(session()->has('status'))
                                        <div class="alert alert-danger">
                                            <strong>{{ session()->get('status') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary btn-lg btn_primary">LOGIN</button>
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