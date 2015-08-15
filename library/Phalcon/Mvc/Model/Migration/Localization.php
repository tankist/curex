<?php

namespace Library\Phalcon\Mvc\Migration;

require_once __DIR__ . '/../../../../functions.php';

use Phalcon\Db as PhalconDb;

/**
 * Class Localization
 *
 * @property \Phalcon\Db\Adapter\Pdo\Mysql $_connection
 * @package Library\Phalcon\Mvc\Migration
 */
trait Localization
{

    /**
     * @var array
     */
    protected $_localesList;

    /**
     *
     */
    public function up()
    {

    }

    /**
     * @throws \Exception
     */
    public function afterUp()
    {
        /** @var \Phalcon\Db\Adapter\Pdo\Mysql $connection */
        $connection = static::$_connection;

        try {
            $connection->begin();

            foreach ($this->_getData() as $placeholder => $placeholderData) {
                // add new localization entity | start
                $l10nData = [
                    'placeholder' => $placeholder,
                    'text' => $placeholderData['text'],
                    'translated' => '1',
                    'scope' => 'frontend',
                ];

                $sql = sprintf('SELECT * FROM `%s` WHERE `placeholder` = ? LIMIT 1;', $this->_getLocalizationsTableName(), $placeholder);
                $l10n = $connection->fetchOne($sql, \Phalcon\Db::FETCH_ASSOC, [$placeholder]);

                if (!$l10n) {
                    $connection->insert($this->_getLocalizationsTableName(), array_values($l10nData), array_keys($l10nData));
                    $l10nId = $connection->lastInsertId();
                } else {
                    $l10nId = (int)$l10n['id'];
                    $connection->update($this->_getLocalizationsTableName(), array_keys($l10nData), array_values($l10nData), "id = '{$l10nId}'");
                }
                // add new localization entity | end

                // delete all translations for current localization entity
                $connection->delete($this->_getExtTranslationsTableName(), 'foreign_key = ?', [$l10nId]);

                // add translations for localization entity | start
                $translationData = [
                    'locale' => '',
                    'object_class' => 'Entities\Localization',
                    'field' => 'text',
                    'foreign_key' => $l10nId,
                    'content' => $l10nData['text'],
                    'portal_id' => null,
                ];

                // set default translations for all locales
                foreach ($this->_getLocalesList() as $locale) {
                    $translationData['locale'] = $locale;
                    $connection->insert($this->_getExtTranslationsTableName(), array_values($translationData), array_keys($translationData));
                }

                // update translations for each locale that we have in the placeholders data
                if (empty($placeholderData['locale'])) {
                    continue;
                }

                // prepare locales to SQL query
                $locales = join(',', array_map(function ($item) {
                    return "'{$item}'";
                }, array_keys($placeholderData['locale'])));

                // delete translations that we have in the placeholders data
                $connection->delete($this->_getExtTranslationsTableName(), 'foreign_key = ? AND locale IN (?)', [$l10nId, $locales]);

                // set translations for each locale that we have in the placeholders data
                foreach ($placeholderData['locale'] as $locale => $content) {
                    $translationData['locale'] = $locale;
                    $translationData['content'] = $content;
                    $connection->insert($this->_getExtTranslationsTableName(), array_values($translationData), array_keys($translationData));
                }
                // add translations for localization entity | end
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }
    }

    /**
     * @return array
     */
    protected function _getLocalesList()
    {
        if (null === $this->_localesList) {
            /** @var \Phalcon\Db\Adapter\Pdo\Mysql $connection */
            $connection = static::$_connection;

            $this->_localesList = [];
            if ($localeList = $connection->fetchAll('SELECT * FROM `countries`;', \Phalcon\Db::FETCH_ASSOC)) {
                $this->_localesList = array_column($localeList, 'locale_code');
                $this->_localesList = array_map('strtolower', $this->_localesList);
            }
        }
        return $this->_localesList;
    }

    /**
     * @return string table name
     */
    protected function _getLocalizationsTableName()
    {
        return 'localizations';
    }

    /**
     * @return string table name
     */
    protected function _getExtTranslationsTableName()
    {
        return 'ext_translations';
    }

    /**
     * Example:
     * return [
     *     '[PLACEHOLDER]' => [
     *         'text' => 'default text',
     *         'locale' => [
     *             'de_de' => 'text for de_de',
     *             'fr_fr' => 'text for fr_fr',
     *         ],
     *     ],
     * ];
     *
     * @return array
     */
    abstract protected function _getData();

}
