module.exports = {
	globDirectory: 'librerias/',
	globPatterns: [
		'**/*.js'
	],
	swDest: 'librerias/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};