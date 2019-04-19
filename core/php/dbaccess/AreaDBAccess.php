<?php


namespace dbaccess;

use models\AreaEditMetaDataModel;
use models\UserAccountModel;
use models\AreaModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class AreaDBAccess
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

    protected $possibleFields = array('title','description',
        'parent_area_id',
        'is_duplicate_of_id',
        'is_deleted',
        'country_id',
        'min_lat','max_lat','min_lng','max_lng');

    public function update(AreaModel $area, array $fields, AreaEditMetaDataModel $areaEditMetaDataModel)
    {
        $alreadyInTransaction = $this->db->inTransaction();

        // Make Information Data
        $fieldsSQL1 = array( 'cached_updated_at = :cached_updated_at ');
        $fieldsParams1 = array( 'id'=>$area->getId() , 'cached_updated_at'=> $this->timesource->getFormattedForDataBase() );
        foreach ($fields as $field) {
            $fieldsSQL1[] = " ".$field."=:".$field." ";
            if ($field == 'title') {
                $fieldsParams1['title'] = substr($area->getTitle(), 0, VARCHAR_COLUMN_LENGTH_USED);
            } elseif ($field == 'description') {
                $fieldsParams1['description'] = $area->getDescription();
            } elseif ($field == 'parent_area_id') {
                $fieldsParams1['parent_area_id'] = $area->getParentAreaId();
            } elseif ($field == 'country_id') {
                $fieldsParams1['country_id'] = $area->getCountryId();
            } elseif ($field == 'is_duplicate_of_id') {
                $fieldsParams1['is_duplicate_of_id'] = $area->getIsDuplicateOfId();
            } elseif ($field == 'is_deleted') {
                $fieldsParams1['is_deleted'] = ($area->getIsDeleted()?1:0);
            } elseif ($field == 'min_lat') {
                $fieldsParams1['min_lat'] = $area->getMinLat();
            } elseif ($field == 'max_lat') {
                $fieldsParams1['max_lat'] = $area->getMaxLat();
            } elseif ($field == 'min_lng') {
                $fieldsParams1['min_lng'] = $area->getMinLng();
            } elseif ($field == 'max_lng') {
                $fieldsParams1['max_lng'] = $area->getMaxLng();
            }
        }

        // Make History Data
        $fieldsSQL2 = array('area_id','user_account_id','created_at','approved_at','from_ip');
        $fieldsSQLParams2 = array(':area_id',':user_account_id',':created_at',':approved_at',':from_ip');
        $fieldsParams2 = array(
            'area_id'=>$area->getId(),
            'user_account_id'=>($areaEditMetaDataModel->getUserAccount() ? $areaEditMetaDataModel->getUserAccount()->getId() : null),
            'created_at'=>$this->timesource->getFormattedForDataBase(),
            'approved_at'=>$this->timesource->getFormattedForDataBase(),
            'from_ip'=>$areaEditMetaDataModel->getIp(),
        );
        if ($areaEditMetaDataModel->getEditComment()) {
            $fieldsSQL2[] = ' edit_comment ';
            $fieldsSQLParams2[] = ' :edit_comment ';
            $fieldsParams2['edit_comment'] = $areaEditMetaDataModel->getEditComment();
        }
        foreach ($this->possibleFields as $field) {
            if (in_array($field, $fields) || $field == 'title') {
                $fieldsSQL2[] = " ".$field." ";
                $fieldsSQLParams2[] = " :".$field." ";
                if ($field == 'title') {
                    $fieldsParams2['title'] = substr($area->getTitle(), 0, VARCHAR_COLUMN_LENGTH_USED);
                } elseif ($field == 'description') {
                    $fieldsParams2['description'] = $area->getDescription();
                } elseif ($field == 'country_id') {
                    $fieldsParams2['country_id'] = $area->getCountryId();
                } elseif ($field == 'parent_area_id') {
                    $fieldsParams2['parent_area_id'] = $area->getParentAreaId();
                } elseif ($field == 'is_duplicate_of_id') {
                    $fieldsParams2['is_duplicate_of_id'] = $area->getIsDuplicateOfId();
                } elseif ($field == 'is_deleted') {
                    $fieldsParams2['is_deleted'] = ($area->getIsDeleted()?1:0);
                } elseif ($field == 'min_lat') {
                    $fieldsParams2['min_lat'] = $area->getMinLat();
                } elseif ($field == 'max_lat') {
                    $fieldsParams2['max_lat'] = $area->getMaxLat();
                } elseif ($field == 'min_lng') {
                    $fieldsParams2['min_lng'] = $area->getMinLng();
                } elseif ($field == 'max_lng') {
                    $fieldsParams2['max_lng'] = $area->getMaxLng();
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
            $stat = $this->db->prepare("UPDATE area_information  SET ".implode(",", $fieldsSQL1)." WHERE id=:id");
            $stat->execute($fieldsParams1);

            // History SQL
            $stat = $this->db->prepare("INSERT INTO area_history (".implode(",", $fieldsSQL2).") VALUES (".implode(",", $fieldsSQLParams2).")");
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
