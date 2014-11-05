<?php

namespace GreaterMedia\Gigya\FakeProfiles;

class FakeGigyaUser {

	public $properties = array();
	public $gigyaUserID;

	function __construct( $properties = array() ) {
		$this->properties = $properties;
	}

	function seed( $faker ) {
		/* all gigya profile fields */
		/*
		 * birthYear
		 * country
		 * email
		 * firstName
		 * lastName
		 * likes
		 * zip
		 * nickname
		 * gender
		 * age
		 * birthDay
		 * birthMonth
		 * proxyemail
		 * state
		 * city
		 * profileURL
		 * photoURL
		 * thumbnailURL
		 * languages
		 * address
		 * honors
		 * professionalHeadline
		 * industry
		 * specialities
		 * religion
		 * politicalView
		 * interestedIn
		 * relationshipStatus
		 * hometown
		 * bio
		 * username
		 * name
		 * locale
		 * verified
		 * timezone
		 * phones - type, number
		 * publications
		 * patents
		 * certifications
		 * skills
		 * education
		 * work
		 * favorites
		 * lastLoginLocation - city, state, country, coordinates
		 * educationLevel
		 * followersCount
		 * followingCount
		 */

		/* Relevant profile fields
		 *
		 * email
		 * firstName
		 * lastName
		 * nickname
		 * username
		 * gender
		 * age
		 * birthDay
		 * birthMonth
		 * birthYear
		 * zip
		 * state
		 * city
		 * country
		 * address
		 * hometown
		 * phones - type, number
		 * locale
		 * verified
		 * timezone
		 * likes
		 * languages
		 * industry
		 * education
		 * work
		 * favorites
		 * lastLoginLocation - city, state, country, coordinates
		 * educationLevel
		 * followersCount
		 * followingCount
		 */
		$gender = rand( 0, 1 ) === 1 ? 'male' : 'female';

		$this->properties['email']     = $faker->email;
		$this->properties['firstName'] = $faker->firstName( $gender );
		$this->properties['lastName']  = $faker->lastName;

		$this->properties['nickname'] = strtolower( $this->properties['firstName'] );
		$this->properties['username'] = $this->properties['email'];
		$this->properties['gender']   = $gender;

		$date  = $faker->dateTime( '-15 years' );
		$day   = intval( $date->format( 'd' ) );
		$month = intval( $date->format( 'm' ) );
		$year  = intval( $date->format( 'y' ) );
		$age   = $date->diff( new \DateTime( 'now' ) )->y;

		$this->properties['age']        = $age;
		$this->properties['birthDay']   = $day;
		$this->properties['birthMonth'] = $month;
		$this->properties['birthYear']  = $year;

		$this->properties['zip']     = $faker->postcode;
		$this->properties['state']   = $faker->stateAbbr;
		$this->properties['city']    = $faker->city;
		$this->properties['country'] = 'United States';
		$this->properties['address'] = $faker->streetAddress;
		$this->properties['hometown'] = $faker->city;
		$this->properties['phones'] = array(
			array( 'home' => $this->phone_number( $faker ) ),
			array( 'cell' => $this->phone_number( $faker ) ),
		);

		$this->properties['locale'] = 'en_US';
		$this->properties['verified'] = $faker->boolean( 90 );
		$this->properties['timezone'] = $faker->timezone;
		$this->properties['likes'] = array(
			$faker->colorName,
			$faker->colorName,
			$faker->colorName,
			$faker->colorName,
			$faker->colorName,
			$faker->colorName,
			$faker->colorName,
		);

		$this->properties['languages'] = array('English');
		$this->properties['industry'] = 'Software';
		$this->properties['favorites'] = array(
			$faker->monthName,
			$faker->domainWord,
			$faker->creditCardType,
			$faker->city,
			$faker->state,
		);
	}

	function phone_number( $faker ) {
		return
			$faker->numberBetween( 100, 999 ) . '-' .
			$faker->numberBetween( 100, 999 ) . '-' .
			$faker->numberBetween( 100, 999 );
	}

	function sync() {

	}

	function to_json() {
		return json_encode( $this->properties );
	}

}
