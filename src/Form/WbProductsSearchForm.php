<?php

declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * WbProductsSearch Form.
 */
class WbProductsSearchForm extends Form
{
    private const KEY_QUERY = 'query';

    public const MSG_NOT_EMPTY = 'Строка не должна быть пустой';

    public const MSG_MAX_LENGTH = 'Длина строки не должна превышать 255 символов';

    public const MSG_REGEX_ERROR = 'Строка может содержать только символы латиницы, кириллицы, цифры и пробелы';

    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField(self::KEY_QUERY, 'string');
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->notEmptyString(self::KEY_QUERY, self::MSG_NOT_EMPTY)
            ->regex('query', "/^([0-9a-zA-Zа-яёЁА-Я ]+)$/iu", self::MSG_REGEX_ERROR)
            ->maxLength(self::KEY_QUERY, 255, self::MSG_MAX_LENGTH);
    }

    /**
     * Defines what to execute once the Form is processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
