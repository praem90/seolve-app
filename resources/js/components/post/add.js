import { CheckCircleIcon } from '@heroicons/react/solid';
import React, {useState} from 'react';

export default props => {

	return (
		<div className={"fixed inset-0 overflow-hidden z-50 " + (props.selected ? '' : 'hidden')} aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
  	  	  <div className="absolute inset-0 overflow-hidden">
    		<div className={"absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity ease-in-out duration-500 " + ( props.selected ? '': 'opacity-0')} aria-hidden="true"></div>
    		<div className={"fixed inset-y-0 right-0 pl-10 max-w-5xl flex transform transition ease-in-out duration-500 sm:duration-700 " + (props.selected ? '' : 'translate-x-full')}>
      	  	  <div className="relative w-screen ">
        		<div className="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
          	  	  <button onClick={props.onClose} type="button" className="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
            		<span className="sr-only">Close panel</span>
            		<svg className="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              	  	  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
            		</svg>
          	  	  </button>
        		</div>
        		<PanelBody onClose={props.onClose} company={props.company}/>
      	  	  </div>
    		</div>
  	  	  </div>
		</div>
	)
}

const PanelBody = props => (
	<div className="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">
      <div className="mt-6 relative flex-1 px-4 sm:px-6">
        <div className="absolute inset-0 px-4 sm:px-6">
    		<div className="h-full " aria-hidden="true">
    			<AddForm onClose={props.onClose} company={props.company}/>
    		</div>
        </div>
      </div>
      </div>
)

export function AddForm(props) {
	const [post, setPost] = useState({});
	const [success, setSuccess] = useState(undefined);

	const [accounts, setAccounts] = useState([]);
	const [addingPost, setAddingPost] = useState(false);

    const onFileChange = (e) => {
        post.new_image = e.target.files.length ? e.target.files : undefined;
        setPost(post);
    }

    const handleSubmit = (e) => {
        e.preventDefault();
        const frmData = new FormData();

        frmData.append('company_id', props.company.id);
        frmData.append('message', post.message);
        // frmData.append('scheduled_at', post.scheduled_at);
        // frmData.append('attachements', post.new_image);

        accounts.forEach(account_id => frmData.append('accounts[]', account_id));

        setAddingPost(true);
        axios.post('/api/company/'+ props.company.id +'/post/', frmData)
            .then(res => {
        		setAddingPost(false);
        		setSuccess(res.data);
                setTimeout(() => props.onClose && props.onClose(res.data), 3000);
            })
            .catch(err => {
        		alert('Please fill all the data');
        		setAddingPost(false);
            });
    }

  return (
    <form className="space-y-8 divide-y divide-gray-200">
      <div className="space-y-8 divide-y divide-gray-200">
        <div>
          <div>
            <h3 className="text-lg leading-6 font-medium text-gray-900">Create Post</h3>
          </div>
			{success ? <SuccessAlert message={success.message}/> : ''}
          <div className="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

            <div className="sm:col-span-6">
      			{props.company.accounts.map(account => (
      			<div key={account.id} className="relative flex items-center">
        			<div className="flex items-center h-5">
          	  	  	  <input
            			id={"account_id_" + account.id}
            			aria-describedby="account-description"
            			name="account"
            			type="checkbox"
            		onChange={e => {
            				if (e.target.checked) {
            					accounts.push(account.id);
            				} else {
            					accounts = accounts.filter(ac => ac.id != account.id);
            				}
            				setAccounts(accounts);
            			}}
            			className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
          	  	  	  />
        			</div>
                        <img
                          className="inline-block ml-2  h-4 w-4 rounded-full ring-2 ring-white"
                          src={account.logo}
                          title={account.name}
                          alt={account.name}
                        />
        			<div className="ml-1 ">
          	  	  	  <label htmlFor={"account_id_" + account.id} className="font-medium text-gray-700">
            			{account.name}
          	  	  	  </label>
        			</div>
      	  	  	  </div>
      			))}
      		</div>
            <div className="sm:col-span-6">
              <label htmlFor="description" className="block text-sm font-medium text-gray-700">
                Meta Description
              </label>
              <div className="mt-1">
                <textarea
                  id="description"
                  name="description"
                  rows={3}
                  className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
      				onChange={(e)  => {post.message = e.target.value; setPost(post)  }}
                  defaultValue=""
                />
              </div>
            </div>

            <div className="sm:col-span-6 ">
              <label htmlFor="cover-photo" className="block text-sm font-medium text-gray-700">
                Image
              </label>
                  <div className="mt-1 flex items-center space-x-5">
                    <label className="block">
                        <span className="sr-only">Choose profile photo</span>
                        <input
                            name="image"
                            type="file"
                            required
      						multiple
                            onChange={onFileChange}
                            className="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-violet-50 file:text-violet-700
                          hover:file:bg-violet-100
                        "/>
                  </label>
                </div>
            </div>


            <div className="sm:col-span-6 ">
              <label htmlFor="cover-photo" className="block text-sm font-medium text-gray-700">
                Scheduled
              </label>
                  <div className="mt-1 flex items-center space-x-5">
                    <label className="block">
                        <span className="sr-only">Choose profile photo</span>
                        <input
                            name="scheduled_at"
                            type="datetime-local"
                            className="block w-full text-sm text-gray-500"
                        />
                  </label>
                </div>
            </div>

          </div>
        </div>

      </div>

      <div className="pt-5">
        <div className="flex justify-end">
          <button
            type="button"
            className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            onClick={props.onClose}
          >
            Cancel
          </button>
          <button
            type="button"
            onClick={handleSubmit}
      		disabled={addingPost}
            className="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Publish Post
          </button>
        </div>
      </div>
    </form>
  )
}

export function SuccessAlert(props) {
  return (
    <div className="rounded-md bg-green-50 p-4">
      <div className="flex">
        <div className="flex-shrink-0">
          <CheckCircleIcon className="h-5 w-5 text-green-400" aria-hidden="true" />
        </div>
        <div className="ml-3">
          <p className="text-sm font-medium text-green-800">{props.message}</p>
        </div>
      </div>
    </div>
  )
}
