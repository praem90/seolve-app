import React, {useEffect, useState} from 'react';
import AddCompany from './create';
import AddPost from '../post/add';
import SearchBar from '../search';
import Pagination from '../pagination';
import Empty from '../empty';
import axios from 'axios';
import { CalendarIcon, ChevronRightIcon, PlusCircleIcon } from '@heroicons/react/solid'

export const CompanyDashboard = () => {
	const [company, setCompany] = useState({accounts: []});
	const [selected, setSelected] = useState(false);


	const fetchCompany = () => {
		const params = new URLSearchParams();

		const route_params = location.pathname.split('/');

		if (route_params.length < 2 ) {
			return;
		}

		params['id[]'] = route_params[1];

		axios.get('/api/company', {params}).then(res => {
			setCompany(res.data.data[0]);
		});
	}

	useEffect(() => fetchCompany(), []);

	return (
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8" >
        	<h2 id="offices-heading" className="text-3xl font-extrabold text-warm-gray-900 mb-2">
        		{company.name}
            </h2>
        	<div className="flex flex-row justify-between">
				<a href={"/oauth/" + company.id + '/facebook/redirect'} className="inline-flex items-center ml-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  					Add Account
				</a>

				<button
					onClick={() => setSelected(true)}
					className="inline-flex items-center ml-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  					Add Post
				</button>
			</div>
			{selected ? <AddPost onClose={() => setSelected(false)} selected={selected} company={company} key={company.id}/> : ''}
			<div className="bg-white mt-4 shadow overflow-hidden sm:rounded-md">
      	  	  <ul role="list" className="divide-y divide-gray-200">
        		{company.accounts.map((account) => (
        			<AccountItem key={account.id} account={account} company={company}/>
        		))}
      		  </ul>
      		</div>
		</div>
	)
}

const AccountItem = (props) => {
	return (
        <li key={props.account.id} className="py-4 flex items-center">
          <img className="h-10 w-10 rounded-full ml-4" src={props.account.logo} alt="" />
          <div className="ml-3">
            <p className="text-sm font-medium text-gray-900">{props.account.name}</p>
            <p className="text-sm text-gray-500"><time dateTime={props.account.created_at}>{props.account.created_at_display}</time></p>
          </div>
        </li>
  )
}


