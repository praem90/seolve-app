const { useState } = require('react');
const React = require('react');
import axios from 'axios';

const AddCompany = (props) => {
	const [name, setName] = useState('');

	const add = () => {
		axios.post('/api/company/', {name: name})
			.then(res => {
				props.onAdd && props.onAdd(res.data);
			})
			.catch(f => alert('Company name already exists'));
	}

	return (
		<div className="flex flex-row items-center justify-end mb-6">
  			<div className="">
    			<div className="relative text-gray-300 focus-within:text-gray-400">
    				<input onChange={e => setName(e.target.value)} name="company_name" className="block w-full px-3 py-2 border border-transparent rounded-md leading-5 bg-gray-300 bg-opacity-25 text-gray-600 placeholder-gray-300 focus:outline-none focus:bg-white focus:ring-0 focus:placeholder-gray-400 focus:text-gray-900 sm:text-sm" placeholder="Enter your company name" />
    			</div>
  			</div>

			<button onClick={add} type="button" className="inline-flex items-center ml-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  				Add
			</button>
		</div>
	);
}

export default AddCompany
