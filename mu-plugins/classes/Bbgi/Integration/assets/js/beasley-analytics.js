/* GA CONFIG DATA EMITTED FROM PHP
		$data = [
			'google_analytics_v3_enabled' => $google_analytics_v3_enabled,
			'google_analytics'        	  => $google_analytics_ua,
			'google_analytics_v4_enabled' => $google_analytics_v4_enabled,
			'google_analytics_v4'	  	  => $google_analytics_ua_v4,
			'google_uid_dimension'    	  => absint( get_option( self::OPTION_UA_UID ) ),
			'google_author_dimension' 	  => absint( get_option( self::OPTION_UA_AUTHOR ) ),
			'title'                   	  => wp_title( '&raquo;', false ),
			'url'					  	  => esc_url( home_url( $_SERVER['REQUEST_URI'] ) ),
			'shows'                   	  => '',
			'category'                	  => '',
			'author'                  	  => 'non-author',
		];
*/


class beasleyAnalytics {
	analyticsProviderArray = [];

	loadBeasleyConfigData = (beasleyAnalyticsConfigData) => {
		// guard to prevent multiple initial loads
		if (this.analyticsProviderArray.length > 0) {
			return;
		}

		if (beasleyAnalyticsConfigData.google_analytics_v3_enabled && beasleyAnalyticsConfigData.google_analytics) {
			this.analyticsProviderArray.push(new beasleyAnalyticsGaV3Provider(beasleyAnalyticsConfigData.google_analytics));
		}
		if (beasleyAnalyticsConfigData.google_analytics_v4_enabled && beasleyAnalyticsConfigData.google_analytics_v4) {
			this.analyticsProviderArray.push(new beasleyAnalyticsGaV4Provider(beasleyAnalyticsConfigData.google_analytics_v4));
		}
	}

	createAnalytics() {
		this.analyticsProviderArray.map(provider => provider.createAnalytics());
	}

	setAnalytics() {
		this.analyticsProviderArray.map(provider => provider.setAnalytics());
	}

	sendEvent() {
		this.analyticsProviderArray.map(provider => provider.sendEvent());
	}


}

class beasleyAnalyticsBaseProvider {
	constructor(typeString, idString) {
		this.analyticType = typeString;
		this.idString = idString;

		this.debugLog(`Constructor - id: ${this.idString}`);
	}

	isDebugMode = true;

	debugLog(message) {
		if (this.isDebugMode) {
			console.log(`Beasley Analytics ${this.analyticType} - ${message}`);
		}
	}

	createAnalytics() {
		this.debugLog(`createAnalytics()`);
	}

	setAnalytics() {
		this.debugLog(`createAnalytics()`);
	}

	sendEvent() {
		this.debugLog(`createAnalytics()`);
	}
}

class beasleyAnalyticsGaV3Provider extends beasleyAnalyticsBaseProvider {
	static typeString = 'GA_V3';

	constructor(idString) {
		super(beasleyAnalyticsGaV3Provider.typeString, idString);
	}

	createAnalytics() {
		super.createAnalytics();
	}

	setAnalytics() {
		super.setAnalytics();
	}

	sendEvent() {
		super.sendEvent();
	}
}

class beasleyAnalyticsGaV4Provider extends beasleyAnalyticsBaseProvider {
	static typeString = 'GA_V4';

	constructor(idString) {
		super(beasleyAnalyticsGaV4Provider.typeString, idString);
	}

	// Category, Action, Label, Value not in GA4 - there are prdefine and you can add custom
	// set event params - https://developers.google.com/analytics/devguides/collection/ga4/event-parameters?client_type=gtag

	// https://support.google.com/analytics/answer/11403294?hl=en#zippy=%2Cgoogle-tag-manager-websites
	// If you manually send page_view events, make sure Enhanced measurement is configured correctly to avoid double counting pageviews on history state changes. Typically, this means disabling Page changes based on browser history events under the advanced settings of the Page views section.

	createAnalytics() {
		super.createAnalytics();
	}

	setAnalytics() {
		super.setAnalytics();
	}

	sendEvent() {
		super.sendEvent();
	}
}


window.beasleyanalytics = new beasleyAnalytics();
window.beasleyanalytics.loadBeasleyConfigData(bbgiAnalyticsConfig);

