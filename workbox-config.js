module.exports = {
	globDirectory: 'view/',
	globPatterns: [
		'**/*.{php,css,jpg,png,js,html,eot,svg,ttf,woff,woff2,otf,txt,less,scss,ico,json,pdf}'
	],
	swDest: 'view/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};