<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 In Cite Solution <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Interface 'FullRestaurant' for the 'ics_sitlor_query' extension.
 *
 * @author	Tsi YANG <tsi@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icssitlorquery
 */

class tx_icssitlorquery_FullRestaurant extends tx_icssitlorquery_Restaurant {
	private $phone = null;
	private $tmpPhone = array(
		'phone1' => '',
		'phone2' => ''
	);
	private $fax;
	private $email;
	private $webSite;

	private $coordinates = null;
	private $latitude = 0;
	private $longitude = 0;

	private $providerName = null;
	private $tmpProviderName = array(
		'title' => '',
		'firstname' => '',
		'lastname' => ''
	);
	private $providerAddress = null;
	private $tmpProviderAddress = array(
		'number' => '',
		'street' => '',
		'extra' => '',
		'zip' => '',
		'city' => ''
	);
	private $providerPhone = null;
	private $tmpProviderPhone = array(
		'phone1' => '',
		'phone2' => ''
	);
	private $providerFax;
	private $providerEmail;
	private $providerWebSite = null;

	private $class;					// tx_icssitlorquery_ValuedTermList

	private $receptionLanguage;		// tx_icssitlorquery_ValuedTermList
	private $menuLanguage;			// tx_icssitlorquery_ValuedTermList
	private $pets;
	private $allowedPets;
	private $allowedGroup;
	private $allowedGroupNumber;	// tx_icssitlorquery_ValuedTermList
	private $motorCoachPark;
	private $serviceOpen;			// tx_icssitlorquery_ValuedTermList

	private $capacity;				// tx_icssitlorquery_ValuedTermList

	private $currentSaleFormula;			// tx_icssitlorquery_ValuedTermList
	private $currentCartePrice;				// tx_icssitlorquery_ValuedTermList
	private $currentMenuPrice;				// tx_icssitlorquery_ValuedTermList


	/**
	 * Initializes the FullRestaurant.
	 *
	 * @return	void
	 */
	public function __construct() {
		parent::__construct();
		$this->class = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->receptionLanguage = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->menuLanguage = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->allowedGroupNumber = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->serviceOpen = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->capacity = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->currentSaleFormula = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->currentCartePrice = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
		$this->currentMenuPrice = t3lib_div::makeInstance('tx_icssitlorquery_ValuedTermList');
	}

	/**
	 * Retrieves properties
	 *
	 * @param	string		$name : Property's name
	 *
	 * @return mixed : name 's value
	 */
	public function __get($name) {
		switch ($name) {
			//-- IDENTITITY
			case 'Phone':
				return $this->phone;
			case 'Fax':
				return $this->fax;
			case 'Email':
				return $this->email;
			case 'WebSite':
				return $this->webSite;

			//-- COORDINATES
			case 'Coordinates':
				return $this->coordinates;

			//-- PROVIDER
			case 'ProviderName':
				return $this->providerName;
			case 'ProviderAddress':
				return $this->providerAddress;
			case 'ProviderPhone':
				return $this->providerPhone;
			case 'ProviderFax':
				return $this->providerFax;
			case 'ProviderEmail':
				return $this->providerEmail;
			case 'ProviderWebSite':
				return $this->providerWebSite;

			//-- CLASS
			case 'Class':
				return $this->class;

			//-- RECEPTION
			case 'ReceptionLanguage':
				return $this->receptionLanguage;
			case 'MenuLanguage':
				return $this->menuLanguage;
			case 'Pets':
				return $this->pets;
			case 'AllowedPets':
				return $this->allowedPets;
			case 'AllowedGroup':
				return $this->allowedGroup;
			case 'AllowedGroupNumber':
				return $this->allowedGroupNumber;
			case 'MotorCoachPark':
				return $this->motorCoachPark;
			case 'serviceOpen':
				return $this->serviceOpen;

			//-- CAPACITY
			case 'Capacity':
				return $this->capacity;

			//-- PRICE
			case 'CurrentSaleFormula':
				return $this->currentSaleFormula;
			case 'CurrentCartePrice':
				return $this->currentCartePrice;
			case 'CurrentMenuPrice':
				return $this->currentMenuPrice;

			default :
				return parent::__get($name);
		}
	}

	/**
	 * Read the current XML element in the XMLReader
	 *
	 * @param	XMLReader $reader : Reader to the parsed document
	 */
	protected function readElement(XMLReader $reader) {
		switch ($reader->name) {
			//-- IDENTITY
			case 'ADRPROD_TEL':
				$this->tmpPhone['phone1'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPROD_TEL2':
				$this->tmpPhone['phone2'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPROD_FAX':
				$this->fax =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPROD_EMAIL':
				$email = $reader->readString();
				$this->email = t3lib_div::makeInstance('tx_icssitlorquery_Link', $email);
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPROD_URL':
				$url = $reader->readString();
				// TODO : Check whether url is valid url
				$this->webSite =  t3lib_div::makeInstance('tx_icssitlorquery_Link', $url);
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			//-- COORDINATES
			case 'LATITUDE':
				$this->latitude =  floatval(str_replace(',', '.', $reader->readString()));
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'LONGITUDE':
				$this->longitude =  floatval(str_replace(',', '.', $reader->readString()));
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			//-- PROVIDER
			case 'PREST_CIVILITE':
				$this->tmpProviderName['title'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'PREST_NOM_RESP':
				$this->tmpProviderName['firstname'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'PREST_PRENOM_RESP':
				$this->tmpProviderName['lastname'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_NUM_VOIE' :
				$this->tmpProviderAddress['number'] = $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_LIB_VOIE' :
				$this->tmpProviderAddress['street'] = $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_COMPL_ADRESSE':
				$this->tmpProviderAddress['extra'] = $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_CP' :
				$this->tmpProviderAddress['zip'] = $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_LIBELLE_COMMUNE' :
				$this->tmpProviderAddress['city'] = $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_TEL':
				$this->tmpProviderPhone['phone1'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_TEL2':
				$this->tmpProviderPhone['phone2'] =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_FAX':
				$this->providerFax =  $reader->readString();
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_EMAIL':
				$email = $reader->readString();
				$this->providerEmail = t3lib_div::makeInstance('tx_icssitlorquery_Link', $email);
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			case 'ADRPREST_URL':
				$url = $reader->readString();
				// TODO : Check whether url is valid url
				$this->providerWebSite =  t3lib_div::makeInstance('tx_icssitlorquery_Link', $url);
				tx_icssitlorquery_XMLTools::skipChildren($reader);
				break;

			default:	//-- CRITERION and other
				parent::readElement($reader);
		}
	}

	/**
	 * Set criterion
	 *
	 * @param	tx_icssitlorquery_ValuedTerm $valuedTerm
	 */
	protected function setCriterion(tx_icssitlorquery_ValuedTerm $valuedTerm) {
		parent::setCriterion($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::RESTAURANT_CLASS)
			$this->class->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::RECEPTION_LANGUAGE)
			$this->receptionLanguage->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::MENU_LANGUAGE)
			$this->menuLanguage->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::PETS)
			$this->pets = $valuedTerm;
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::ALLOWED_PETS)
			$this->allowedPets = $valuedTerm;
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::ALLOWED_GROUP)
			$this->allowedGroup = $valuedTerm;
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::ALLOWED_GROUP_NUMBER)
			$this->allowedGroupNumber->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::MOTORCOACH_PARK)
			$this->motorCoachPark = $valuedTerm;
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::SERVICEOPEN)
			$this->serviceOpen->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::RESTAURANT_CAPACITY)
			$this->capacity->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::CURRENT_SALE_FORMULA)
			$this->currentSaleFormula->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::CURRENT_CARTE_PRICE)
			$this->currentCartePrice->Add($valuedTerm);
		if ($valuedTerm->Criterion->ID == tx_icssitlorquery_CriterionUtils::CURRENT_MENU_PRICE)
			$this->currentMenuPrice->Add($valuedTerm);
	}

	/**
	 * Process after parsing the current XML node in the XMLReader
	 *
	 */
	protected function afterParseXML() {
		parent::afterParseXML();
		$this->phone = t3lib_div::makeInstance(
			'tx_icssitlorquery_Phone',
			$this->tmpPhone['phone1'],
			$this->tmpPhone['phone2']
		);
		$this->coordinates = t3lib_div::makeInstance('tx_icssitlorquery_Coordinates', $this->latitude, $this->longitude);
		$this->providerName = t3lib_div::makeInstance(
			'tx_icssitlorquery_Name',
			$this->tmpProviderName['title'],
			$this->tmpProviderName['firstname'],
			$this->tmpProviderName['lastname']
		);
		$this->providerAddress = t3lib_div::makeInstance(
			'tx_icssitlorquery_Address',
			$this->tmpProviderAddress['number'],
			$this->tmpProviderAddress['street'],
			$this->tmpProviderAddress['extra'],
			$this->tmpProviderAddress['zip'],
			$this->tmpProviderAddress['city']
		);
		$this->providerPhone = t3lib_div::makeInstance(
			'tx_icssitlorquery_Phone',
			$this->tmpProviderPhone['phone1'],
			$this->tmpProviderPhone['phone2']
		);
	}

	/**
	 * Retrieves required criteria
	 *
	 * @return mixed : Array of criteria IDs
	 */
	public static function getRequiredCriteria() {
		$criteria = array(
			tx_icssitlorquery_CriterionUtils::RESTAURANT_CLASS,
			tx_icssitlorquery_CriterionUtils::RECEPTION_LANGUAGE,
			tx_icssitlorquery_CriterionUtils::MENU_LANGUAGE,
			tx_icssitlorquery_CriterionUtils::PETS,
			tx_icssitlorquery_CriterionUtils::ALLOWED_PETS,
			tx_icssitlorquery_CriterionUtils::ALLOWED_GROUP,
			tx_icssitlorquery_CriterionUtils::ALLOWED_GROUP_NUMBER,
			tx_icssitlorquery_CriterionUtils::MOTORCOACH_PARK,
			tx_icssitlorquery_CriterionUtils::SERVICEOPEN,
			tx_icssitlorquery_CriterionUtils::RESTAURANT_CAPACITY,
			tx_icssitlorquery_CriterionUtils::CURRENT_SALE_FORMULA,
			tx_icssitlorquery_CriterionUtils::CURRENT_CARTE_PRICE,
			tx_icssitlorquery_CriterionUtils::CURRENT_MENU_PRICE,
		);
		return array_merge(parent::getRequiredCriteria(), $criteria);
	}

}