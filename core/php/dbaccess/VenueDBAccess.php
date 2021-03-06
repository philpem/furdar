<?php


namespace dbaccess;

use models\UserAccountModel;
use models\VenueEditMetaDataModel;
use models\VenueModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class VenueDBAccess
{

    /** @var  \PDO */
    protected $db;

    /** @var  \TimeSource */
    protected $timesource;


    public function __construct(Application $application)
    {
        $this->db = $application['db'];
        $this->timesource = $application['timesource'];
    }

    protected $possibleFields = array('title','lat','lng','description','address','address_code','country_id','area_id','is_duplicate_of_id','is_deleted');

    public function update(VenueModel $venue, array $fields, VenueEditMetaDataModel $venueEditMetaDataModel)
    {
        $alreadyInTransaction = $this->db->inTransaction();

        // Make Information Data
        $fieldsSQL1 = array( 'cached_updated_at = :cached_updated_at ');
        $fieldsParams1 = array( 'id'=>$venue->getId(), 'cached_updated_at'=> $this->timesource->getFormattedForDataBase()  );
        foreach ($fields as $field) {
            $fieldsSQL1[] = " ".$field."=:".$field." ";
            if ($field == 'title') {
                $fieldsParams1['title'] = substr($venue->getTitle(), 0, VARCHAR_COLUMN_LENGTH_USED);
            } elseif ($field == 'lat') {
                $fieldsParams1['lat'] = $venue->getLat();
            } elseif ($field == 'lng') {
                $fieldsParams1['lng'] = $venue->getLng();
            } elseif ($field == 'description') {
                $fieldsParams1['description'] = $venue->getDescription();
            } elseif ($field == 'address') {
                $fieldsParams1['address'] = $venue->getAddress();
            } elseif ($field == 'address_code') {
                $fieldsParams1['address_code'] = substr($venue->getAddressCode(), 0, VARCHAR_COLUMN_LENGTH_USED);
            } elseif ($field == 'country_id') {
                $fieldsParams1['country_id'] = $venue->getCountryId();
            } elseif ($field == 'area_id') {
                $fieldsParams1['area_id'] = $venue->getAreaId();
            } elseif ($field == 'is_duplicate_of_id') {
                $fieldsParams1['is_duplicate_of_id'] = $venue->getIsDuplicateOfId();
            } elseif ($field == 'is_deleted') {
                $fieldsParams1['is_deleted'] = ($venue->getIsDeleted()?1:0);
            }
        }

        // Make History Data
        $fieldsSQL2 = array('venue_id','user_account_id','created_at','approved_at','from_ip');
        $fieldsSQLParams2 = array(':venue_id',':user_account_id',':created_at',':approved_at',':from_ip');
        $fieldsParams2 = array(
            'venue_id'=>$venue->getId(),
            'user_account_id'=>($venueEditMetaDataModel->getUserAccount() ? $venueEditMetaDataModel->getUserAccount()->getId() : null),
            'created_at'=>$this->timesource->getFormattedForDataBase(),
            'approved_at'=>$this->timesource->getFormattedForDataBase(),
            'from_ip'=>$venueEditMetaDataModel->getIp(),
        );
        if ($venueEditMetaDataModel->getEditComment()) {
            $fieldsSQL2[] = ' edit_comment ';
            $fieldsSQLParams2[] = ' :edit_comment ';
            $fieldsParams2['edit_comment'] = $venueEditMetaDataModel->getEditComment();
        }
        foreach ($this->possibleFields as $field) {
            if (in_array($field, $fields) || $field == 'title') {
                $fieldsSQL2[] = " ".$field." ";
                $fieldsSQLParams2[] = " :".$field." ";
                if ($field == 'title') {
                    $fieldsParams2['title'] = substr($venue->getTitle(), 0, VARCHAR_COLUMN_LENGTH_USED);
                } elseif ($field == 'lat') {
                    $fieldsParams2['lat'] = $venue->getLat();
                } elseif ($field == 'lng') {
                    $fieldsParams2['lng'] = $venue->getLng();
                } elseif ($field == 'description') {
                    $fieldsParams2['description'] = $venue->getDescription();
                } elseif ($field == 'address') {
                    $fieldsParams2['address'] = $venue->getAddress();
                } elseif ($field == 'address_code') {
                    $fieldsParams2['address_code'] = substr($venue->getAddressCode(), 0, VARCHAR_COLUMN_LENGTH_USED);
                } elseif ($field == 'country_id') {
                    $fieldsParams2['country_id'] = $venue->getCountryId();
                } elseif ($field == 'area_id') {
                    $fieldsParams2['area_id'] = $venue->getAreaId();
                } elseif ($field == 'is_duplicate_of_id') {
                    $fieldsParams2['is_duplicate_of_id'] = $venue->getIsDuplicateOfId();
                } elseif ($field == 'is_deleted') {
                    $fieldsParams2['is_deleted'] = ($venue->getIsDeleted()?1:0);
                }
                $fieldsSQL2[] = " ".$field."_changed ";
                $fieldsSQLParams2[] = " 0 ";
            } else {
                $fieldsSQL2[] = " ".$field."_changed ";
                $fieldsSQLParams2[] = " -2 ";
            }
        }

        try {
            if (!$alreadyInTransaction) {
                $this->db->beginTransaction();
            }

            // Information SQL
            $stat = $this->db->prepare("UPDATE venue_information  SET ".implode(",", $fieldsSQL1)." WHERE id=:id");
            $stat->execute($fieldsParams1);

            // History SQL
            $stat = $this->db->prepare("INSERT INTO venue_history (".implode(",", $fieldsSQL2).") VALUES (".implode(",", $fieldsSQLParams2).")");
            $stat->execute($fieldsParams2);

            if (!$alreadyInTransaction) {
                $this->db->commit();
            }
        } catch (Exception $e) {
            if (!$alreadyInTransaction) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
