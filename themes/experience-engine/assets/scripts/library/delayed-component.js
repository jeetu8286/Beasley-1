import React, { PureComponent } from 'react';

const delayed = ( WrappedComponent, delay ) => {
	const getDisplayName = () => WrappedComponent.displayName || WrappedComponent.name || 'Component';

	const DelayedComponent = class extends PureComponent {

		constructor( props ) {
			super( props );
			this.state = { waiting: true };
		}

		componentDidMount() {
			this.timeoutId = setTimeout( () => {
				this.setState( { waiting: false } );
			}, delay );
		}

		componentWillUnmount() {
			if ( this.timeoutId ) {
				clearTimeout( this.timeoutId );
			}
		}

		render() {
			const { waiting } = this.state;
			if ( waiting ) {
				return false;
			}

			return waiting ? false : <WrappedComponent {...this.props} />;
		}

	};

	DelayedComponent.displayName = `Delayed(${getDisplayName( WrappedComponent )})`;

	return DelayedComponent;
};

export default delayed;
