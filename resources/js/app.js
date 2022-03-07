require('./bootstrap');

const React = require('react');
const ReactDom = require('react-dom');

import Dashboard from './components/dashboard';

const App = () => (
	<Dashboard />
)

ReactDom.render(<App />, document.getElementById('Dashboard'))
