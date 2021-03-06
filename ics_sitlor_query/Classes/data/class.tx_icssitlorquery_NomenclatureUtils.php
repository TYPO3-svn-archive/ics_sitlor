<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 In Cite Solution <technique@in-cite.net>
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
 * Class 'tx_icssitlorquery_NomenclatureUtils' for the 'ics_sitlor_query' extension.
 *
 * @author	Tsi YANG <tsi@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icssitlorquery
 */
class tx_icssitlorquery_NomenclatureUtils {
	/*******************************************************
	 *
	 * Accomodation
	 *
	 *******************************************************/
	const ACCOMODATION = 4000001;		// Gender "H�bergement"
	const HOTEL = 4000002;				// Type "H�tel"
	const HOTEL_RESTAURANT = 4000003;	// Type "H�tel - h�tel restaurant"
	const FURNISHED = 4000012;			// Type "Meubl�"
	const RESIDENCE = 4000004; 			// Category "R�sidence "
	const TOURIST_RESIDENCE = 4000010;	// Type "R�sidences de tourisme"
	const HOTEL_RESIDENCE = 4000011;	// Type "R�sidences h�teli�re"
	static $hotel = array(
		self::HOTEL,
		self::HOTEL_RESTAURANT,
		// self::FURNISHED,
		self::TOURIST_RESIDENCE,
		self::HOTEL_RESIDENCE,
	);

	const HOLLIDAY_CAMPSITE = 4000007;	// Type "Terrain de Camping saisonnie"
	const CATEGORIZED_CAMPSITE = 4000004;	// Type "Terrain de Camping class�"
	const FARM_CAMPING = 4000006;	// Type "Camping � la ferme"
	const YOUTH_HOSTEL = 4000071;	// Type "Auberge de Jeunesse"
	static $camping = array(
		self::HOLLIDAY_CAMPSITE,
		self::CATEGORIZED_CAMPSITE,
		self::FARM_CAMPING,
	);
	const GUESTHOUSE = 4000001;	// Category "Chambres d'h�tes"
	const GUESTHOUSE_TYPE = 4000001;	// Type "Chambres d'h�tes"
	const HOLLIDAY_COTTAGE = 4000005; // Category "Meubl� "


	/*******************************************************
	 *
	 * Restaurant
	 *
	 *******************************************************/
	const RESTAURANT = 4000007;	// Category "Restauration"

	/*******************************************************
	 *
	 * Event
	 *
	 *******************************************************/
	const EVENT = 4000003 ;	// Genre "A voir/ A faire"
	
	
	/*******************************************************
	 *
	 * Subscriber
	 *
	 *******************************************************/
	const ARTS_CRAFTS = 737000000;	// Category "2. Artisans" for "737000000 - OT NANCY LOCAL"
	const COMMERCE = 737000001;			// Category "2. Commerces" for Gender "737000000 - OT NANCY LOCAL"
	const ASSOCIATION = 737000005;		// Category "3. Associations" for Gender "737000000 - OT NANCY LOCAL"
}