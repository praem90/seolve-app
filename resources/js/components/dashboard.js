import React, {useEffect, useState} from 'react';
import SearchBar from './search';
import Pagination from './pagination';
import Empty from './empty';
import axios from 'axios';

const Dashboard = () => {
	const [query, setQuery] = useState('');
	const [force, setForce] = useState(false);
	const [profiles, setProfiles] = useState([]);

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

		axios.get('/profile/search', {params}).then(res => {
			const {data, ...info} = res.data;
			setProfiles(data);
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
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8" >
			<SearchBar onChange={_.debounce(onChange, 500)} onForce={onForceChange} force={force} />
			{profiles.length === 0 ? <Empty title="No profiles found"/> : <Pagination prev={prev} next={next} info={info} />}

		</div>
	)
}

export default Dashboard
