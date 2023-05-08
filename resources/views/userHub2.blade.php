<!DOCTYPE html>
<html lang="en">
<head>
	<title>User Hub</title>
</head>
<body style="margin-top: 12px; background-image: url('wood.jpeg');">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
  
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
				You are Logged In
                        </div>
                    @endif
  
                    
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>