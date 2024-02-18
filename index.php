<?php
/*
 *
 * Copyright (C) 2016 - 2023 CyberDay Studio. All right reserved.
 * Author: Nguyen Duy Thanh (segfault.e404)
 *
 */

include('../private/config.php');

// Header
echo "<!DOCTYPE html>";

// Lowest length accepted is 64
function generateRandomString($length = 128) {
	$salt = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()_+{}|:\"<>?-=[]\;',./";
	$randstring = '';
	for ($i = 0; $i < $length; $i++) {
		$randstring .= $salt[random_int(0, strlen($salt) - 1)];
	}

	return $randstring;
}

function createDirectory($directory = '../private/', $directoryName) {
	if (!file_exists($directory . $directoryName)) {
		mkdir($directory . $directoryName, 0777, true);
		return true;
	} else return false;
}

function checkDir($directory = '../private/', $directoryName) {
	if (!file_exists($directory . $directoryName)) return false;
	else return true;
}

// https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function get_client_ip_addr() {
	$ip = 
		getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');

	return $ip;
}
?>

<html lang="en">
    <head>
		<style>
			@import url('https://fonts.cdnfonts.com/css/sf-pro-display');

            body {
                margin: 0;
            }
            .navbar {
                overflow: hidden;
                position: fixed;
                top: 0;
                width: 100%;
				background-color: teal;
            }
            .navbar a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 20px;
            }
            .main {
                margin-top: 120px;
            }

            /* Styles for the loading modal */
			.modal-container {
				display: none;
				justify-content: center;
				align-items: center;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				z-index: 1000; /* Ensure modal is on top of other content */
			}
			/* Styles for the semi-transparent background with blur */
			.modal-background {
				position: absolute;
				background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
				inset: -3px;
				filter: blur(30%);
				backdrop-filter: blur(10px);
			}

			.loader {
				border: 16px solid #f3f3f3;
				border-top: 16px solid #3498db;
				border-radius: 50%;
				width: 120px;
				height: 120px;
				animation: spin 2s linear infinite;
			}
			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}
			.center-screen {
				position: absolute;
				top: 38%;
				left: 47.28%;
				transform: translate(-50%, -50%);
			}
			/* Style for the blinking cursor */
			.blink-cursor {
				display: inline-block;
				width: 0.6em; /* Adjust the width as needed */
				height: 1em;
				background-color: transparent; /* Change cursor color here */
				animation: blink-animation 0.8s step-end infinite;
			}

			@keyframes blink {
				0% { opacity: 0; } /* Invisible */
				50% { opacity: 1; } /* Visible */
				100% { opacity: 0; } /* Invisible */
			}

			.blink-animation {
				animation: blink 0.8s infinite; /* Adjust the animation duration as needed */
			}

			.typing-animation {
				display: inline-block;
			}
        </style>
        <title>Blog - CyberDay Studio</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/public/style/w3.css">
        <link rel="stylesheet" href="/public/style/cyberdaystudio.css">
        <link rel="stylesheet" href="/public/style/fontawesome/fontawesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <style>
            body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
        </style>
		<script src="/public/js/cyberdaystudio.js"></script>
		<script>
            // Function to show the loader
			function showLoader() {
				document.getElementById('modalContainer').style.display = 'flex';
			}

			// Function to hide the loader
			function hideLoader() {
				document.getElementById('modalContainer').style.display = 'none';
			}

			// Function to handle click events on loader links
			function handleLoaderLinkClick(event) {
				// Check if the clicked element or its parent has the loader-link class
				if (event.target.classList.contains('loader-link') || event.target.closest('.loader-link')) {
					// Prevent default link behavior
					event.preventDefault();

					// Show loader before performing the action
					showLoader();

					// Redirect or perform action after a short delay
					setTimeout(function() {
						// Redirect to the href of the clicked link or perform other action

						setTimeout(function() {
							hideLoader();
						}, 1000);

						window.location.href = event.target.href || event.target.closest('.loader-link').href;
					}, 1000); // Adjust the delay as needed
				}
			}

			// Function to handle popstate event (browser back/forward buttons)
			function handlePopState(event) {
				// Check if the loader is currently shown
				if (document.getElementById('modalContainer').style.display === 'flex') {
					// Hide the loader
					hideLoader();
				}
			}

			// Add event listeners
			document.addEventListener('click', handleLoaderLinkClick); // Handle clicks on loader links
			window.addEventListener('popstate', handlePopState); // Handle browser back/forward buttons
        </script>
    </head>
    <body class="w3-light-gray main">
        <div class="modal-container" id="modalContainer">
			<div class="modal-background"></div>
			<div class="loader center-screen"></div>
		</div>
		<script>
			document.getElementById('modalContainer').style.display = 'flex';
		</script>
		<?php
			// Start output buffering
			ob_start();
		?>
		<script>
			setTimeout(function() {
				setTimeout(function() {
					document.getElementById('modalContainer').style.display = 'none';
				}, 2000);
				
				document.getElementById('mainContent').style.display = 'block';
			}, 2000);
		</script>

		<div id="mainContent" class="w3-content" style="max-width: 100%; display: none; ">
            <div style="background-color: teal; " class="navbar" id="navbar">
                <a href="index.php" style="text-decoration: none;">
                    <img src="/public/images/banner_top.png" style="width:60%;">
                </a>
            </div>
			
			<div style="margin-top: -41.8px"></div>

			<div style="width: 100%; background: linear-gradient(to bottom, teal, #333); ">
				<div id="titlebar" style="height: 38vh; font-family: 'SF Pro Display', sans-serif; color: white; ">
					<p id="typing-text" class="typing-animation" style="font-size: 38px; padding-left: 72.55px; margin-top: -0.5px; font-weight: bold; "></p>
					<span id="cursor" class="blink-animation" style="font-size: 38px;">|</span>

					<br style="line-height: 1em; ">

					<p id="description_1" class="typing-animation" style="font-size: 22px; padding-left: 74.55px; margin-top: -16px; padding-right: 72.55px; opacity: 0;">
						Welcome you to visit to our blog and we are happy to see you in here! <br><br>
						We are very proud to present our blog and we hope our blog will become the place where we document 
						everything about our projects,<br>tips, tricks, and stories as we develop a project.<br><br>
						Read and stay tuned latest tips, tricks, and notes that may make your life easier and don't forget to read our stories, 
                        you might find more<br>tips, technical perspectives and other interesting things! Also, we hope you have best experience
						with our new blog!
					</p>
				</div>
			</div>
			<script>
				window.addEventListener('DOMContentLoaded', function() {
					var description = document.getElementById('description_1');
					if (description) {
						setTimeout(function() {
							description.style.transition = 'opacity 1s ease-in-out'; // Add transition property
							description.style.opacity = '1'; // Set initial opacity to 1 for smooth appearance
						}, 6250);
					}
				});
			</script>
			<script>
				const textToType = "We are CyberDay Studios Team!";
				const typingSpeed = 50; // Typing speed (milliseconds per character)

				// Function to simulate typing animation
				function typeText(element, text, speed) {
					let index = 0;
					const typingInterval = setInterval(function() {
						// Append the next character to the element
						element.textContent += text[index];
						index++;

						// Check if we have typed the entire text
						if (index >= text.length) {
							clearInterval(typingInterval); // Stop the typing animation
							setTimeout(function() {
								document.getElementById("cursor").style.display = "none"; // Hide the cursor
							}, 1740);
						}
					}, speed);
				}

				// Start the typing animation when the DOM content is loaded
				document.addEventListener("DOMContentLoaded", function() {
					setTimeout(function() {
						const typingElement = document.getElementById("typing-text");
						typeText(typingElement, textToType, typingSpeed);
					}, 6250);
				});
			</script>
			<script>
				function updateBackgroundColorSmooth() {
					var ticking = false;
					var lastScrollPosition = 0;

					function requestTick() {
						if (!ticking) {
							requestAnimationFrame(updateNavbarBackground);
							ticking = true;
						}
					}

					function updateNavbarBackground() {
						var scrollPosition = window.scrollY;

						// Only update if the scroll position has changed significantly
						if (Math.abs(scrollPosition - lastScrollPosition) > 1) {
							lastScrollPosition = scrollPosition;

							var navbar = document.getElementById('navbar');
							var titlebar = document.getElementById('titlebar');
							var typing_txt = document.getElementById('typing-text');
							var blink_cursor = document.getElementById('cursor');

							var scrollThreshold = window.innerHeight * 0.38; // Adjust 0.38 to change the scroll threshold
							var scrollPercentage = Math.min(scrollPosition / scrollThreshold, 1);

							// Interpolate between teal and #333 based on scroll percentage
							var red = Math.round(0x00 + scrollPercentage * (0x33 - 0x00));
							var green = Math.round(0x80 + scrollPercentage * (0x33 - 0x80));
							var blue = Math.round(0x80 + scrollPercentage * (0x33 - 0x80));

							var opacity = 1 - Math.min(scrollPosition / (window.innerHeight * 0.24), 1);
							var opacity_typing_txt = 1 - Math.min(scrollPosition / (window.innerHeight * 0.04), 1);
							
							titlebar.style.color = `rgba(255, 255, 255, ${opacity})`;

							navbar.style.backgroundColor = `rgb(${red}, ${green}, ${blue})`;
							typing_txt.style.color = `rgba(255, 255, 255, ${opacity_typing_txt})`;
							blink_cursor.style.color = `rgba(255, 255, 255, ${opacity_typing_txt})`;
						}

						ticking = false; // Reset the ticking flag
					}

					return function() {
						if (!ticking) {
							requestTick();
						}
					};
				}

				// Attach scroll event listener
				var smoothBackgroundColorUpdate = updateBackgroundColorSmooth();
				window.addEventListener('scroll', smoothBackgroundColorUpdate);
			</script>

            <!-- Grid -->
            <div class="w3-row">

            <!-- Blog entries -->
            <div class="w3-col l8 s12">
                <!-- All blog listed here-->
                <div class="w3-card-4 w3-margin w3-white">
                    <div class="w3-container">
                        <h3><b>Introduce CyberBlog and the untold stories behind the scenes</b></h3>
                        <h5>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            width="20px" height="20px" viewBox="0 -5 50 50" version="1.1">
                                <g id="surface1">
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;" 
                                d="M 25 25 C 30.15625 25 34.375 20.78125 34.375 15.625 C 34.375 10.46875 30.15625 
                                6.25 25 6.25 C 19.84375 6.25 15.625 10.46875 15.625 15.625 C 15.625 20.78125 19.84375 
                                25 25 25 Z M 25 29.6875 C 18.789062 29.6875 6.25 32.851562 
                                6.25 39.0625 L 6.25 43.75 L 43.75 43.75 L 43.75 39.0625 C 43.75 32.851562 31.210938 
                                29.6875 25 29.6875 Z M 25 29.6875 "/>
                                </g>
                            </svg>
                            segfault.e404
                            <span class="w3-tag" style="margin-left: 5px;">September 6, 2023</span>
                        </h5>
                    </div>
                    <div class="w3-container">
                        <p>Today, September 06 2023, our new blog are inaugurated</p>
                        
                        <!-- Post controller -->
                        <div class="w3-row">
                            <div class="w3-col m8 s12">
                                <p>
                                    <a class="w3-button w3-padding-large w3-white w3-border loader-link" href="/public/posts/introduction-new-cyberblog/">
                                        <b>READ THIS ARTICLE »</b>
                                    </a>
                                </p>
                            </div>
                            <!-- END BUTTON -->

                            <div class="w3-col m4 w3-hide-small">
                                <p>
                                    <span class="w3-padding-large w3-right">
                                        <b>Comments  </b>
                                        <span class="w3-tag">0</span>
                                    </span>
                                </p>
                            </div>
                            <!-- END COMMENT -->
                        </div>
                        <!-- END POST CONTROLLER -->
                    </div>
                    <!-- END DESCRIPTION -->
                </div>
                <hr>
                <!-- END BLOG POST PREVIEW CARD -->

				<!-- Articles list will show here -->
				<div class="w3-card-4 w3-margin w3-white">
                    <div class="w3-container">
                        <h3><b>PLACE HOLDER</b></h3>
                        <h5>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            width="20px" height="20px" viewBox="0 -5 50 50" version="1.1">
                                <g id="surface1">
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;" 
                                d="M 25 25 C 30.15625 25 34.375 20.78125 34.375 15.625 C 34.375 10.46875 30.15625 
                                6.25 25 6.25 C 19.84375 6.25 15.625 10.46875 15.625 15.625 C 15.625 20.78125 19.84375 
                                25 25 25 Z M 25 29.6875 C 18.789062 29.6875 6.25 32.851562 
                                6.25 39.0625 L 6.25 43.75 L 43.75 43.75 L 43.75 39.0625 C 43.75 32.851562 31.210938 
                                29.6875 25 29.6875 Z M 25 29.6875 "/>
                                </g>
                            </svg>
                            segfault.e404
                            <span class="w3-tag" style="margin-left: 5px;">September 5, 2023</span>
                        </h5>
                    </div>
                    <div class="w3-container">
                        <p>This section only for place holder and test. Nothing article here. Please do not click <b>READ MORE</b> button</p>
                        
                        <!-- Post controller -->
                        <div class="w3-row">
                            <div class="w3-col m8 s12">
                                <p>
                                    <a id="readMoreLink" class="w3-button w3-padding-large w3-white w3-border loader-link" href="viewpost.php?id=050923">
                                        <b>READ THIS ARTICLE »</b>
                                    </a>
                                </p>
                            </div>
                            <!-- END BUTTON -->

                            <div class="w3-col m4 w3-hide-small">
                                <p>
                                    <span class="w3-padding-large w3-right">
                                        <b>Comments  </b>
                                        <span class="w3-tag">0</span>
                                    </span>
                                </p>
                            </div>
                            <!-- END COMMENT -->
                        </div>
                        <!-- END POST CONTROLLER -->
                    </div>
                    <!-- END DESCRIPTION -->
                </div>
                <hr>
                <!-- END BLOG POST PREVIEW CARD -->
            </div>
            <!-- END BLOG ENTRIES -->

            <!-- Introduction menu -->
            <div class="w3-col l4">
                <!-- Posts -->
                <div class="w3-card w3-margin">
                    <div class="w3-container w3-padding">
                    <h4>Popular Posts</h4>
                    </div>
                    <ul class="w3-ul w3-hoverable w3-white">
                        <p style="margin-left: 16px;">Nothing post right now! Please try again later</p>
                    </ul>
                </div>
                <hr>
                
                <!-- Labels / tags -->
                <div class="w3-card w3-margin">
                    <div class="w3-container w3-padding">
                    <h4>Tags</h4>
                    </div>
                    <div class="w3-container w3-white">
                    <p><span class="w3-tag w3-black w3-margin-bottom">announcements</span></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
	<footer class="w3-container w3-padding-48 w3-margin-top">
        <div class="w3-center w3-small">
            <hr />
            <a href="index.php" style="text-decoration: none;">
                <img src="/public/images/banner.png" style="width: 280px;">
            </a>
            <br />
            <a href="https://www.facebook.com/profile.php?id=100086485856895" target="_blank" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px">
                    <path fill="#039be5" d="M24 5A19 19 0 1 0 24 43A19 19 0 1 0 24 5Z"/>
                    <path fill="#fff" d="M26.572,29.036h4.917l0.772-4.995h-5.69v-2.73c0-2.075,
                    0.678-3.915,2.619-3.915h3.119v-4.359c-0.548-0.074-1.707-0.236-3.897-0.236c-4.573,
                    0-7.254,2.415-7.254,7.917v3.323h-4.701v4.995h4.701v13.729C22.089,42.905,23.032,43,24,
                    43c0.875,0,1.729-0.08,2.572-0.194V29.036z"/>
                </svg>
            </a>
            <a href="https://twitter.com/cyberday04" target="_blank" style="text-decoration: none; margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px">
                    <path fill="#03A9F4" d="M42,12.429c-1.323,0.586-2.746,0.977-4.247,
                    1.162c1.526-0.906,2.7-2.351,3.251-4.058c-1.428,0.837-3.01,
                    1.452-4.693,1.776C34.967,9.884,33.05,9,30.926,9c-4.08,0-7.387,
                    3.278-7.387,7.32c0,0.572,0.067,1.129,0.193,
                    1.67c-6.138-0.308-11.582-3.226-15.224-7.654c-0.64,
                    1.082-1,2.349-1,3.686c0,2.541,
                    1.301,4.778,3.285,6.096c-1.211-0.037-2.351-0.374-3.349-0.914c0,0.022,
                    0,0.055,0,0.086c0,3.551,2.547,6.508,5.923,7.181c-0.617,0.169-1.269,
                    0.263-1.941,0.263c-0.477,0-0.942-0.054-1.392-0.135c0.94,
                    2.902,3.667,5.023,6.898,5.086c-2.528,1.96-5.712,
                    3.134-9.174,3.134c-0.598,0-1.183-0.034-1.761-0.104C9.268,
                    36.786,13.152,38,17.321,38c13.585,0,21.017-11.156,
                    21.017-20.834c0-0.317-0.01-0.633-0.025-0.945C39.763,15.197,41.013,
                    13.905,42,12.429"/>
                </svg>
            </a>
            <a href="https://www.youtube.com/@CyberDayStudio" target="_blank" style="text-decoration: none; margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px">
                    <path fill="#FF3D00" d="M43.2,33.9c-0.4,2.1-2.1,3.7-4.2,4c-3.3,0.5-8.8,1.1-15,1.1c-6.1,
                    0-11.6-0.6-15-1.1c-2.1-0.3-3.8-1.9-4.2-4C4.4,31.6,4,28.2,4,24c0-4.2,0.4-7.6,0.8-9.9c0.4-2.1,
                    2.1-3.7,4.2-4C12.3,9.6,17.8,9,24,9c6.2,0,11.6,0.6,15,1.1c2.1,0.3,3.8,1.9,4.2,4c0.4,2.3,0.9,
                    5.7,0.9,9.9C44,28.2,43.6,31.6,43.2,33.9z"/>
                    <path fill="#FFF" d="M20 31L20 17 32 24z"/>
                </svg>
            </a>
            <a href="https://t.me/cyberdaystudio" target="_blank" style="text-decoration: none; margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48" width="48px" height="48px">
                    <circle style="fill:#29B6F6;" cx="24" cy="24" r="20"/>
                    <path style="fill:#FFFFFF;" d="M33.95,15l-3.746,19.126c0,0-0.161,0.874-1.245,0.874c-0.576,
                    0-0.873-0.274-0.873-0.274l-8.114-6.733  l-3.97-2.001l-5.095-1.355c0,
                    0-0.907-0.262-0.907-1.012c0-0.625,0.933-0.923,0.933-0.923l21.316-8.468  c-0.001-0.001,
                    0.651-0.235,1.126-0.234C33.667,14,34,14.125,34,14.5C34,14.75,33.95,15,33.95,15z"/>
                    <path style="fill:#B0BEC5;" d="M23,30.505l-3.426,3.374c0,0-0.149,0.115-0.348,
                    0.12c-0.069,0.002-0.143-0.009-0.219-0.043  l0.964-5.965L23,30.505z"/>
                    <path style="fill:#CFD8DC;" d="M29.897,18.196c-0.169-0.22-0.481-0.26-0.701-0.093L16,
                    26c0,0,2.106,5.892,2.427,6.912  c0.322,1.021,0.58,1.045,0.58,1.045l0.964-5.965l9.832-9.096C30.023,
                    18.729,30.064,18.416,29.897,18.196z"/>
                </svg>
            </a>
            <a href="https://github.com/Duy-Thanh/" target="_blank" style="text-decoration: none; margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px">
                    <linearGradient id="rL2wppHyxHVbobwndsT6Ca" x1="4" x2="44" y1="23.508" y2="23.508" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#4c4c4c"/>
                        <stop offset="1" stop-color="#343434"/>
                    </linearGradient>
                    <path fill="url(#rL2wppHyxHVbobwndsT6Ca)" d="M24,4C12.954,4,4,12.954,4,24c0,8.887,5.801,16.411,
                    13.82,19.016h12.36	C38.199,40.411,44,32.887,44,24C44,12.954,35.046,4,24,4z"/>
                    <path d="M30.01,41.996L30,36.198c0-0.939-0.22-1.856-0.642-2.687c5.641-1.133,8.386-4.468,
                    8.386-10.177	c0-2.255-0.665-4.246-1.976-5.92c0.1-0.317,0.174-0.645,
                    0.22-0.981c0.188-1.369-0.023-2.264-0.193-2.984l-0.027-0.116	c-0.186-0.796-0.409-1.364-0.418-1.388l-0.111-0.282l-0.111-0.282l-0.302-0.032l-0.303-0.032c0,
                    0-0.199-0.021-0.501-0.021	c-0.419,0-1.04,0.042-1.627,0.241l-0.196,0.066c-0.74,0.249-1.439,
                    0.485-2.417,1.069c-0.286,0.171-0.599,0.366-0.934,0.584	C27.334,12.881,25.705,12.69,24,
                    12.69c-1.722,0-3.365,0.192-4.889,0.571c-0.339-0.22-0.654-0.417-0.942-0.589	c-0.978-0.584-1.677-0.819-2.417-1.069l-0.196-0.066c-0.585-0.199-1.207-0.241-1.626-0.241c-0.302,
                    0-0.501,0.021-0.501,0.021	l-0.302,0.032l-0.3,0.031l-0.112,0.281l-0.113,0.283c-0.01,0.026-0.233,
                    0.594-0.419,1.391l-0.027,0.115	c-0.17,0.719-0.381,1.615-0.193,2.983c0.048,0.346,0.125,0.685,
                    0.23,1.011c-1.285,1.666-1.936,3.646-1.936,5.89	c0,5.695,2.748,9.028,8.397,10.17c-0.194,
                    0.388-0.345,0.798-0.452,1.224c-0.197,0.067-0.378,0.112-0.538,0.137	c-0.238,0.036-0.487,
                    0.054-0.739,0.054c-0.686,0-1.225-0.134-1.435-0.259c-0.313-0.186-0.872-0.727-1.414-1.518	c-0.463-0.675-1.185-1.558-1.992-1.927c-0.698-0.319-1.437-0.502-2.029-0.502c-0.138,
                    0-0.265,0.01-0.376,0.028	c-0.517,0.082-0.949,0.366-1.184,0.78c-0.203,0.357-0.235,0.773-0.088,
                    1.141c0.219,0.548,0.851,0.985,1.343,1.255	c0.242,0.133,0.765,0.619,1.07,1.109c0.229,0.368,
                    0.335,0.63,0.482,0.992c0.087,0.215,0.183,0.449,0.313,0.732	c0.47,1.022,1.937,1.924,2.103,
                    2.023c0.806,0.483,2.161,0.638,3.157,0.683l0.123,0.003c0,0,0.001,0,0.001,0	c0.24,0,0.57-0.023,
                    1.004-0.071v2.613c0.002,0.529-0.537,0.649-1.25,0.638l0.547,0.184C19.395,43.572,21.645,44,24,44	c2.355,0,4.605-0.428,
                    6.703-1.176l0.703-0.262C30.695,42.538,30.016,42.422,30.01,41.996z" opacity=".05"/>
                    <path d="M30.781,42.797c-0.406,0.047-1.281-0.109-1.281-0.795v-5.804c0-1.094-0.328-2.151-0.936-3.052	c5.915-0.957,
                    8.679-4.093,8.679-9.812c0-2.237-0.686-4.194-2.039-5.822c0.137-0.365,0.233-0.75,
                    0.288-1.147	c0.175-1.276-0.016-2.086-0.184-2.801l-0.027-0.116c-0.178-0.761-0.388-1.297-0.397-1.319l-0.111-0.282l-0.303-0.032	c0,
                    0-0.178-0.019-0.449-0.019c-0.381,0-0.944,0.037-1.466,0.215l-0.196,0.066c-0.714,0.241-1.389,
                    0.468-2.321,1.024	c-0.332,0.198-0.702,0.431-1.101,0.694C27.404,13.394,25.745,13.19,
                    24,13.19c-1.762,0-3.435,0.205-4.979,0.61	c-0.403-0.265-0.775-0.499-1.109-0.699c-0.932-0.556-1.607-0.784-2.321-1.024l-0.196-0.066c-0.521-0.177-1.085-0.215-1.466-0.215	c-0.271,
                    0-0.449,0.019-0.449,0.019l-0.302,0.032l-0.113,0.283c-0.009,0.022-0.219,0.558-0.397,1.319l-0.027,
                    0.116	c-0.169,0.715-0.36,1.524-0.184,2.8c0.056,0.407,0.156,0.801,0.298,1.174c-1.327,1.62-1.999,
                    3.567-1.999,5.795	c0,5.703,2.766,8.838,8.686,9.806c-0.395,0.59-0.671,1.255-0.813,1.964c-0.33,
                    0.13-0.629,0.216-0.891,0.256	c-0.263,0.04-0.537,0.06-0.814,0.06c-0.69,
                    0-1.353-0.129-1.69-0.329c-0.44-0.261-1.057-0.914-1.572-1.665	c-0.35-0.51-1.047-1.417-1.788-1.755c-0.635-0.29-1.298-0.457-1.821-0.457c-0.11,
                    0-0.21,0.008-0.298,0.022	c-0.366,0.058-0.668,0.252-0.828,0.534c-0.128,0.224-0.149,
                    0.483-0.059,0.708c0.179,0.448,0.842,0.85,1.119,1.002	c0.335,0.184,0.919,0.744,1.254,
                    1.284c0.251,0.404,0.37,0.697,0.521,1.067c0.085,0.209,0.178,0.437,0.304,0.712	c0.331,0.719,
                    1.353,1.472,1.905,1.803c0.754,0.452,2.154,0.578,2.922,0.612l0.111,0.002c0.299,0,0.8-0.045,
                    1.495-0.135v3.177	c0,0.779-0.991,0.81-1.234,0.81c-0.031,0,0.503,0.184,0.503,0.184C19.731,
                    43.64,21.822,44,24,44c2.178,0,4.269-0.36,6.231-1.003	C30.231,42.997,30.812,42.793,30.781,
                    42.797z" opacity=".07"/>
                    <path fill="#fff" d="M36.744,23.334c0-2.31-0.782-4.226-2.117-5.728c0.145-0.325,0.296-0.761,
                    0.371-1.309	c0.172-1.25-0.031-2-0.203-2.734s-0.375-1.25-0.375-1.25s-0.922-0.094-1.703,
                    0.172s-1.453,0.469-2.422,1.047	c-0.453,0.27-0.909,0.566-1.27,0.806C27.482,13.91,25.785,
                    13.69,24,13.69c-1.801,0-3.513,0.221-5.067,0.652	c-0.362-0.241-0.821-0.539-1.277-0.811c-0.969-0.578-1.641-0.781-2.422-1.047s-1.703-0.172-1.703-0.172s-0.203,
                    0.516-0.375,1.25	s-0.375,1.484-0.203,2.734c0.077,0.562,0.233,1.006,0.382,1.333c-1.31,
                    1.493-2.078,3.397-2.078,5.704	c0,5.983,3.232,8.714,9.121,9.435c-0.687,0.726-1.148,1.656-1.303,
                    2.691c-0.387,0.17-0.833,0.33-1.262,0.394	c-1.104,0.167-2.271,0-2.833-0.333s-1.229-1.083-1.729-1.813c-0.422-0.616-1.031-1.331-1.583-1.583	c-0.729-0.333-1.438-0.458-1.833-0.396c-0.396,
                    0.063-0.583,0.354-0.5,0.563c0.083,0.208,0.479,0.521,0.896,0.75	c0.417,0.229,1.063,0.854,1.438,
                    1.458c0.418,0.674,0.5,1.063,0.854,1.833c0.249,0.542,1.101,1.219,1.708,1.583	c0.521,0.313,1.562,
                    0.491,2.688,0.542c0.389,0.018,1.308-0.096,2.083-0.206v3.75c0,0.639-0.585,1.125-1.191,
                    1.013	C19.756,43.668,21.833,44,24,44c2.166,0,4.243-0.332,6.19-0.984C29.585,43.127,29,42.641,
                    29,42.002v-5.804	c0-1.329-0.527-2.53-1.373-3.425C33.473,32.071,36.744,29.405,36.744,
                    23.334z M11.239,32.727c-0.154-0.079-0.237-0.225-0.185-0.328	c0.052-0.103,0.22-0.122,
                    0.374-0.043c0.154,0.079,0.237,0.225,0.185,0.328S11.393,32.806,11.239,32.727z M12.451,
                    33.482	c-0.081,0.088-0.255,0.06-0.389-0.062s-0.177-0.293-0.096-0.381c0.081-0.088,0.255-0.06,
                    0.389,0.062S12.532,33.394,12.451,33.482z M13.205,34.732c-0.102,0.072-0.275,
                    0.005-0.386-0.15s-0.118-0.34-0.016-0.412s0.275-0.005,0.386,0.15	C13.299,34.475,13.307,34.66,
                    13.205,34.732z M14.288,35.673c-0.069,0.112-0.265,0.117-0.437,0.012s-0.256-0.281-0.187-0.393	c0.069-0.112,
                    0.265-0.117,0.437-0.012S14.357,35.561,14.288,35.673z M15.312,36.594c-0.213-0.026-0.371-0.159-0.353-0.297	c0.017-0.138,
                    0.204-0.228,0.416-0.202c0.213,0.026,0.371,0.159,0.353,0.297C15.711,36.529,15.525,36.62,15.312,
                    36.594z M16.963,36.833c-0.227-0.013-0.404-0.143-0.395-0.289c0.009-0.146,0.2-0.255,
                    0.427-0.242c0.227,0.013,0.404,0.143,0.395,0.289	C17.381,36.738,17.19,36.846,16.963,36.833z M18.521,36.677c-0.242,
                    0-0.438-0.126-0.438-0.281s0.196-0.281,0.438-0.281	c0.242,0,0.438,0.126,0.438,0.281S18.762,
                    36.677,18.521,36.677z"/>
                </svg>
            </a>
            <br><br>
            <p id="copyright"></p>
        </div>
    </footer>
</html>