<!DOCTYPE html>
<html>
	<head>
		<title>[@meta_title]</title>
		<meta charset='[@meta_charset]' />
		<meta name='viewport' content='[@meta_viewport]' />
		<meta name='author' content='[@meta_author]' />
		<meta name='contact' content='[@meta_contact]' />
		<meta name='distribution' content='[@meta_distribution]' />
		<meta name='google' content='[@meta_google]' />
		<meta name='robots' content='[@meta_robots]' />
		<meta name='copyright' content='[@meta_copyright]' />
		<meta name='xua' content='[@meta_xua]'>
		<meta name='type' content='[@meta_type]'>
		<meta name='product' content='[@meta_product]'>
		<meta name='description' content='[@meta_description]'/>
		<meta name='disclaimer' content='[@meta_disclaimer]'/>
		<meta name='keywords' content='[@meta_keywords]'/>
		<meta name='version' content='[@meta_version]'/>
		<meta property='og:title' content='[@meta_product]' />
		<meta property='og:description' content='[@meta_description]' />
		<meta property='og:image' content='[@meta_logo]' />
		<link rel='icon' href='[@meta_icon]'>
		<link href='[@font_url]' rel='stylesheet'>		
		<style>
			:root {
				--bg:				#ffffff;
				--panel:			#fdfdfd;
				--accent:			#f2f2f2;
				--text:				#333;
				--muted:			#777;
				--button-bg:		#e6e6e6;
				--button-hover:		#dcdcdc;
			}
			body {
				margin:				0;
				font-family:		"[@font_name]";
				background:			var(--bg);
				color:				var(--text);
			}
			#nav-toggle {
				display:			none;
			}
			.hamburger {
				display:			inline-block;
				cursor:				pointer;
				padding:			12px;
			}
			.hamburger span {
				display:			block;
				width:				28px;
				height:				3px;
				margin:				6px 0;
				background:			var(--text);
				transition:			0.3s;
			}
			nav {
				position:			fixed;
				top:				0;
				left:				0;
				height:				100%;
				width:				260px;
				max-width:			80%;
				background:			var(--panel);
				border-right:		1px solid var(--accent);
				transform:			translateX(-100%);
				transition:			transform 0.3s ease;
				box-shadow:			2px 0 8px #0002;
				z-index:			1000;
				display:			flex;
				flex-direction:		column;
			}
			nav .menu-container {
				flex:				1;
				overflow-y:			auto;
				padding:			1rem;
			}
			nav ul {
				list-style:			none;
				padding:			0;
				margin:				0;
			}
			nav li {
				margin:				1rem 0;
			}
			nav a {
				color:				var(--text);
				text-decoration:	none;
				font-size:			1.1rem;
			}
			.close-btn {
				display:			inline-block;
				cursor:				pointer;
				padding:			10px 14px;
				background:			var(--accent);
				border-radius:		4px;
				font-size:			1rem;
				color:				var(--text);
				text-align:			center;
				margin:				1rem;
			}
			.close-btn:hover {
				background:			#eaeaea;
			}
			#nav-toggle:checked ~ nav {
				transform:			translateX(0);
			}
			#nav-toggle:checked + label .top {
				transform:			rotate(45deg) translate(5px, 5px);
			}
			#nav-toggle:checked + label .middle {
				opacity:			0;
			}
			#nav-toggle:checked + label .bottom {
				transform:			rotate(-45deg) translate(6px, -6px);
			}
			header {
				display:			flex;
				justify-content:	space-between;
				align-items:		center;
				padding:			1rem;
				background:			var(--panel);
				border-bottom:		1px solid var(--accent);
				position:			sticky;
				top:				0;
				z-index:			500;
			}
			.brand {
				font-weight:		bold; font-size: 1.2rem;
			}
			main {
				padding:			2rem;
			}
			.form-group {
				margin-bottom:		1.5rem;
			}
			label {
				display:			block;
				margin-bottom:		.5rem;
				font-weight:		600;
				color:				var(--text);
			}
			input[type="text"], textarea, input[type="password"], input[type="email"], input[type="number"], input[type="time"], input[type="date"]  {
				width:				95%;
				padding:			.75rem;
				border:				1px solid var(--accent);
				border-radius:		4px;
				background:			var(--panel);
				font-size:			1rem;
				color:				var(--text);
			}
			textarea {
				resize:				vertical; min-height: 100px;
			}
			button {
				padding:			.75rem 1.5rem;
				border:				none;
				border-radius:		4px;
				background:			var(--button-bg);
				font-size:			1rem;
				font-weight:		600;
				cursor:				pointer;
				color:				var(--text);
			}
			button:hover {
				background:			var(--button-hover);
			}
			table {
				width:				100%;
				border-collapse:	collapse;
				margin-top:			2rem;
				background:			var(--panel);
				border:				1px solid var(--accent);
				font-size:			small;
			}
			th, td {
				padding:			.5rem .5rem;
				border:				1px solid var(--accent);
				text-align:			left;
			}
			th {
				background:			var(--accent);
				font-weight:		600;
			}
			tr:nth-child(even) {
				background:			#fafafa;
			}
			@media (max-width: 600px) {
				nav {
					width:			100%;
					max-width:		100%;
				}
				nav a {
					font-size:		1.2rem;
				}
				.close-btn {
					width:			calc(100% - 2rem);
					text-align:		center; font-size: 1.1rem;
				}
				.brand {
					font-size:		1rem;
				}
				.hamburger span {
					width:			26px;
				}
			}
			a.button-link {
				display:			inline-block;
				padding:			.65rem 1.5rem;
				border-radius:		4px;
				background:			var(--button-bg);
				font-size:			1rem;
				font-weight:		600;
				color:				var(--text);
				text-decoration:	none;
				text-align:			center;
				cursor:				pointer;
			}
			a.button-link:hover {
				background:			var(--button-hover);
			}
			a.button-small-link {
				display:			inline-block;
				padding:			.24rem .36rem;
				border-radius:		2px;
				font-size:			small;
				font-weight:		600;
				color:				var(--text);
				text-decoration:	none;
				text-align:			center;
				cursor:				pointer;
			}
			a.button-small-link:hover {
				background:			var(--button-hover);
			}
			.calendar-grid {
				display:			grid;
				width:				100%;
				grid-template-columns:			11% 11% 11% 11% 11% 11% 11%;
			}
			.calendar-grid div {
				height:				44px;
				width:				36px;
				border:				1px solid transparent;
			}
			.calendar-grid div:hover {
				background:			var(--button-hover);
				border:				1px solid #d0d0d0;
			}
			@media screen and (max-width: 36px) {
				.calendar-grid {
					grid-template-columns:		auto;  
				}
				.no-event {
					display:					none;
				}
			}
			mark {
				color:				red;
				background-color:	transparent ;
			}
			.day-link {
				cursor:				pointer;
				color:				var(--text);
				text-decoration:	none;
			}
			.container {
				overflow:			hidden;
			}
			.timeline {
				ul {
					padding:		0 0 0 0;
					margin:			0;
					list-style:		none;
					&::before {
						width:		1px;
						top:		0;
						left:		2.5em;
						z-index:	-1;
					}
				}
				li div {
					display:		inline-block;
					margin:			1em 0;
					vertical-align:	top;
				}
				.bullet {
					width:			1em;
					height:			1em;
					box-sizing:		border-box;
					border-radius:	50%;
					z-index:		1;
					margin-right:	1em;
				}
				.event:hover {
					background:		var(--button-bg);
				}
				.time {
					width:			30%;
					font-size:		0.75em;
					padding-top:	0.25em;
				}
				.desc {
					width:			50%;
				}
			}
		</style>
	</head>
	<body>
		<input type="checkbox" id="nav-toggle" />
		<header>
			<div class="brand">
				<a href="?"><svg height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  viewBox="0 0 511.999 511.999" xml:space="preserve"><polygon style="fill:#CEE8FA;" points="385.518,289.025 385.518,311.609 319.82,358.012 256.662,294.854 255.338,294.854 192.18,358.012 126.482,311.609 126.482,289.025 192.445,191.901 255.999,98.348 319.555,191.901 "/><path style="fill:#2D527C;" d="M255.999,0C114.842,0,0,114.842,0,255.999s114.842,255.999,255.999,255.999 c48.979,0,96.587-13.88,137.676-40.137c5.181-3.31,6.554-9.874,6.532-15.024V311.614c0-0.028-0.006-0.054-0.006-0.082v-22.507 c0-2.941-0.884-5.817-2.537-8.25l-65.889-97.004c-0.026-0.04-0.047-0.081-0.073-0.12l-63.555-93.555 c-2.734-4.024-7.282-6.433-12.147-6.433s-9.413,2.41-12.147,6.433l-63.555,93.555c-0.023,0.035-0.041,0.072-0.065,0.106 l-65.9,97.018c-1.652,2.435-2.537,5.308-2.537,8.251v22.507c0,0.028-0.006,0.054-0.006,0.082v119.087 c-50.309-41.6-82.421-104.47-82.421-174.701c0-124.964,101.667-226.63,226.63-226.63s226.63,101.667,226.63,226.63 c0,18.676-2.275,37.24-6.761,55.176c-1.968,7.866,2.815,15.84,10.683,17.808c7.864,1.968,15.84-2.814,17.808-10.683 c5.069-20.263,7.639-41.225,7.639-62.302C512,114.842,397.158,0,255.999,0z M321.56,372.585c0.2-0.024,0.401-0.046,0.599-0.078 c0.204-0.032,0.405-0.075,0.608-0.116c0.184-0.038,0.369-0.076,0.551-0.12c0.209-0.051,0.417-0.109,0.624-0.169 c0.173-0.05,0.344-0.104,0.515-0.162c0.211-0.07,0.423-0.144,0.633-0.225c0.16-0.062,0.319-0.129,0.476-0.197 c0.216-0.093,0.432-0.184,0.645-0.288c0.138-0.068,0.273-0.142,0.41-0.213c0.228-0.119,0.455-0.236,0.678-0.367 c0.06-0.037,0.119-0.078,0.179-0.115c0.275-0.169,0.551-0.335,0.816-0.523l42.542-30.046v111.457 c-30.511,17.968-64.744,28.441-100.154,30.717V329.646l38.751,38.751c0.101,0.101,0.211,0.186,0.314,0.283 c0.236,0.223,0.471,0.445,0.718,0.649c0.157,0.131,0.323,0.247,0.485,0.369c0.21,0.16,0.419,0.32,0.634,0.467 c0.172,0.117,0.349,0.222,0.526,0.332c0.217,0.134,0.433,0.267,0.656,0.388c0.181,0.098,0.363,0.189,0.546,0.28 c0.229,0.113,0.458,0.222,0.692,0.322c0.184,0.079,0.367,0.154,0.552,0.225c0.244,0.094,0.49,0.179,0.739,0.26 c0.179,0.059,0.36,0.116,0.54,0.167c0.267,0.075,0.537,0.138,0.808,0.198c0.166,0.037,0.33,0.078,0.498,0.109 c0.314,0.059,0.63,0.1,0.947,0.138c0.126,0.015,0.253,0.037,0.38,0.048c0.579,0.053,1.16,0.066,1.742,0.05 c0.257-0.007,0.515-0.013,0.772-0.034C321.178,372.635,321.369,372.607,321.56,372.585z M299.034,187.835 c-13.109,7.209-27.758,10.968-43.033,10.968c-15.275,0-29.924-3.761-43.033-10.969l43.033-63.355L299.034,187.835z M141.166,293.54 l55.247-81.337c18.027,10.479,38.35,15.969,59.585,15.969s41.559-5.491,59.585-15.969l55.247,81.337v10.463l-49.378,34.876 l-54.41-54.41c-2.753-2.753-6.489-4.301-10.383-4.301h-1.323c-3.894,0-7.63,1.548-10.383,4.301l-54.41,54.41l-49.378-34.876V293.54 H141.166z M141.161,339.964l42.542,30.046c4.38,3.093,9.953,3.483,14.599,1.352c1.551-0.711,2.997-1.7,4.261-2.965l38.751-38.751 v152.497c-36.369-2.338-70.425-13.288-100.154-30.836L141.161,339.964L141.161,339.964z"/></svg></a>
				&nbsp;
				[@meta_product]
			</div>
			<label for="nav-toggle" class="hamburger">
				<span class="top"></span>
				<span class="middle"></span>
				<span class="bottom"></span>
			</label>
		</header>
		<nav>
			<label for="nav-toggle" class="close-btn">&#x2715;</label>
			<div class="menu-container">
				<ul>
					[@menu_list]
				</ul>
			</div>
		</nav>
		<main>
			[@content]
		</main>
		<center>
			<small>
				[@language_list]
				<br />
				<small>
					&copy; [@meta_copyright] <a title='[@label_terms]' href='?action=terms'>[@label_terms]</a>
				</small>
			</small>
		</center>
	</body>
</html>
