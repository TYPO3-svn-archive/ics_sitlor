<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 In Cite Solution <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the CriterionFactorys of the GNU General Public License as published by
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


/**
 * Class 'tx_icssitlorquery_CriterionFactory' for the 'ics_sitlor_query' extension.
 *
 * @author	Tsi YANG <tsi@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icssitlorquery
 */
class tx_icssitlorquery_CriterionFactory {
	static $criteria;	// tx_icssitlorquery_CriterionList
	static $criteriaTerms = array();	// TermList[]

	static $login;
	static $password;
	static $url;

	private static $hash;
	private static $cacheInstance;
	private static $lifetime = 0;

	/**
	 * Fetch values
	 *
	 * @return	boolean
	 */
	private static function FetchValues() {
		$params = array(
			'user' => utf8_decode(self::$login),
			'pwkey' => utf8_decode(self::$password),
		);
		$url = self::$url . '?' . http_build_query($params);
		t3lib_div::devLog('Url criterion', 'ics_sitlor_query', 0, array(urldecode($url)));

		$xmlContent = tx_icssitlorquery_XMLTools::getXMLDocument($url);
		if (!$xmlContent) {
			tx_icssitquery_Debug::error('Unable to read criteria XML document at ' . $url);
			return false;
		}

		$reader = new XMLReader();
		// file_put_contents(t3lib_div::getFileAbsFileName('typo3temp/sitlor_criterion_out.xml'), $xmlContent);
		$reader->XML($xmlContent);
		if (!tx_icssitlorquery_XMLTools::XMLMoveToRootElement($reader, 'LEI')) {
			tx_icssitquery_Debug::error('Invalid response from SITLOR nomenclature.');
			return false;
		}
		$reader->read();
		if (!$reader->next('Resultat')) {
			tx_icssitquery_Debug::error('Can not reach "Resultat" node from SITLOR nomenclature.');
			return false;
		}

		$reader->read();
		$elements = array();
		while ($reader->nodeType != XMLReader::END_ELEMENT) {
			if ($reader->nodeType == XMLReader::ELEMENT) {
				switch ($reader->name) {
					case 'Criteres':
						$terms = t3lib_div::makeInstance('tx_icssitlorquery_TermList');
						if ($criterion = tx_icssitlorquery_Criterion::FromXML($reader, $terms)) {
							$elements[] = array(
								$criterion,
								$terms
							);
						}
						break;

					default:
						tx_icssitlorquery_XMLTools::skipChildren($reader);
				}
			}
			$reader->read();
		}

		self::$cacheInstance->set(self::$hash, serialize($elements), array(), self::$lifetime);

		return $elements;
	}

	/**
	 * Load from cache
	 *
	 * @return	void
	 */
	private static function LoadFromCache() {
		t3lib_cache::initializeCachingFramework();
        try {
            self::$cacheInstance = $GLOBALS['typo3CacheManager']->getCache('icssitlorquery_cache');
        } catch (t3lib_cache_exception_NoSuchCache $e) {
            self::$cacheInstance = $GLOBALS['typo3CacheFactory']->create(
                'icssitlorquery_cache',
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['icssitlorquery_cache']['frontend'],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['icssitlorquery_cache']['backend'],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['icssitlorquery_cache']['options']
            );
        }

		self::$criteria = t3lib_div::makeInstance('tx_icssitlorquery_CriterionList');

		self::$hash = md5(self::$url . self::$login . self::$password);
		if (!self::$cacheInstance->has(self::$hash))
			self::FetchValues();
		$all = unserialize(self::$cacheInstance->get(self::$hash));
		if ($all === false) {
			throw new Exception('Criteria on cache is broken.');
		}

		foreach ($all as $element) {
			self::$criteria->Add($element[0]);
			self::$criteriaTerms[$element[0]->ID] = $element[1];
		}
	}

	/**
	 * Initialize the factory
	 *
	 * @return	void
	 */
	private static function initialize() {
		if (!self::$login || !self::$password || !self::$url)
			throw new Exception('Criterion connection parameters must be set.');

		if (isset(self::$criteria))
			return;

		self::LoadFromCache();
	}

	/**
	 * Retrieves Criterion
	 *
	 * @param	int		$id : id of criterion
	 * @return	Criterion
	 */
	public static function GetCriterion($id) {
		self::initialize();
		for ($i=0; $i<self::$criteria->Count(); $i++) {
			$criterion = self::$criteria->Get($i);
			if ($criterion->ID == $id) {
				return $criterion;
			}
		}
		return null;
	}

	/**
	 * Retrieves CriterionList
	 *
	 * @param	int		array $ids : ids of criteria
	 * @return	CriterionList
	 */
	public static function GetCriteria(array $ids) {
		self::initialize();
		$criteria = t3lib_div::makeInstance('tx_icssitlorquery_CriterionList');
		for ($i=0; $i<self::$criteria->Count(); $i++) {
			$criterion = self::$criteria->Get($i);
			if (in_array($criterion->ID, $ids)) {
				$criteria->Add($criterion);
			}
		}
		return $criteria;
	}

	/**
	 * Retrieves CriterionList
	 *
	 * @return	CriterionList
	 */
	public static function GetAllCriteria() {
		self::initialize();
		return self::$criteria;
	}

	/**
	 * Retrieves Terms from criterion
	 *
	 * @param	tx_icssitlorquery_Criterion $criterion
	 * @return TermList
	 */
	public static function GetCriterionTerms(tx_icssitlorquery_Criterion $criterion) {
		self::initialize();
		return self::$criteriaTerms[$criterion->ID];
	}

	/**
	 * Retrieves Criterion Term
	 *
	 * @param	tx_icssitlorquery_Criterion $criterion
	 * @param	int $termID : ID Term
	 * @return Term
	 */
	public static function GetCriterionTerm(tx_icssitlorquery_Criterion $criterion, $termID) {
		if (!is_int($termID))
			throw new Exception('Term ID must be integer.');
			
		self::initialize();
		$terms = self::$criteriaTerms[$criterion->ID];
		for ($i=0; $i<$terms->Count(); $i++) {
			$term = $terms->Get($i);
			if ($term->ID == $termID) {
				return $term;
			}
		}
		return null;
	}
	
	/**
	 * Set connection parameters
	 *
	 * @param	string $login
	 * @param	string $password
	 * @param	string $url
	 * @return	void
	 */
	public static function SetConnectionParameters($login, $password, $url) {
		self::$criteria = null;
		self::$criteriaTerms = array();

		self::$login = $login;
		self::$password = $password;
		self::$url = $url;
	}
}