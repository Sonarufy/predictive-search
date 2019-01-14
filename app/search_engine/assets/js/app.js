/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

// loads the jquery package from node_modules
var easyAutocomplete = require('easy-autocomplete');


require('../css/app.css');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
