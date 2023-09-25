<?php
/*
 *
 * Copyright (C) 2016 - 2023 CyberDay Studio. All right reserved.
 * Author: Nguyen Duy Thanh (segfault.e404)
 *
 */

// Get current directory
$current_dir = getcwd();

// Get include path too
$include_path = get_include_path();

// Set include directory for library!
$include_lib = "";

// HTML code here!
echo "<html>\r\n";
echo "<head>\r\n";

echo "<link rel=\"stylesheet\" href=\"../../public/style/styles/base16/3024.css\">\r\n";

echo "<style>\r\n";
echo "body { display: block; } .alert { display: none; }\r\n";
echo "</style>\r\n";

// Style
echo "<style>\r\n";
echo "@import url(https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic);\r\n";
echo "code { display: block; font-family: Consolas, Monaco, monospace; font-size: 10pt; overflow-x: auto; padding: 0.5em; scrollbar-width: thin; -moz-tab-size: 4; tab-size: 4; }\r";
echo " .pre { margin: 0; }\r";
echo " pre code.hljs { padding: 16px 30px 20px; }\r";
echo "</style>\r\n";

// JavaScript here
echo "<script src=\"../../public/js/alert.js\"></script>\r\n";
echo "<script src=\"../../public/js/highlight.min.js\"></script>\r\n";
echo "<script src=\"../../public/js/codeblock.js\"></script>\r\n";
echo "</head>\r\n";
echo "<body>\r\n";

function check_lib_exists($libName = "", $current_dir = "", $include_path = "", $include_lib = "") {
	global $include_lib;
	if (!file_exists($current_dir . "/" . $libName)) {
		if (!file_exists($include_path . "/" . $libName)) {
			echo "<h1>Server error</h1>\r\n";
			echo "<hr class=\"solid\">\r\n";
			echo "<p>Something went wrong with the server! I'm sorry about that and I will fix them as soon as possible<p>\r\n";
			trigger_error("$libName not found in $current_dir or $include_path");

			return false;
		} else {
			trigger_error("$libName found at $include_path!");

			$include_lib = $include_path . "/" . $libName;

			return true;
		}
	} else {
		if (file_exists($include_path . PATH_SEPARATOR . $libName)) {
			trigger_error("$libName found in both directories: $current_dir and $include_path");
			trigger_error("Consider to use $libName in $current_dir!");

			$include_lib = $current_dir . "/" . $libName;

			return true;
		} else {
			$include_lib = $current_dir . "/" . $libName;

			return true;
		}
	}
}

// Include phpseclib
if (!check_lib_exists("phpseclib", $current_dir, $include_path, $include_lib)) {
	if ($include_lib == NULL) {
		$include_lib = "Not found or library directory is unreachable";
	}

	echo "<br/>\r\n";
	echo "<hr class=\"solid\">\r\n";

	echo "<div id=\"technical_section\">\r\n";
	echo "<div id=\"technical_title\">\r\n";
	echo "<p>Technical Information: </p>";
	echo "</div>\r\n";	// end of technical_section
	
	echo "<div id=\"technical_button\">\r\n";
	echo "<button onclick=\"show_alert_div()\">Show</button>\r\n";
	echo "<button onclick=\"hide_alert_div()\">Hide</button>\r\n";
	echo "</div>\r\n";
	echo "</div>\r\n";

	echo "<div id=\"alert_div\" class=\"alert\">\r\n";
	echo "<br/>\r\n";
	echo "<pre>\r\n";
	echo "<code class=\"language\">\r\n";
	echo "Success: FALSE\r\n";
	echo "Current working directory: $current_dir\r\n";
	echo "Include path: $include_path\r\n";
	echo "Library name have an error occurred: $libname\r\n";
	echo "Include directory to $libname: $include_lib\r\n";
	echo "</code>\r\n";
	echo "</pre>\r\n";
	echo "</div>\r\n";
} else {
	set_include_path($include_lib);
	include('bootstrap.php');
	include('Crypt/RSA.php');
}

echo "</body>\r\n";
echo "</html>\r\n";

?>
