import React, { useEffect, Fragment } from 'react';

import IntersectionObserverContext, {
	Observable,
} from './context/intersection-observer';

import ContentDispatcher from './modules/ContentDispatcher';
import ModalDispatcher from './modules/ModalDispatcher';
import LivePlayer from './modules/LivePlayer';
import PrimaryNav from './modules/PrimaryNav';
import UserNav from './modules/UserNav';
import SearchForm from './modules/SearchForm';
import BackToTop from './components/BackToTop';

import ErrorBoundary from './components/ErrorBoundary';
import { isSafari, isWindowsBrowser } from './library/browser';

const observer = new Observable();

const App = () => {
	useEffect( () => {
		if ( isSafari() ) {
			document.body.classList.add( 'is-safari' );
		} else if ( isWindowsBrowser() ) {
			document.body.classList.add( 'is-windows' );
		}
	}, [] );

	return (
		<Fragment>
			<IntersectionObserverContext.Provider value={observer}>
				<ErrorBoundary>
					<ContentDispatcher />
					<ModalDispatcher />
					<LivePlayer />
					<PrimaryNav />
					<UserNav suppressUserCheck={false} />
					<SearchForm />
				</ErrorBoundary>
			</IntersectionObserverContext.Provider>
			<ErrorBoundary>
				<BackToTop />
			</ErrorBoundary>
		</Fragment>
	);
};

export default App;
