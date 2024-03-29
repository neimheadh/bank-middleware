/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// Import Prism
import 'prismjs/prism';
import 'prismjs/components/prism-yaml';
import './plugins/prism-live';

// start the Stimulus application
import './bootstrap';
