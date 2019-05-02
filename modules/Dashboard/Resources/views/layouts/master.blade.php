<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="modules/dashboard/dashboard/images/logo/favicon.png">
    <link href="{{ mix("app.css", 'build/dashboard') }}" rel="stylesheet">

    <script>window.App = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'logo' => adminLogo(),
            'apiRoute' => route('dashboard.api.index'),
            'user' => publicUser(),
            'settings' => [
              'captcha' => [
                'key' => config('services.recaptcha.key')
              ],
            ],
            'copyRight' => companyCopyright(),
            'options' => [
              'name' => setting('company_name'),
              'perPage' => 50,
              'resourcesTab' => setting('client_resources_page_content')
            ]
        ]); ?>
    </script>
</head>

<body>
    <div id="app" class="app"></div>
    <script type="text/javascript" src="{{ mix("manifest.js", 'build/dashboard') }}"></script>
    <script type="text/javascript" src="{{ mix("vendor.js", 'build/dashboard') }}"></script>
    <script type="text/javascript" src="{{ mix("main.js", 'build/dashboard') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script> 
</body>

</html>
