module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{css,eot,svg,ttf,woff,woff2,js,otf,jpg,png,pdf,ico,php}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};