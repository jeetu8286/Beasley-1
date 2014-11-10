var AVAILABLE_CONSTRAINTS = [

	/* System Fields */
	{
		type: 'system:createdTimestamp',
		valueType: 'date',
		value: '01/01/2012'
	},
	{
		type: 'system:isActive',
		valueType: 'boolean',
	},
	{
		type: 'system:isRegistered',
		valueType: 'boolean',
	},
	{
		type: 'system:lastLoginTimestamp',
		valueType: 'date',
		value: '01/01/2014'
	},
	{
		type: 'system:verified',
		valueType: 'boolean',
		value: true,
	},

	/* Profile fields */
	{
		type: 'profile:birthYear',
		valueType: 'integer',
		value: 1990
	},
	{
		type: 'profile:country',
		valueType: 'string',
		value: 'United States'
	},
	{
		type: 'profile:zip',
		valueType: 'string',
		value: '01001'
	},
	{
		type: 'profile:gender',
		valueType: 'string',
		value: 'm'
	},
	{
		type: 'profile:age',
		valueType: 'integer',
		value: 25
	},
	{
		type: 'profile:birthDay',
		valueType: 'integer',
		value: 1,
	},
	{
		type: 'profile:birthMonth',
		valueType: 'integer',
		value: 1
	},
	{
		type: 'profile:state',
		valueType: 'string',
		value: 'New York'
	},
	{
		type: 'profile:city',
		valueType: 'string'
	},
	{
		type: 'profile:timezone',
		valueType: 'string'
	},

	/* Contests */
	{
		type: 'record:contest',
		valueType: 'string',
		entryTypeID: -1,
		entryFieldID: -1
	}

];

/* Constraint Meta */

var AVAILABLE_CONSTRAINTS_META = [

	/* System Fields */
	{
		type: 'system:createdTimestamp',
		title: 'Creation Date',
	},
	{
		type: 'system:isActive',
		title: 'Active Status',
		choices: [
			{ label: 'Active', value: true },
			{ label: 'Inactive', value: false }
		]
	},
	{
		type: 'system:isRegistered',
		title: 'Registration Status',
		choices: [
			{ label: 'Registered', value: true },
			{ label: 'Not Registered', value: false }
		]
	},
	{
		type: 'system:lastLoginTimestamp',
		title: 'Last Logged In'
	},
	{
		type: 'system:verified',
		title: 'Verified Status',
		choices: [
			{ label: 'Verified', value: true },
			{ label: 'Not Verified', value: false },
		]
	},

	/* Profile Fields */
	{
		type: 'profile:birthYear',
		title: 'Birth Year'
	},
	{
		type: 'profile:country',
		title: 'Country',
		choices: [
			{ label: 'Afghanistan', value: 'Afghanistan' },
			{ label: 'Albania', value: 'Albania' },
			{ label: 'Algeria', value: 'Algeria' },
			{ label: 'American Samoa', value: 'Samoa' },
			{ label: 'Andorra', value: 'Andorra' },
			{ label: 'Angola', value: 'Angola' },
			{ label: 'Antigua and Barbuda', value: 'Barbuda' },
			{ label: 'Argentina', value: 'Argentina' },
			{ label: 'Armenia', value: 'Armenia' },
			{ label: 'Australia', value: 'Australia' },
			{ label: 'Austria', value: 'Austria' },
			{ label: 'Azerbaijan', value: 'Azerbaijan' },
			{ label: 'Bahamas', value: 'Bahamas' },
			{ label: 'Bahrain', value: 'Bahrain' },
			{ label: 'Bangladesh', value: 'Bangladesh' },
			{ label: 'Barbados', value: 'Barbados' },
			{ label: 'Belarus', value: 'Belarus' },
			{ label: 'Belgium', value: 'Belgium' },
			{ label: 'Belize', value: 'Belize' },
			{ label: 'Benin', value: 'Benin' },
			{ label: 'Bermuda', value: 'Bermuda' },
			{ label: 'Bhutan', value: 'Bhutan' },
			{ label: 'Bolivia', value: 'Bolivia' },
			{ label: 'Bosnia and Herzegovina', value: 'Herzegovina' },
			{ label: 'Botswana', value: 'Botswana' },
			{ label: 'Brazil', value: 'Brazil' },
			{ label: 'Brunei', value: 'Brunei' },
			{ label: 'Bulgaria', value: 'Bulgaria' },
			{ label: 'Burkina Faso', value: 'Faso' },
			{ label: 'Burundi', value: 'Burundi' },
			{ label: 'Cambodia', value: 'Cambodia' },
			{ label: 'Cameroon', value: 'Cameroon' },
			{ label: 'Canada', value: 'Canada' },
			{ label: 'Cape Verde', value: 'Verde' },
			{ label: 'Cayman Islands', value: 'Islands' },
			{ label: 'Central African Republic', value: 'Republic' },
			{ label: 'Chad', value: 'Chad' },
			{ label: 'Chile', value: 'Chile' },
			{ label: 'China', value: 'China' },
			{ label: 'Colombia', value: 'Colombia' },
			{ label: 'Comoros', value: 'Comoros' },
			{ label: 'Congo, Democratic Republic of the', value: 'the' },
			{ label: 'Congo, Republic of the', value: 'the' },
			{ label: 'Costa Rica', value: 'Rica' },
			{ label: 'Croatia', value: 'Croatia' },
			{ label: 'Cuba', value: 'Cuba' },
			{ label: 'Cyprus', value: 'Cyprus' },
			{ label: 'Czech Republic', value: 'Republic' },
			{ label: 'Denmark', value: 'Denmark' },
			{ label: 'Djibouti', value: 'Djibouti' },
			{ label: 'Dominica', value: 'Dominica' },
			{ label: 'Dominican Republic', value: 'Republic' },
			{ label: 'East Timor', value: 'Timor' },
			{ label: 'Ecuador', value: 'Ecuador' },
			{ label: 'Egypt', value: 'Egypt' },
			{ label: 'El Salvador', value: 'Salvador' },
			{ label: 'Equatorial Guinea', value: 'Guinea' },
			{ label: 'Eritrea', value: 'Eritrea' },
			{ label: 'Estonia', value: 'Estonia' },
			{ label: 'Ethiopia', value: 'Ethiopia' },
			{ label: 'Fiji', value: 'Fiji' },
			{ label: 'Finland', value: 'Finland' },
			{ label: 'France', value: 'France' },
			{ label: 'French Polynesia', value: 'Polynesia' },
			{ label: 'Gabon', value: 'Gabon' },
			{ label: 'Gambia', value: 'Gambia' },
			{ label: 'Georgia', value: 'Georgia' },
			{ label: 'Germany', value: 'Germany' },
			{ label: 'Ghana', value: 'Ghana' },
			{ label: 'Greece', value: 'Greece' },
			{ label: 'Greenland', value: 'Greenland' },
			{ label: 'Grenada', value: 'Grenada' },
			{ label: 'Guam', value: 'Guam' },
			{ label: 'Guatemala', value: 'Guatemala' },
			{ label: 'Guinea', value: 'Guinea' },
			{ label: 'Guinea-Bissau', value: 'Bissau' },
			{ label: 'Guyana', value: 'Guyana' },
			{ label: 'Haiti', value: 'Haiti' },
			{ label: 'Honduras', value: 'Honduras' },
			{ label: 'Hong Kong', value: 'Kong' },
			{ label: 'Hungary', value: 'Hungary' },
			{ label: 'Iceland', value: 'Iceland' },
			{ label: 'India', value: 'India' },
			{ label: 'Indonesia', value: 'Indonesia' },
			{ label: 'Iran', value: 'Iran' },
			{ label: 'Iraq', value: 'Iraq' },
			{ label: 'Ireland', value: 'Ireland' },
			{ label: 'Israel', value: 'Israel' },
			{ label: 'Italy', value: 'Italy' },
			{ label: 'Jamaica', value: 'Jamaica' },
			{ label: 'Japan', value: 'Japan' },
			{ label: 'Jordan', value: 'Jordan' },
			{ label: 'Kazakhstan', value: 'Kazakhstan' },
			{ label: 'Kenya', value: 'Kenya' },
			{ label: 'Kiribati', value: 'Kiribati' },
			{ label: 'North Korea', value: 'Korea' },
			{ label: 'South Korea', value: 'Korea' },
			{ label: 'Kosovo', value: 'Kosovo' },
			{ label: 'Kuwait', value: 'Kuwait' },
			{ label: 'Kyrgyzstan', value: 'Kyrgyzstan' },
			{ label: 'Laos', value: 'Laos' },
			{ label: 'Latvia', value: 'Latvia' },
			{ label: 'Lebanon', value: 'Lebanon' },
			{ label: 'Lesotho', value: 'Lesotho' },
			{ label: 'Liberia', value: 'Liberia' },
			{ label: 'Libya', value: 'Libya' },
			{ label: 'Liechtenstein', value: 'Liechtenstein' },
			{ label: 'Lithuania', value: 'Lithuania' },
			{ label: 'Luxembourg', value: 'Luxembourg' },
			{ label: 'Macedonia', value: 'Macedonia' },
			{ label: 'Madagascar', value: 'Madagascar' },
			{ label: 'Malawi', value: 'Malawi' },
			{ label: 'Malaysia', value: 'Malaysia' },
			{ label: 'Maldives', value: 'Maldives' },
			{ label: 'Mali', value: 'Mali' },
			{ label: 'Malta', value: 'Malta' },
			{ label: 'Marshall Islands', value: 'Islands' },
			{ label: 'Mauritania', value: 'Mauritania' },
			{ label: 'Mauritius', value: 'Mauritius' },
			{ label: 'Mexico', value: 'Mexico' },
			{ label: 'Micronesia', value: 'Micronesia' },
			{ label: 'Moldova', value: 'Moldova' },
			{ label: 'Monaco', value: 'Monaco' },
			{ label: 'Mongolia', value: 'Mongolia' },
			{ label: 'Montenegro', value: 'Montenegro' },
			{ label: 'Morocco', value: 'Morocco' },
			{ label: 'Mozambique', value: 'Mozambique' },
			{ label: 'Myanmar', value: 'Myanmar' },
			{ label: 'Namibia', value: 'Namibia' },
			{ label: 'Nauru', value: 'Nauru' },
			{ label: 'Nepal', value: 'Nepal' },
			{ label: 'Netherlands', value: 'Netherlands' },
			{ label: 'New Zealand', value: 'Zealand' },
			{ label: 'Nicaragua', value: 'Nicaragua' },
			{ label: 'Niger', value: 'Niger' },
			{ label: 'Nigeria', value: 'Nigeria' },
			{ label: 'Norway', value: 'Norway' },
			{ label: 'Northern Mariana Islands', value: 'Islands' },
			{ label: 'Oman', value: 'Oman' },
			{ label: 'Pakistan', value: 'Pakistan' },
			{ label: 'Palau', value: 'Palau' },
			{ label: 'Palestine', value: 'Palestine' },
			{ label: 'Panama', value: 'Panama' },
			{ label: 'Papua New Guinea', value: 'Guinea' },
			{ label: 'Paraguay', value: 'Paraguay' },
			{ label: 'Peru', value: 'Peru' },
			{ label: 'Philippines', value: 'Philippines' },
			{ label: 'Poland', value: 'Poland' },
			{ label: 'Portugal', value: 'Portugal' },
			{ label: 'Puerto Rico', value: 'Rico' },
			{ label: 'Qatar', value: 'Qatar' },
			{ label: 'Romania', value: 'Romania' },
			{ label: 'Russia', value: 'Russia' },
			{ label: 'Rwanda', value: 'Rwanda' },
			{ label: 'Saint Kitts and Nevis', value: 'Nevis' },
			{ label: 'Saint Lucia', value: 'Lucia' },
			{ label: 'Saint Vincent and the Grenadines', value: 'Grenadines' },
			{ label: 'Samoa', value: 'Samoa' },
			{ label: 'San Marino', value: 'Marino' },
			{ label: 'Sao Tome and Principe', value: 'Principe' },
			{ label: 'Saudi Arabia', value: 'Arabia' },
			{ label: 'Senegal', value: 'Senegal' },
			{ label: 'Serbia and Montenegro', value: 'Montenegro' },
			{ label: 'Seychelles', value: 'Seychelles' },
			{ label: 'Sierra Leone', value: 'Leone' },
			{ label: 'Singapore', value: 'Singapore' },
			{ label: 'Slovakia', value: 'Slovakia' },
			{ label: 'Slovenia', value: 'Slovenia' },
			{ label: 'Solomon Islands', value: 'Islands' },
			{ label: 'Somalia', value: 'Somalia' },
			{ label: 'South Africa', value: 'South Africa' },
			{ label: 'Spain', value: 'Spain' },
			{ label: 'Sri Lanka', value: 'Lanka' },
			{ label: 'Sudan', value: 'Sudan' },
			{ label: 'Sudan, South', value: 'South' },
			{ label: 'Suriname', value: 'Suriname' },
			{ label: 'Swaziland', value: 'Swaziland' },
			{ label: 'Sweden', value: 'Sweden' },
			{ label: 'Switzerland', value: 'Switzerland' },
			{ label: 'Syria', value: 'Syria' },
			{ label: 'Taiwan', value: 'Taiwan' },
			{ label: 'Tajikistan', value: 'Tajikistan' },
			{ label: 'Tanzania', value: 'Tanzania' },
			{ label: 'Thailand', value: 'Thailand' },
			{ label: 'Togo', value: 'Togo' },
			{ label: 'Tonga', value: 'Tonga' },
			{ label: 'Trinidad and Tobago', value: 'Tobago' },
			{ label: 'Tunisia', value: 'Tunisia' },
			{ label: 'Turkey', value: 'Turkey' },
			{ label: 'Turkmenistan', value: 'Turkmenistan' },
			{ label: 'Tuvalu', value: 'Tuvalu' },
			{ label: 'Uganda', value: 'Uganda' },
			{ label: 'Ukraine', value: 'Ukraine' },
			{ label: 'United Arab Emirates', value: 'Emirates' },
			{ label: 'United Kingdom', value: 'Kingdom' },
			{ label: 'United States', value: 'United States' },
			{ label: 'Uruguay', value: 'Uruguay' },
			{ label: 'Uzbekistan', value: 'Uzbekistan' },
			{ label: 'Vanuatu', value: 'Vanuatu' },
			{ label: 'Vatican City', value: 'City' },
			{ label: 'Venezuela', value: 'Venezuela' },
			{ label: 'Vietnam', value: 'Vietnam' },
			{ label: 'Virgin Islands, British', value: 'British' },
			{ label: 'Virgin Islands, U.S.', value: 'Virgin Islands, US' },
			{ label: 'Yemen', value: 'Yemen' },
			{ label: 'Zambia', value: 'Zambia' },
			{ label: 'Zimbabwe', value: 'Zimbabwe' },
		]
	},
	{
		type: 'profile:zip',
		title: 'Zip Code'
	},
	{
		type: 'profile:gender',
		title: 'Gender',
		choices: [
			{ label: 'Male', value: 'm' },
			{ label: 'Female', value: 'f' },
			{ label: 'Unknown', value: 'u' }
		]
	},
	{
		type: 'profile:age',
		title: 'Age',
	},
	{
		type: 'profile:birthDay',
		title: 'Birth Day'
	},
	{
		type: 'profile:birthMonth',
		title: 'Birth Month',
		choices: [
			{ label: 'January'   , value: 1  } ,
			{ label: 'February'  , value: 2  } ,
			{ label: 'March'     , value: 3  } ,
			{ label: 'April'     , value: 4  } ,
			{ label: 'May'       , value: 5  } ,
			{ label: 'June'      , value: 6  } ,
			{ label: 'July'      , value: 7  } ,
			{ label: 'August'    , value: 8  } ,
			{ label: 'September' , value: 9  } ,
			{ label: 'October'   , value: 10 } ,
			{ label: 'November'  , value: 11 } ,
			{ label: 'December'  , value: 12 } ,
		]
	},
	{
		type: 'profile:state',
		title: 'State',
		choices: [
			{ label: 'Alabama'               , value: 'Alabama'               } ,
			{ label: 'Alaska'                , value: 'Alaska'                } ,
			{ label: 'Arizona'               , value: 'Arizona'               } ,
			{ label: 'Arkansas'              , value: 'Arkansas'              } ,
			{ label: 'California'            , value: 'California'            } ,
			{ label: 'Colorado'              , value: 'Colorado'              } ,
			{ label: 'Connecticut'           , value: 'Connecticut'           } ,
			{ label: 'Delaware'              , value: 'Delaware'              } ,
			{ label: 'District of Columbia'  , value: 'District of Columbia'  } ,
			{ label: 'Florida'               , value: 'Florida'               } ,
			{ label: 'Georgia'               , value: 'Georgia'               } ,
			{ label: 'Hawaii'                , value: 'Hawaii'                } ,
			{ label: 'Idaho'                 , value: 'Idaho'                 } ,
			{ label: 'Illinois'              , value: 'Illinois'              } ,
			{ label: 'Indiana'               , value: 'Indiana'               } ,
			{ label: 'Iowa'                  , value: 'Iowa'                  } ,
			{ label: 'Kansas'                , value: 'Kansas'                } ,
			{ label: 'Kentucky'              , value: 'Kentucky'              } ,
			{ label: 'Louisiana'             , value: 'Louisiana'             } ,
			{ label: 'Maine'                 , value: 'Maine'                 } ,
			{ label: 'Maryland'              , value: 'Maryland'              } ,
			{ label: 'Massachusetts'         , value: 'Massachusetts'         } ,
			{ label: 'Michigan'              , value: 'Michigan'              } ,
			{ label: 'Minnesota'             , value: 'Minnesota'             } ,
			{ label: 'Mississippi'           , value: 'Mississippi'           } ,
			{ label: 'Missouri'              , value: 'Missouri'              } ,
			{ label: 'Montana'               , value: 'Montana'               } ,
			{ label: 'Nebraska'              , value: 'Nebraska'              } ,
			{ label: 'Nevada'                , value: 'Nevada'                } ,
			{ label: 'New Hampshire'         , value: 'New Hampshire'         } ,
			{ label: 'New Jersey'            , value: 'New Jersey'            } ,
			{ label: 'New Mexico'            , value: 'New Mexico'            } ,
			{ label: 'New York'              , value: 'New York'              } ,
			{ label: 'North Carolina'        , value: 'North Carolina'        } ,
			{ label: 'North Dakota'          , value: 'North Dakota'          } ,
			{ label: 'Ohio'                  , value: 'Ohio'                  } ,
			{ label: 'Oklahoma'              , value: 'Oklahoma'              } ,
			{ label: 'Oregon'                , value: 'Oregon'                } ,
			{ label: 'Pennsylvania'          , value: 'Pennsylvania'          } ,
			{ label: 'Rhode Island'          , value: 'Rhode Island'          } ,
			{ label: 'South Carolina'        , value: 'South Carolina'        } ,
			{ label: 'South Dakota'          , value: 'South Dakota'          } ,
			{ label: 'Tennessee'             , value: 'Tennessee'             } ,
			{ label: 'Texas'                 , value: 'Texas'                 } ,
			{ label: 'Utah'                  , value: 'Utah'                  } ,
			{ label: 'Vermont'               , value: 'Vermont'               } ,
			{ label: 'Virginia'              , value: 'Virginia'              } ,
			{ label: 'Washington'            , value: 'Washington'            } ,
			{ label: 'West Virginia'         , value: 'Virginia'              } ,
			{ label: 'Wisconsin'             , value: 'Wisconsin'             } ,
			{ label: 'Wyoming'               , value: 'Wyoming'               } ,
			{ label: 'Armed Forces Americas' , value: 'Armed Forces Americas' } ,
			{ label: 'Armed Forces Europe'   , value: 'Armed Forces Europe'   } ,
			{ label: 'Armed Forces Pacific'  , value: 'Armed Forces Pacific'  } ,
		]
	},
	{
		type: 'profile:city',
		title: 'City'
	},
	{
		type: 'profile:timezone',
		title: 'Timezone'
	},

	/* Contests */
	{
		type: 'record:contest',
		title: 'Contest Entry'
	}

];

var AVAILABLE_CONSTRAINTS_META_MAP = {};

(function() {
	var i;
	var constraints = AVAILABLE_CONSTRAINTS_META;
	var map         = AVAILABLE_CONSTRAINTS_META_MAP;
	var n           = constraints.length;

	for ( i = 0; i < n; i++ ) {
		constraint = constraints[i];
		map[constraint.type] = constraint;
	}
})();

