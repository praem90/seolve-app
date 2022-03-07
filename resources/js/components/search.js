const React = require('react');

const searchBar = props => {
	return (
		<div className="flex flex-row items-center mb-6">
  			<div className="">
    			<label htmlFor="search" className="sr-only">Search Profiles</label>
    			<div className="relative text-gray-300 focus-within:text-gray-400">
    				<div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
    					<svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
    						<path fillRule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clipRule="evenodd" />
    					</svg>
    				</div>
    				<input onChange={props.onChange} id="search" name="search" className="block w-full pl-10 pr-3 py-2 border border-transparent rounded-md leading-5 bg-gray-300 bg-opacity-25 text-gray-600 placeholder-gray-300 focus:outline-none focus:bg-white focus:ring-0 focus:placeholder-gray-400 focus:text-gray-900 sm:text-sm" placeholder={props.placeholder || "Search company"} type="search" />
    			</div>
  			</div>

			{props.searchOnly ? '' : <Checkbox onForce={props.onForce} force={props.force}  /> }
		</div>
	)
}

const Checkbox = (props) => (
			<div className="relative flex items-start ml-3">
    			<div className="flex items-center h-5">
      	  	  	  <input onChange={props.onForce}  checked={props.force} id="comments" aria-describedby="comments-description" name="comments" type="checkbox" className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
    			</div>
    			<div className="ml-3 text-sm">
      	  	  	  <label htmlFor="comments" className="font-sm text-gray-700 whitespace-nowrap">Fetch from Github</label>
    			</div>
  	  	   </div>

)

export default searchBar;
