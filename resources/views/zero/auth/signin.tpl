<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{$config['appName']} - {$trans->t('signin')}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />		
		<link rel="shortcut icon" href="/favicon.png" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="/theme/zero/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/theme/zero/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	</head>
	<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<style>body { background-image: url('/theme/zero/assets/media/auth/bg4.jpg'); } [data-bs-theme="dark"] body { background-image: url('/theme/zero/assets/media/auth/bg4-dark.jpg'); }</style>
			<div class="d-flex flex-column flex-column-fluid flex-lg-row">
				<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
					<div class="d-flex flex-center flex-lg-start flex-column">
						<a href="" class="mb-7 fs-3hx fw-bold text-white">{$config['appName']}</a>						
					</div>
				</div>
				<div class="d-flex flex-center w-lg-50 p-10">
					<div class="card rounded-3 w-md-550px">
						<div class="card-body p-10 p-lg-20">							
							<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="/user" action="#">								
								<div class="text-center mb-11">
									<h1 class="text-dark fw-bolder mb-6">{$trans->t('signin')}</h1>
								</div>
								<div class="fv-row mb-8">
									<input type="text" placeholder="{$trans->t('email')}" name="email" autocomplete="off" id="signin-email" data-kt-translate="sign-in-input-email" class="form-control form-control-lg bg-transparent" />
								</div>
								<div class="fv-row mb-8">
									<input type="password" placeholder="{$trans->t('passwd')}" name="password" autocomplete="off" id="signin-passwd" data-kt-translate="sign-in-input-password" class="form-control form-control-lg bg-transparent" />
								</div>
								{if $config['enable_signin_captcha'] == true && $config['captcha_provider'] == 'turnstile' && $captcha['turnstile_sitekey'] != ''}
									<div class="fv-row mb-8">
										<div class="cf-turnstile" data-sitekey="{$captcha['turnstile_sitekey']}" data-theme="light"></div>
									</div>
								{/if}
								<div class="d-grid mb-10">
									<button id="kt_sign_in_submit" class="btn btn-primary">
										<span class="indicator-label" data-kt-translate="sign-in-submit">{$trans->t('signin')}</span>
										<span class="indicator-progress">
											<span data-kt-translate="general-progress">{$trans->t('please wait')}</span>
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>									
								</div>
								<div class="text-gray-500 text-center fw-semibold fs-6">
									<span><a class="link-primary" href="/auth/signup">{$trans->t('signup')}</a></span>
									|
									<span><a href="/password/reset" class="link-primary" data-kt-translate="sign-in-forgot-password">{$trans->t('forgot passwd')}</a></span>
								</div>
							</form>
						</div>
					</div>
				</div>				
			</div>
			<div class="app_footer py-4 d-flex flex-lg-column" id="kt_app_footer">
				<div class="app-container container-fluid d-flex flex-column flex-center py-3">
					<div class="text-white order-2 order-md-1 text-center">
						&copy;<script>document.write(new Date().getFullYear());</script>,&nbsp;<span>{$config["appName"]},&nbsp;Inc.&nbsp;All rights reserved.</span>
					</div>
				</div>
			</div>
		</div>
		<script src="/theme/zero/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/theme/zero/assets/js/scripts.bundle.js"></script>
		<script src="/js/auth/signin.min.js"></script>
		{if $config['enable_signin_captcha'] === true && $config['captcha_provider'] === 'turnstile'}
			<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
		{/if}
	</body>
</html>