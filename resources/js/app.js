require('./bootstrap');

const React = require('react');
const ReactDom = require('react-dom');

import Dashboard from './components/dashboard';
import {CompanyDashboard} from './components/company/dashboard';

let root = document.getElementById('Dashboard');
let App = () => (
    <Dashboard />
);

if (! root) {
    root = document.getElementById('company_dashboard');
    App = () => (
        <CompanyDashboard />
    );
}
console.log(root);

root && ReactDom.render(<App />, root)
