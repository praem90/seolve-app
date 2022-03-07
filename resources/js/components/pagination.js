import React from 'react';

export default props => (
<nav className="bg-white px-4 py-3 mt-6 flex items-center justify-between shadow rounded-md sm:px-6" aria-label="Pagination">
  <div className="hidden sm:block">
    <p className="text-sm text-gray-700">
      Showing&nbsp;
      <span className="font-medium">{props.info.from}</span>
      &nbsp;to&nbsp;
      <span className="font-medium">{props.info.to}</span> of <span className="font-medium">{props.info.total}</span>
      &nbsp;results
    </p>
  </div>
  <div className="flex-1 flex justify-between sm:justify-end">
    <a href="#" onClick={props.prev} className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
      Previous
    </a>
    <a href="#" onClick={props.next} className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
      Next
    </a>
  </div>
</nav>
)
