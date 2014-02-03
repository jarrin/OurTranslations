<!DOCTYPE html>
<html>
	<head>
		<title>[title]</title>
		<link href='https://fonts.googleapis.com/css?family=Exo+2:400,400italic,700' rel='stylesheet' type='text/css'>
		[css]
		[js]

		<script type="text/javascript">
			$(document).ready([this-page].ready);
		</script>

	</head>

	<body>
		<div id="up" >
			<div id="logo-container" >

				<div class="logo" ></div>
				<div class="text" >[title]</div>
			</div>
			[if(loggedin)]
				<div id="points" >
					[points]
				</div>
			[else]
				<input type="button" id="login_btn" value="[login-or-register]" href="#login" >
			[/if]
			<nav>
				<ul id="navigator">
					<li data-url="./" class="[page-index]" ><a>[dashboard]<div class="badge" >1233</div></a></li>
					<li data-url="my-account" class="[page-my-account]" ><a>[my-account]</a></li>
					<li data-url="submit" class="[page-request-translation]" ><a>[request-translation]</a></li>
					<li data-url="dashboarrd" class="[page-browse-translation]" >
						<a>[browse-translation]</a>
						<ul class="sub-menu" >
							<li ><a>Item 1</a></li>
							<li ><a>Item 2</a></li>
							<li ><a>Item 3</a></li>
						</ul>

					</li>
					<li data-url="dashboarrd" class="[page-info]" ><a>[info]</a></li>
					<li data-url="other-actions" class="[page-other-actions]" ><a>[other-actions]</a></li>
				</ul>
			</nav>

		</div>
		<div id="main" >
			[body]
		</div>
		<div id="login" >
			<div class="popup-header" >
				<div>[login]</div>
			</div>
			<form id="login-form" action="">
				<div id="login-error" >

				</div>
				<label for="email" >[your-email-address]</label>
				<input type="email" id="email" required />				
				<label for="password">[your-password]</label>
				<input type="password" id="password" required />
				<input type="submit" value="[login]">
				<input type="button" id="login-signup" value="[signup]">
			</div>
		</div
>	</body>

</html>