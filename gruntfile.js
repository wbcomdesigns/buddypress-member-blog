'use strict';
module.exports = function (grunt) {

	// load all grunt tasks matching the `grunt-*` pattern
	// Ref. https://npmjs.org/package/load-grunt-tasks
	require('load-grunt-tasks')(grunt);
	grunt.initConfig({

		// Check text domain
		checktextdomain: {
			options: {
				text_domain: ['buddypress-member-blog'], // Specify allowed domain(s)
				keywords: [ // List keyword specifications
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			target: {
				files: [{
					src: [
						'*.php',
						'**/*.php',
						'!node_modules/**',
						'!options/framework/**',
						'!plugin-update-checker/**',
						'!tests/**'
					], // all php
					expand: true
				}]
			}
		},
		// Task for CSS minification
		cssmin: {
			public: {
				files: [{
					expand: true,
					cwd: 'public/css/', // Source directory for frontend CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all frontend CSS files except already minified ones
					dest: 'public/css/', // Destination directory for minified frontend CSS
					ext: '.min.css', // Extension for minified files
				},
				{
					expand: true,
					cwd: 'public/css-rtl/', // Source directory for RTL CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all .css files except already minified ones
					dest: 'public/css-rtl/', // Destination directory for minified CSS
					ext: '.min.css' // Output file extension
				}],
			},
			admin: {
				files: [{
					expand: true,
					cwd: 'admin/css/', // Source directory for admin CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all admin CSS files except already minified ones
					dest: 'admin/css/', // Destination directory for minified admin CSS
					ext: '.min.css', // Extension for minified files
				},
				{
					expand: true,
					cwd: 'admin/css-rtl/', // Source directory for RTL CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all .css files except already minified ones
					dest: 'admin/css-rtl/', // Destination directory for minified CSS
					ext: '.min.css' // Output file extension
				}],
			},
			wbcom: {
				files: [{
					expand: true,
					cwd: 'admin/wbcom/assets/css/', // Source directory for admin CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all admin CSS files except already minified ones
					dest: 'admin/wbcom/assets/css/', // Destination directory for minified admin CSS
					ext: '.min.css', // Extension for minified files
				},
				{
					expand: true,
					cwd: 'admin/wbcom/assets/css-rtl/', // Source directory for RTL CSS files
					src: ['*.css', '!*.min.css', '!vendor/*.css'], // Minify all .css files except already minified ones
					dest: 'admin/wbcom/assets/css-rtl/', // Destination directory for minified CSS
					ext: '.min.css' // Output file extension
				}],
			},
		},
		// Task for JavaScript minification
		uglify: {
			public: {
				options: {
					mangle: false, // Prevents variable name mangling
				},
				files: [{
					expand: true,
					cwd: 'public/js/', // Source directory for frontend JS files
					src: ['*.js', '!*.min.js', '!vendor/*.js'], // Minify all frontend JS files except already minified ones
					dest: 'public/js/', // Destination directory for minified frontend JS
					ext: '.min.js', // Extension for minified files
				}],
			},
			admin: {
				options: {
					mangle: false, // Prevents variable name mangling
				},
				files: [{
					expand: true,
					cwd: 'admin/js/', // Source directory for admin JS files
					src: ['*.js', '!*.min.js', '!vendor/*.js'], // Minify all admin JS files except already minified ones
					dest: 'admin/js/', // Destination directory for minified admin JS
					ext: '.min.js', // Extension for minified files
				}],
			},
			wbcom: {
				options: {
					mangle: false, // Prevents variable name mangling
				},
				files: [{
					expand: true,
					cwd: 'admin/wbcom/assets/js/', // Source directory for wbcom admin JS files
					src: ['*.js', '!*.min.js', '!vendor/*.js'], // Minify all admin JS files except already minified ones
					dest: 'admin/wbcom/assets/js/', // Destination directory for minified wbcom admin JS
					ext: '.min.js', // Extension for minified files
				}],
			},
		},
		// Task for watching file changes
		watch: {
			css: {
				files: ['public/css/*.css', '!public/css/*.min.css'], // Watch for changes in frontend CSS files
				tasks: ['cssmin:public'], // Run frontend CSS minification task
			},
			adminCss: {
				files: ['admin/css/*.css', '!admin/css/*.min.css'], // Watch for changes in admin CSS files
				tasks: ['cssmin:admin'], // Run admin CSS minification task
			},
			js: {
				files: ['public/js/*.js', '!public/js/*.min.js'], // Watch for changes in frontend JS files
				tasks: ['uglify:public'], // Run frontend JS minification task
			},
			adminJs: {
				files: ['admin/js/*.js', '!admin/js/*.min.js'], // Watch for changes in admin JS files
				tasks: ['uglify:admin'], // Run admin JS minification task
			},
			php: {
				files: ['**/*.php'], // Watch for changes in PHP files
				tasks: ['checktextdomain'], // Run text domain check
			},
		},
		// Task for generating RTL CSS
		rtlcss: {
			myTask: {
				options: {
					// Generate source maps
					map: { inline: false },
					// RTL CSS options
					opts: {
						clean: false
					},
					// RTL CSS plugins
					plugins: [],
					// Save unmodified files
					saveUnmodified: true,
				},
				files: [
					{
						expand: true,
						cwd: 'public/css', // Source directory for public CSS
						src: ['*.css', '!**/*.min.css', '!vendor/**/*.css'], // Source files, excluding vendor CSS
						dest: 'public/css-rtl', // Destination directory for public RTL CSS
						flatten: true // Prevents creating subdirectories
					},
					{
						expand: true,
						cwd: 'admin/css', // Source directory for admin CSS
						src: ['*.css', '!**/*.min.css', '!vendor/**/*.css'], // Source files, excluding vendor CSS
						dest: 'admin/css-rtl', // Destination directory for admin RTL CSS
						flatten: true // Prevents creating subdirectories
					},
					{
						expand: true,
						cwd: 'admin/wbcom/assets/css', // Source directory for wbcom admin CSS
						src: ['*.css', '!**/*.min.css', '!vendor/**/*.css'], // Source files, excluding vendor CSS
						dest: 'admin/wbcom/assets/css-rtl', // Destination directory for wbcom admin RTL CSS
						flatten: true // Prevents creating subdirectories
					}
				]
			}
		},
		// make po files
		shell: {
			wpcli: {
				command: 'wp i18n make-pot . languages/buddypress-member-blog.pot',
			}
		}
	});

	// Load the plugins
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-checktextdomain');
	grunt.loadNpmTasks('grunt-rtlcss');
	grunt.loadNpmTasks('grunt-shell');

	// Register default tasks
	grunt.registerTask('default', ['cssmin', 'uglify', 'checktextdomain', 'rtlcss', 'shell', 'watch']);
};
