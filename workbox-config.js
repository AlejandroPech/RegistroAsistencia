module.exports = {
	globDirectory: 'view/',
	globPatterns: [
		'**/*.{css,jpg,png,js,html,txt,eot,svg,ttf,woff,woff2,ico,json,otf,pdf}'
	],
	swDest: 'view/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};