import React, {useEffect, useState} from 'react';
import AddCompany from './create';
import SearchBar from '../search';
import Pagination from '../pagination';
import Empty from '../empty';
import axios from 'axios';
import { CalendarIcon, ChevronRightIcon, PlusCircleIcon } from '@heroicons/react/solid'

export const CompanyList = () => {
	const [query, setQuery] = useState('');
	const [force, setForce] = useState(false);
	const [companies, setCompanies] = useState([]);

	const [info, setInfo] = useState({
		current_page: 1,
		from: 1,
		to: 0,
		total: 0,
	});

	const fetchProfiles = () => {
		const params = new URLSearchParams();

		if (query) {
			params.set('query', query);
		}
		params.set('page', info.current_page);

		if (force) {
			params.set('force', force);
		}

		axios.get('/api/company', {params}).then(res => {
			const {data, ...info} = res.data;
			setCompanies(data);
			info.to = info.to || 0;
			info.from = info.from || 0;
			setInfo(info);
		});
	}

	const onChange = e => {
		setInfo({...info, ...{current_page: 1}});
		setQuery(e.target.value);
	}

	const onForceChange = e => {
		setForce(e.target.checked);
	}

	const next = () => {
		if (info.total <= info.to) {
			return;
		}

		info.current_page++;
		fetchProfiles()
	}

	const prev = () => {
		if (info.from === 0) {
			return;
		}

		info.current_page--;
		fetchProfiles()
	}

	useEffect(() => fetchProfiles(), [query])

	return (
        <div className="" >
        	<h2 id="offices-heading" className="text-3xl font-extrabold text-warm-gray-900 mb-2">
        		Your Companies
            </h2>
        	<div className="flex flex-row justify-between">
            	<AddCompany onAdd={fetchProfiles} />
				<SearchBar onChange={_.debounce(onChange, 500)} onForce={onForceChange} searchOnly />
			</div>
			<div className="bg-white shadow overflow-hidden sm:rounded-md">
      	  	  <ul role="list" className="divide-y divide-gray-200">
        		{companies.map((company) => (
        			<CompanyItem key={company.id} company={company}/>
        		))}
      		  </ul>
      		</div>
			{companies.length === 0 ? <Empty title="No profiles found"/> : <Pagination prev={prev} next={next} info={info} />}
		</div>
	)
}

const CompanyItem = (props) => {
	return (
          <li key={props.company.id}>
            <a href="#" className="block hover:bg-gray-50">
              <div className="px-4 py-4 flex items-center sm:px-6">
                <div className="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                  <div className="truncate">
                    <div className="flex text-sm">
                      <p className="font-medium text-indigo-600 truncate">{props.company.name}</p>
                      <p className="ml-1 flex-shrink-0 font-normal text-gray-500">{props.company.sub_title}</p>
                    </div>
                    <div className="mt-2 flex">
                      <div className="flex items-center text-sm text-gray-500">
                        <CalendarIcon className="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                        <p>
                          <time dateTime={props.company.created_at}>{props.company.created_at_display}</time>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div className="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                    <div className="flex overflow-hidden -space-x-1">
                      {props.company.accounts.map((account) => (
                        <img
                          key={account.id}
                          className="inline-block h-6 w-6 rounded-full ring-2 ring-white"
                          src={account.logo}
                          alt={account.name}
                        />
                      ))}
        				<PlusCircleIcon className="h-5 w-5 text-gray-400" />
                    </div>
                  </div>
                </div>
                <div className="ml-5 flex-shrink-0">
                  <ChevronRightIcon className="h-5 w-5 text-gray-400" aria-hidden="true" />
                </div>
              </div>
            </a>
          </li>
  )
}

