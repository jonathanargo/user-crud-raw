<?php

namespace Models;

use Exceptions\AttributeDoesNotExistException;
use Exceptions\AttribtuesAlreadyLoadedException;
use Components\AttributeValidator;
use Components\Database;


/**
 * Attribute hints for IDE convenience only
 * 
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $mobile_number
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $timezone
 * @property string $created
 * @property string $last_updated
 */
class User
{
    // Keeps track of whether our attributes have been loaded already.
    protected $loaded = false;

    /**
     * DB attributes.
     * 
     * I'm going to store these in an array rather than as class properties for easier manipulation
     * Normally bad practice, but this is what most ORMs do for that reason.
     */
    private $attributes = [
        'id' => '',
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'mobile_number' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => '',
        'timezone' => '',
        'created' => '',
        'last_updated' => '',
    ];

    /**
     * Attributes that can't be mass-assigned.
     * We don't want mass-assignment touching sensitive attributes like the ID or the timestamps
     */
    private $protectedAttributes = [
        'id',
        'created',
        'last_updated',
    ];

    // Validation errors
    protected $errors = [];

    /** @property Database $db */
    private $db;

    /**
     * @param array<string, string> $attributes to be assigned on construction
     */
    public function __construct(array $attributes = [], $db = null)
    {
        $this->assign($attributes);
        if ($db === null) {
            $db = Database::getInstance();
        }
        $this->db = $db;
    }

    /** 
     * Requirement: Get a single object property from the model
     * 
     * @param string $attribute
     */
    public function __get(string $attribute): string
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        throw new AttributeDoesNotExistException($attribute);
    }

    /**
     * Requirement: Set a single object property on the model
     * 
     * @param string $attribute
     * @param string $value
     */
    public function __set(string $attribute, $value)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            $this->attributes[$attribute] = $value;
        } else {
            throw new AttributeDoesNotExistException($attribute);
        }
    }


    /**
     * Requirement: Set object properties in bulk in the model
     * 
     * Sets all mass-fillable attributes
     */
    public function assign(array $attributes): void
    {
        foreach ($attributes as $key => $val) {
            if (!in_array($key, $this->protectedAttributes)) { // Only non-protected attributes
                $this->attributes[$key] = $val;
            }
        }
    }

    /**
     * Requirement: Get all object properties from the model
     */
    public function toArray($includeProtected = true): array
    {
        $result = $this->attributes;

        // Scrub the protected attributes
        if (!$includeProtected) {
            foreach ($this->protectedAttributes as $protectedAttribute) {
                unset($result[$protectedAttribute]);
            }
        }

        return $result;
    }

    /**
     * Returns true if the model has not yet been saved to the DB
     */
    public function isNewRecord(): bool
    {
        return empty($this->attributes['id']);
    }

    /**
     * These validation rules will be passed to the validator to validate the model
     */
    protected function getValidationRules(): array
    {
        return [
            'id' => ['required', 'integer', 'on:update'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string'],
            'mobile_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string', 'max:2'],
            'zip' => ['required', 'string'],
            'country' => ['required', 'string', 'max:2'],
            'timezone' => ['string'],
            // Note that we don't need to validate the MySQL timestamps. Handled by the DB for us.
        ];
    }

    /**
     * Requirement: Validation of the object’s properties
     */
    public function validate(): bool
    {
        // The context is important for certain validation rules that are only validated on insert or update.
        $context = 'insert';
        if (!$this->isNewRecord()) {
            $context = 'update';
        }
        
        $validator = new AttributeValidator();
        $result = $validator->validate($this->toArray(), $this->getValidationRules(), $context);
        $this->errors = $validator->getErrors();
        return $result;
    }

    /**
     * Returns model validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorsFlat(): array
    {
        $errors = [];
        foreach ($this->errors as $attribute => $attributeErrors) {
            foreach ($attributeErrors as $error) {
                $errors[] = $attribute.': '.$error;
            }
        }
        return $errors;
    }

    /**
     * Requirement - Fetch a row from the database that sets the model properties
     * 
     * Locates fetches the user attributes by the user ID and assigns them to the model
     * 
     * @param int $id
     * @throws AttribtuesAlreadyLoadedException if you've already loaded the model. Only call this once.
     */
    public function loadById(int $id) {
        if ($this->loaded) {
            // We don't allow the user to load the attributes from the DB more than once.
            throw new AttribtuesAlreadyLoadedException();
        }

        $users = $this->db->query('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
        $userRow = ($users[0] ?? null);
        if (!empty($userRow)){
            // First clear all existing attributes
            foreach ($this->attributes as $key => $val) {
                $this->attributes[$key] = '';
            }

            // Directly assign attributes from the row.
            foreach ($userRow as $key => $val) {
                $this->attributes[$key] = strval($val); // Need to cast to string for consistent handling of validation
            }
            return true;
        }
        return false;
    }

    /**
     * Loads all models in the table
     * 
     * @return array<User>
     */
    public static function findAll(): array
    {
        $users = [];

        $db = Database::getInstance();
        $userRows = $db->query('SELECT * FROM users');
        foreach ($userRows as $userRow) {
            $user = self::find($userRow['id']);
            if ($user instanceof User) {
                $users[] = $user;
            }
        }
        
        return $users;
    }

    /**
     * Requirement: Fetch a row from the database that sets the model properties
     * 
     * @return User|null Requested user model, null if not found
     */
    public static function find(int $id): User|null
    {
        $user = new User();
        if ($user->loadById($id)) {
            return $user;
        }
        return null;
    }

    /**
     * Requirement: Create a row in the database from properties in the model
     * Requirement: Update a row in the database from properties in the model
     * 
     * Saves the model to the DB.
     * 
     * @return bool
     */
    public function save(): bool
    {
        // TODO JSA - Need to make sure the timestamp is working
        // Validate all of the model validation rules.
        if (!$this->validate()) {
            return false; // Stop here if we failed validation
        }

        $attributes = $this->toArray(false); // We only want the attributes that aren't protected

        // insert or update the record
        if ($this->isNewRecord()) {
            $id = $this->db->insert('users', $attributes);
            $this->id = $id; // Populate ID
        } else {
            $this->db->updateById('users', $attributes, $this->id);
        }

        // Reload the model to get fresh data
        $this->loaded = false;
        $this->loadById($this->id);

        return true;
    }

    /**
     * Requirement: Delete a row in the database from the primary key in the model
     * 
     * Deletes the model
     * 
     * @return bool
     */
    public function delete(): bool
    {
        if ($this->isNewRecord()) {
            return false;
        }

        $this->db->deleteById('users', $this->id);
        $this->attributes = []; // Can no longer use this object at all

        return true;
    }

    /**
     * Returns the first_name and last_name of user
     */
    public function getFullName():string
    {
        return $this->first_name.' '.$this->last_name;
    }

    // None of these date formatting functions really belong in a model, but for the sake of time...

    /**
     * Returns the created timestamp formatted to locale
     */
    public function getFormattedCreated(): string
    {
        return $this->formatDatetime($this->created);
    }

    /** 
     * Returns the updated timestamp formatted to locale
     */
    public function getFormattedUpdated(): string
    {
        return $this->formatDatetime($this->last_updated);
    }

    private function formatDatetime(string $datetime): string
    {
        $locale = 'en_US';
        $formatter = new \IntlDateFormatter('en_US', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM);
        $pattern = $formatter->getPattern();
        return $formatter->format(strtotime($datetime));

    }
}