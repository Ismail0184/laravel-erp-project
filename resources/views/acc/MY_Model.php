<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://cibonfire.com/docs/guides/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Bonfire Base Model
 *
 * The Base model implements standard CRUD functions that can be
 * used and overriden by module models. This helps to maintain
 * a standard interface to program to, and makes module creation
 * faster.
 *
 * @package    Bonfire
 * @subpackage MY_Model
 * @category   Models
 * @author     Lonnie Ezell
 * @link       http://cibonfire.com/docs/guides/models.html
 *
 */
class BF_Model extends CI_Model
{

	/**
	 * Stores custom errors that can be used in UI error reporting.
	 *
	 * @var string
	 * @access public
	 */
	public $error 		= '';

	/**
	 * The name of the db table this model primarily uses.
	 *
	 * @var string
	 * @access protected
	 */
	protected $table_name 	= '';

	/**
	 * The primary key of the table. Used as the 'id' throughout.
	 *
	 * @var string
	 * @access protected
	 */
	protected $key		= 'id';

	/**
	 * Field name to use to the created time column in the DB table.
	 *
	 * @var string
	 * @access protected
	 */
	protected $created_field = 'created_on';

	/**
	 * Field name to use to the modified time column in the DB table.
	 *
	 * @var string
	 * @access protected
	 */
	protected $modified_field = 'modified_on';

    /**
     * Field name to use for the deleted column in the DB table if $soft_deletes is enabled
     *
     * @var string
     * @access protected
     */
    protected $deleted_field = 'deleted';

	/**
	 * Whether or not to auto-fill a 'created_on' field on inserts.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_created	= TRUE;

	/**
	 * Whether or not to auto-fill a 'modified_on' field on updates.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_modified = TRUE;

	/**
	 * If TRUE, will log user id for 'created_by', 'modified_by', and 'deleted_by'
	 *
	 * @var bool
	 * @access protected
	 */
	protected $log_user = FALSE;

	/**
	 * Field name to use as the created by column in the DB table
	 *
	 * @var string
	 * @access protected
	 */
	protected $created_by_field = 'created_by';

	/**
	 * Field name to use as the modified by column in the DB table
	 *
	 * @var string
	 * @access protected
	 */
	protected $modified_by_field = 'modified_by';

	/**
	 * Field name to use as the deleted by column in the DB table
	 *
	 * @var string
	 * @access protected
	 */
	protected $deleted_by_field = 'deleted_by';

	/**
	 * The type of date/time field used for created_on and modified_on fields.
	 * Valid types are: 'int', 'datetime', 'date'
	 *
	 * @var string
	 * @access protected
	 */
	protected $date_format = 'int';

	/**
	 * If FALSE, the delete() method will perform a TRUE delete of that row.
	 * If TRUE, a 'deleted' field will be set to 1.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $soft_deletes = TRUE;

	/**
	 * Stores any selects here for use by the find* functions.
	 *
	 * @var string
	 * @access protected
	 */
	protected $selects = '';

	/**
	 * If FALSE, the select() method will not try to protect your field or table names with backticks.
	 * This is useful if you need a compound select statement.
	 *
	 * @var bool
	 * @access protected
	 */
	protected $escape = TRUE;

	/**
	 * DB Connection details (string or array)
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $db_con = '';

	/**
	 * Observer Arrays
	 *
	 * Each array can contain the names of callback functions within the extending model
	 * That will be called during each event.
	 *
	 * <code>
	 *	$before_insert = array('set_created', 'validate_fields');
	 * </code>
	 *
	 * $before_insert contains the names of callback functions within the extending model
	 * which will be called before the insert method.
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_insert	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the insert method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_insert		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_update	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_update		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_find		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_find		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_delete	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_delete		= array();

    /**
     * Contains the names of callback functions within the extending model which
     * will be called if $validation_rules is empty (or not an array) when
     * requested via the get_validation_rules() method.
     *
     * Note: These methods should not add $insert_validation_rules, as they are
     * added to the $validation_rules after these functions return.
     *
	 * @see $before_insert
	 *
     * @var array
	 * @access protected
     */
    protected $empty_validation_rules = array();

	/**
     * Protected, non-modifiable attributes
     *
     * @var array
     * @access protected
     */
    protected $protected_attributes = array();

    /**
     * By default, we return items as objects. You can change this for the
     * entire class by setting this value to 'array' instead of 'object'.
     * Alternatively, you can do it on a per-instance basis using the
     * 'as_array()' and 'as_object()' methods.
     *
     * @var string
     * @access protected
     */
    protected $return_type      = 'object';

	/**
	 * Holds the return type temporarily when using the
	 * as_array() and as_object() methods
	 *
	 * @var string
	 * @access protected
	 */
    protected $temp_return_type = NULL;

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     *
     * @see http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#validationrulesasarray
     */
    protected $validation_rules = array();

    /**
     * @var Array Additional Validation rules only used on insert
     */
    protected $insert_validation_rules = array();

    /**
     * Optionally skip the validation. Used in conjunction with
     * skip_validation() to skip data validation for any future calls.
     */
    protected $skip_validation = FALSE;

    /**
     * If TRUE, inserts will return the last_insert_id. However, this can
     * potentially slow down large imports drastically, so you can turn it off
     * with the return_insert_id(false) method.
     *
     * This will also disable after_insert, since the observer receives the last_insert_id
     */
    protected $return_insert_id = true;

    /**
     * @var array Metadata for the model's database fields
     *
     * This can be set to avoid a database call if using $this->prep_data()
     * and/or $this->get_field_info().
     *
     * @see http://ellislab.com/codeigniter/user-guide/database/fields.html
     *
     * Each field's definition should be as follows:
        array(
            'name'            => $field_name,
            'type'            => $field_data_type,
            'default'         => $field_default_value,
            'max_length'      => $field_max_length,
            'primary_key'     => (1 if the column is a primary key),
        ),
     */
    protected $field_info = array();

    //--------------------------------------------------------------------

	/**
	 * BF_Model's constructor
	 *
	 * Setup the DB connection if it doesn't exist,
	 * setup the before_insert and before_update events
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// if there are specific DB connection settings used in a model
		// load the database using those settings.
		if (!empty($this->db_con)) {

			$this->db = $this->load->database($this->db_con, TRUE);
		}

		// If we're loading the model, then we probably need the
		// database, so make sure it's loaded.
		if (!isset($this->db))
		{
			$this->load->database();
		}

        // if the $field_info property is set, convert it from an array of
        // arrays to an array of objects
        if ( ! empty($this->field_info)) {
            foreach ($this->field_info as $key => &$field) {
                $this->field_info[$key] = (object) $field;
            }
        }

		// Always protect our attributes
        array_unshift($this->before_insert, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

		// Check our auto-set features and make sure they are part of
		// our observer system.
		if ($this->set_created === true) array_unshift($this->before_insert, 'created_on');
		if ($this->set_modified === true) array_unshift($this->before_update, 'modified_on');

	}//end __construct()

	//---------------------------------------------------------------

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object/array representing the db row, or FALSE.
	 */
	public function find($id='')
	{
		$this->trigger('before_find');

		$query = $this->db->get_where($this->table_name, array($this->table_name . '.' . $this->key => $id));

		if ( ! $query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type()}();

		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

        // Reset our return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find()

	//---------------------------------------------------------------

	/**
	 * Returns all records in the table.
	 *
	 * By default, there is no 'where' clause, but you can filter
	 * the results that are returned by using either CodeIgniter's
	 * Active Record functions before calling this function, or
	 * through method chaining with the where() method of this class.
	 *
	 * @return mixed An array of objects/arrays representing the results, or FALSE on failure or empty set.
	 */
	public function find_all()
	{
		$this->trigger('before_find');

		$query = $this->db->get($this->table_name);

		if (!$query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type(true)}();

		if (is_array($return))
		{
            $last_record = count($return) - 1;
			foreach ($return as $key => &$row)
			{
				$row = $this->trigger('after_find', $row, ($key == $last_record));
			}
		}

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

        // Reset our return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find_all()

	//---------------------------------------------------------------

	/**
	 * A convenience method that combines a where() and find_all() call into a single call.
	 *
	 * @param mixed  $field The table field to search in.
	 * @param mixed  $value The value that field should be.
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 *
	 * @return bool|mixed An array of objects representing the results, or FALSE on failure or empty set.
	 */
	public function find_all_by($field=NULL, $value=NULL, $type='and')
	{
		if (empty($field))
		{
			return FALSE;
		}

		// Setup our field/value check
		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if ($type == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		return $this->find_all();

	}//end find_all_by()

	//--------------------------------------------------------------------

	/**
	 * Returns the first result that matches the field/values passed.
	 *
	 * @param string $field Either a string or an array of fields to match against. If an array is passed it, the $value parameter is ignored since the array is expected to have key/value pairs in it.
	 * @param string $value The value to match on the $field. Only used when $field is a string.
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 *
	 * @return bool|mixed An object representing the first result returned.
	 */
	public function find_by($field='', $value='', $type='and')
	{
		if (empty($field) || ( ! is_array($field) && empty($value)))
		{
			$this->error = lang('bf_model_find_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_find_error'));
			return FALSE;
		}

		$this->trigger('before_find');

		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if ($type == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		$query = $this->db->get($this->table_name);

		if ( ! $query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type()}();

		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

		// Reset our return type
        $this->temp_return_type = $this->return_type;

        return $return;
	}//end find_by()

	//---------------------------------------------------------------

	/**
	 * Inserts a row of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool|mixed Either the $id of the row inserted, or FALSE on failure.
	 */
	public function insert($data=null)
	{
		if ($this->skip_validation === false) {
		    $data = $this->validate($data, 'insert');
            if ($data === false) {
                return false;
            }
		}

		$data = $this->trigger('before_insert', $data);

		if ($this->set_created === true && $this->log_user === true
            && ! array_key_exists($this->created_by_field, $data)
           ) {
			$data[$this->created_by_field] = $this->auth->user_id();
		}

		// Insert it
		$status = $this->db->insert($this->table_name, $data);

		if ($status == false) {
			$this->error = $this->get_db_error_message();
        } elseif ($this->return_insert_id) {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
        }

        return $status;

	}//end insert()

	//---------------------------------------------------------------

	/**
	 * Inserts a batch of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool|mixed Either the $id of the row inserted, or FALSE on failure.
	 *
	 * @todo Check the code before the section marked "Insert it".
	 * 'before_insert' should trigger the 'created_on' method, so we either
	 * shouldn't set $this->created_field in $set, or we should merge $set
	 * before we trigger 'before_insert'.
	 * Additionally, shouldn't the merge be:
	 *  $data[$key] = array_merge($set, $record)
	 *  or
	 *  $record = array_merge($set, $record)
	 * ?
	 */
	public function insert_batch($data=null)
	{
		$set = array();

		// Add the created field
		if ($this->set_created === true) {
			$set[$this->created_field] = $this->set_date();
		}

		if ($this->set_created === true && $this->log_user === true) {
			$set[$this->created_by_field] = $this->auth->user_id();
		}

		if ( ! empty($set)) {
			foreach ($data as $key => &$record) {
				$record = $this->trigger('before_insert', $record);

				$data[$key] = array_merge($set, $data[$key]);
			}
		}

		// Insert it
		$status = $this->db->insert_batch($this->table_name, $data);

		if ($status === false) {
			$this->error = $this->get_db_error_message();
			return false;
		}

		return true;

	}//end insert_batch()

	//---------------------------------------------------------------

	/**
	 * Updates an existing row in the database.
	 *
	 * @param mixed	$where	The primary_key value of the row to update or the where clause.
	 * @param array $data	An array of key/value pairs to update.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update($where=NULL, $data=NULL)
	{
		if ($this->skip_validation === FALSE)
		{
		    $data = $this->validate($data);
		}

		if ( ! is_array($where))
		{
			$where = array($this->key => $where);
		}

		$data = $this->trigger('before_update', $data);

		// Add the user id if using a modified_by field
		if ($this->set_modified === TRUE && $this->log_user === TRUE && !array_key_exists($this->modified_by_field, $data))
		{
			$data[$this->modified_by_field] = $this->auth->user_id();
		}

		if ($result = $this->db->update($this->table_name, $data, $where))
		{
			$this->trigger('after_update', array($data, $result));
			return TRUE;
		}

		return FALSE;

	}//end update()

	//---------------------------------------------------------------

	/**
	 * A convenience method that allows you to use any field/value pair as the 'where' portion of your update.
	 *
	 * @param string $field The field to match on.
	 * @param string $value The value to search the $field for.
	 * @param array  $data  An array of key/value pairs to update.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_where($field=NULL, $value=NULL, $data=NULL)
	{
		return $this->update(array($field => $value), $data);
	}//end update_where()

	//---------------------------------------------------------------

	/**
	 * Updates a batch of existing rows in the database.
	 *
	 * @param array  $data  An array of key/value pairs to update.
	 * @param string $index A string value of the db column to use as the where key
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_batch($data = NULL, $index = NULL)
	{
		if (is_null($index) || is_null($data))
		{
			return FALSE;
		}

        // Add the modified field
        if ($this->set_modified === TRUE && !array_key_exists($this->modified_field, $data))
        {
            foreach ($data as $key => $record)
            {
                $data[$key][$this->modified_field] = $this->set_date();
                if ($this->log_user === TRUE && !array_key_exists($this->modified_by_field, $data[$key]))
                {
                    $data[$key][$this->modified_by_field] = $this->auth->user_id();
                }
            }
        }

        $result = $this->db->update_batch($this->table_name, $data, $index);
        if (empty($result))
        {
            return TRUE;
        }

		return FALSE;

	}//end update_batch()

	//--------------------------------------------------------------------


	/**
	 * Performs a delete on the record specified. If $this->soft_deletes is TRUE,
	 * it will attempt to set $this->deleted_field on the current record
	 * to '1', to allow the data to remain in the database.
	 *
	 * @param mixed $id The primary_key value to match against.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete($id=NULL)
	{
		$this->trigger('before_delete', $id);

		// set the where clause to be used in the update/delete below
		$this->db->where($this->key, $id);

		if ($this->soft_deletes === TRUE)
		{
			$data = array(
				$this->deleted_field => 1,
			);

			if ($this->log_user === TRUE)
			{
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$result = $this->db->update($this->table_name, $data);
		}
		else
		{
			$result = $this->db->delete($this->table_name);
		}

		if ($result)
		{
			$this->trigger('after_delete', $id);
			return TRUE;
		}

		$this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());

		return FALSE;

	}//end delete()

	//---------------------------------------------------------------

	/**
	 * Performs a delete using any field/value pair(s) as the 'where'
	 * portion of your delete statement. If $this->soft_deletes is
	 * TRUE, it will attempt to set $this->deleted_field on the current
	 * record to '1', to allow the data to remain in the database.
	 *
	 * @param mixed/array $data key/value pairs accepts an associative array or a string
	 *
	 * @example 1) array( 'key' => 'value', 'key2' => 'value2' )
	 * @example 2) ' (`key` = "value" AND `key2` = "value2") '
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete_where($where=NULL)
	{
		$where = $this->trigger('before_delete', $where);

		// set the where clause to be used in the update/delete below
		$this->db->where($where);

		if ($this->soft_deletes === TRUE)
		{
			$data = array(
				$this->deleted_field => 1,
			);

			if ($this->log_user === TRUE)
			{
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$this->db->update($this->table_name, $data);
		}
		else
		{
			$this->db->delete($this->table_name);
		}

		$result = $this->db->affected_rows();

		if ($result)
		{
			$this->trigger('after_delete', $result);

			return $result;
		}

		$this->error = lang('bf_model_db_error') . $this->get_db_error_message();

		return FALSE;

	}//end delete_where()

	//---------------------------------------------------------------

	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Checks whether a field/value pair exists within the table.
	 *
	 * @param string $field The field to search for.
	 * @param string $value The value to match $field against.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value))
		{
			$this->error = lang('bf_model_unique_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_unique_error'));
			return FALSE;
		}

		$this->db->where($field, $value);
		$query = $this->db->get($this->table_name);

		if ($query && $query->num_rows() == 0)
		{
			return TRUE;
		}

		return FALSE;

	}//end is_unique()

	//---------------------------------------------------------------

	/**
	 * Returns the number of rows in the table.
	 *
	 * @internal This is potentially confusing given that count_all()
	 * and count_all_results() are different methods on $this->db,
	 * with the difference being that count_all_results() is
	 * modified by previous use of where(), like(), etc., while
	 * count_all() is not
	 *
	 * @return int
	 */
	public function count_all()
	{
		return $this->db->count_all_results($this->table_name);

	}//end count_all()

	//---------------------------------------------------------------

	/**
	 * Returns the number of elements that match the field/value pair.
	 *
	 * @param string $field The field to search for.
	 * @param string $value The value to match $field against.
	 *
	 * @return bool|int
	 */
	public function count_by($field='', $value=NULL)
	{
		if (empty($field))
		{
			$this->error = lang('bf_model_count_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_count_error'));
			return FALSE;
		}

		$this->db->where($field, $value);

		return (int)$this->db->count_all_results($this->table_name);

	}//end count_by()

	//---------------------------------------------------------------

	/**
	 * A convenience method to return only a single field of the specified row.
	 *
	 * @param mixed  $id    The primary_key value to match against.
	 * @param string $field The field to search for.
	 *
	 * @return bool|mixed The value of the field.
	 */
	public function get_field($id=NULL, $field='')
	{
		if (empty($id) || empty($field))
		{
			//$this->error = lang('bf_model_fetch_error');
			//$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_fetch_error'));
			return FALSE;
		}

		$query = $this->db->select($field)
			->where($this->key, $id)
			->get($this->table_name);

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->$field;
		}

		return FALSE;

	}//end get_field()

	//---------------------------------------------------------------

	/**
	 * A convenience method to return options for form dropdown menus.
	 *
	 * Can pass either Key ID and Label Table names or Just Label Table name.
	 *
	 * @return array The options for the dropdown.
	 */
	public function format_dropdown()
	{
		$args = & func_get_args();

		if (count($args) == 2)
		{
			list($key, $value) = $args;
		}
		else
		{
			$key = $this->key;
			$value = $args[0];
		}

		$query = $this->db->select(array($key, $value))->get($this->table_name);

		$options = array();
		foreach ($query->result() as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		return $options;

	}//end format_dropdown()

	//--------------------------------------------------------------------
	// !CHAINABLE UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Sets the where portion of the query in a chainable format.
	 *
	 * @param mixed  $field The field to search the db on. Can be either a string with the field name to search, or an associative array of key/value pairs.
	 * @param string $value The value to match the field against. If $field is an array, this value is ignored.
	 *
	 * @return BF_Model An instance of this class.
	 */
	public function where($field=NULL, $value=NULL)
	{
		if ( ! empty($field))
		{
			if (is_string($field))
			{
				$this->db->where($field, $value);
			}
			elseif (is_array($field))
			{
				$this->db->where($field);
			}
		}

		return $this;

	}//end where()

	//--------------------------------------------------------------------

	/**
	 * Inserts a chainable order_by method from either a string or an
	 * array of field/order combinations. If the $field value is an array,
	 * it should look like:
	 *
	 * array(
	 *     'field1' => 'asc',
	 *     'field2' => 'desc'
	 * );
	 *
	 * @param mixed  $field The field to order the results by, or an array of field/order pairs.
	 * @param string $order Which direction to order the results ('asc' or 'desc')
	 *
	 * @return BF_Model An instance of this class.
	 */
	public function order_by($field=NULL, $order='asc')
	{
		if ( ! empty($field))
		{
			if (is_string($field))
			{
				$this->db->order_by($field, $order);
			}
			elseif (is_array($field))
			{
				foreach ($field as $f => $o)
				{
					$this->db->order_by($f, $o);
				}
			}
		}

		return $this;

	}//end order_by()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Scope Methods
	//--------------------------------------------------------------------

	/**
	 * Sets the value of the soft deletes flag.
	 *
	 * <code>
	 *     $this->my_model->soft_delete(true)->delete($id);
	 * </code>
	 *
	 * @param  boolean $val If TRUE, will temporarily use soft_deletes.
	 *
	 * @return BF_Model 	An instance of this class.
	 */
	public function soft_delete($val=TRUE)
	{
		$this->soft_deletes = (boolean)$val;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
     * Temporarily sets our return type to an array.
     */
    public function as_array()
    {
        $this->temp_return_type = 'array';

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets our return type to an object.
     */
    public function as_object()
    {
        $this->temp_return_type = 'object';

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets our object return to a json object.
     */
    public function as_json()
    {
        $this->temp_return_type = 'json';

        return $this;
    }

	//--------------------------------------------------------------------

    /**
     * Sets the value of the return_insert_id flag
     *
     * @param Bool $return (optional) whether insert will return the ID
     *
     * @return Object    returns $this to allow method chaining
     */
	public function return_insert_id($return=true)
	{
	    $this->return_insert_id = (bool)$return;

	    return $this;
	}

	//--------------------------------------------------------------------

    /**
     * Sets the value of the skip_validation flag
     *
     * @param Bool $skip (optional) whether to skip validation in the model
     *
     * @return Object    returns $this to allow method chaining
     */
	public function skip_validation($skip=true)
	{
	    $this->skip_validation = $skip;

	    return $this;
	}

	//--------------------------------------------------------------------
	// !OBSERVERS
	//--------------------------------------------------------------------

	/**
	 * Sets the created on date for the object based on the
	 * current date/time and date_format. Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array
	 */
	public function created_on($row)
	{
		if ( ! array_key_exists($this->created_field, $row))
		{
			$row[$this->created_field] = $this->set_date();
		}

		return $row;
	} // end created_on()

	//--------------------------------------------------------------------

	/**
	 * Sets the modified_on date for the object based on the
	 * current date/time and date_format. Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array
	 */
	public function modified_on($row)
	{
		if ( ! array_key_exists($this->modified_field, $row))
		{
			$row[$this->modified_field] = $this->set_date();
		}

		return $row;
	}

	//--------------------------------------------------------------------


	//---------------------------------------------------------------
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Triggers a model-specific event and call each of it's observers.
	 *
	 * @param string 	$event 	The name of the event to trigger
	 * @param mixed 	$data 	The data to be passed to the callback functions.
	 *
	 * @return mixed
	 */
	public function trigger($event, $data=false)
	{
		if ( ! isset($this->$event) || ! is_array($this->$event))
		{
			return $data;
		}

		foreach ($this->$event as $method)
		{
			if (strpos($method, '('))
			{
				preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
				$this->callback_parameters = explode(',', $matches[3]);
			}

			$data = call_user_func_array(array($this, $method), array($data));
		}

		return $data;
	}

	//--------------------------------------------------------------------

    /**
     * Get the validation rules for the model
     *
     * @uses $empty_validation_rules Observer to generate validation rules if they are empty
     *
     * @param String $type Either 'update' or 'insert', appends rules set in $insert_validation_rules on insert
     *
     * @return array    The validation rules for the model or an empty array
     */
    public function get_validation_rules($type='update')
    {
        $temp_validation_rules = $this->validation_rules;

        /*
         * When $validation_rules is empty (or not an array), try to generate
         * them by triggering the $empty_validation_rules observer
         *
         * If the observer returns a non-empty array, set $validation_rules so
         * they aren't re-generated for this instance of the model
         */

        if (empty($temp_validation_rules) || ! is_array($temp_validation_rules))
        {
            $temp_validation_rules = $this->trigger('empty_validation_rules', $temp_validation_rules);
            if (empty($temp_validation_rules) || ! is_array($temp_validation_rules))
            {
                return array();
            }

            $this->validation_rules = $temp_validation_rules;
        }

        // Any insert additions?
        if ($type == 'insert'
            && is_array($this->insert_validation_rules)
            && ! empty($this->insert_validation_rules)
           ) {
            // Get the index for each field in the validation rules
            $fieldIndexes = array();
            foreach ($temp_validation_rules as $key => $validation_rule) {
                $fieldIndexes[$validation_rule['field']] = $key;
            }

            foreach ($this->insert_validation_rules as $key => $rule) {
                if (is_array($rule)) {
                    $insert_rule = $rule;
                } else {
                    // if $key isn't a field name and $insert_rule isn't an
                    // array, we probably can't do anything useful, so skip it
                    if (is_numeric($key)) {
                        continue;
                    }

                    $insert_rule = array(
                        'field' => $key,
                        'rules' => $rule,
                    );
                }

                /*
                 * if the field is already in the validation rules,
                 * we update the validation rule to merge the insert rule
                 * (or replace an empty rule)
                 */
                if (isset($fieldIndexes[$insert_rule['field']])) {
                    $fieldKey = $fieldIndexes[$insert_rule['field']];
                    if (empty($temp_validation_rules[$fieldKey]['rules'])) {
                        $temp_validation_rules[$fieldKey]['rules'] = $insert_rule['rules'];
                    } else {
                        $temp_validation_rules[$fieldKey]['rules'] .= '|' . $insert_rule['rules'];
                    }
                } else {
                    // Otherwise we add the insert rule to the validation rules
                    $temp_validation_rules[] = $insert_rule;
                }
            }
        }

        return $temp_validation_rules;
    }

	//--------------------------------------------------------------------

	/**
	 * Validates the data passed into it based upon the form_validation rules
	 * setup in the $this->validation_rules property.
	 *
	 * If $type == 'insert', any additional rules in the class var $insert_validation_rules
	 * for that field will be added to the rules.
	 *
	 * @param  array $data      An array of data to validate
	 * @param  string $type     Either 'update' or 'insert'.
	 * @return array/bool       The original data or FALSE
	 */
	public function validate($data, $type='update')
	{
	    if ($this->skip_validation) {
	        return $data;
	    }

        $current_validation_rules = $this->get_validation_rules($type);

        if (empty($current_validation_rules)) {
            return $data;
        }

        foreach ($data as $key => $val) {
            $_POST[$key] = $val;
        }

        $this->load->library('form_validation');

        /*
         * $current_validation_rules can be an array of rules, which we pass
         * to set_rules(), or a string that is passed to run(), which will
         * attempt to load the rules from a config file (run() will ignore
         * the input otherwise)
         */
        if (is_array($current_validation_rules)) {
            $this->form_validation->set_rules($current_validation_rules);
            $valid = $this->form_validation->run();
        } else {
            $valid = $this->form_validation->run($current_validation_rules);
        }

        if ($valid !== true) {
            return false;
        }

        return $data;
	}

	//--------------------------------------------------------------------

	/**
     * Protect attributes by removing them from $row array. Useful for
     * removing id, or submit buttons names if you simply throw your $_POST
     * array at your model. :)
     *
     * @param object/array $row The value pair item to remove.
     */
    public function protect_attributes($row)
    {
        foreach ($this->protected_attributes as $attr)
        {
            if (is_object($row))
            {
                unset($row->$attr);
            }
            else
            {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    //--------------------------------------------------------------------

	/**
	 * A utility function to allow child models to use the type of
	 * date/time format that they prefer. This is primarily used for
	 * setting created_on and modified_on values, but can be used by
	 * inheriting classes.
	 *
	 * The available time formats are:
	 * * 'int'		- Stores the date as an integer timestamp.
	 * * 'datetime'	- Stores the date and time in the SQL datetime format.
	 * * 'date'		- Stores teh date (only) in the SQL date format.
	 *
	 * @param mixed $user_date An optional PHP timestamp to be converted.
	 *
	 * @access protected
	 *
	 * @return int|null|string The current/user time converted to the proper format.
	 */
	protected function set_date($user_date=NULL)
	{
		$curr_date = empty($user_date) ? time() : $user_date;

		switch ($this->date_format)
		{
			case 'int':
				return $curr_date;

			case 'datetime':
				return date('Y-m-d H:i:s', $curr_date);

			case 'date':
				return date( 'Y-m-d', $curr_date);
		}

	}//end set_date()

	//--------------------------------------------------------------------

	/**
     * Return the method name for the current return type
     */
    protected function _return_type($multi = FALSE)
    {
        $method = $multi ? 'result' : 'row';

        // If our type is either 'array' or 'json', we'll simply use the array version
        // of the function, since the database library doesn't support json.
        return $this->temp_return_type == 'array' ? $method . '_array' : $method;
    }

    //--------------------------------------------------------------------

	/**
	 * Allows you to retrieve error messages from the database
	 *
	 * @return string
	 */
	protected function get_db_error_message()
	{
		switch ($this->db->platform())
		{
			case 'cubrid':
				return cubrid_errno($this->db->conn_id);
			case 'mssql':
				return mssql_get_last_message();
			case 'mysql':
				return mysql_error($this->db->conn_id);
			case 'mysqli':
				return mysqli_error($this->db->conn_id);
			case 'oci8':
				// If the error was during connection, no conn_id should be passed
				$error = is_resource($this->db->conn_id) ? oci_error($this->db->conn_id) : oci_error();
				return $error['message'];
			case 'odbc':
				return odbc_errormsg($this->db->conn_id);
			case 'pdo':
				$error_array = $this->db->conn_id->errorInfo();
				return $error_array[2];
			case 'postgre':
				return pg_last_error($this->db->conn_id);
			case 'sqlite':
				return sqlite_error_string(sqlite_last_error($this->db->conn_id));
			case 'sqlsrv':
				$error = array_shift(sqlsrv_errors());
				return !empty($error['message']) ? $error['message'] : null;
			default:
				/*
				 * !WARNING! $this->db->_error_message() is supposed to be private and
				 * possibly won't be available in future versions of CI
				 */
				return $this->db->_error_message();
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Allows you to set the table to use for all methods during runtime.
	 *
	 * @param string $table The table name to use (do not include the prefix!)
	 *
	 * @return void
	 */
	public function set_table($table='')
	{
		$this->table_name = $table;

	}//end set_table()

	//--------------------------------------------------------------------

	/**
	 * Allows you to get the table name
	 *
	 * @return string $this->table_name (current model table name)
	 */
	public function get_table()
	{
		return $this->table_name;

	}//end get_table()

	//--------------------------------------------------------------------

	/**
	 * Allows you to get the table primary key
	 *
	 * @return string $this->key (current model table primary key)
	 */
	public function get_key()
	{
		return $this->key;

	}//end get_key()

	//--------------------------------------------------------------------

    /**
     * Get the name of the created by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_created_by_field()
    {
        if ($this->set_created && $this->log_user) {
            return $this->created_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the created field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_created_field()
    {
        if ($this->set_created) {
            return $this->created_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the deleted field
     *
     * @return String    The name of the field if soft_deletes is enabled, else an empty string
     */
    public function get_deleted_field()
    {
        if ($this->soft_deletes) {
            return $this->deleted_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the deleted by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_deleted_by_field()
    {
        if ($this->soft_deletes && $this->log_user) {
            return $this->deleted_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the metadata for the model's database fields
     *
     * Returns the model's database field metadata stored in $this->field_info
     * if set, else it tries to retrieve the metadata from
     * $this->db->field_data($this->table_name);
     *
     * @todo The MongoDB driver is the only one that doesn't appear to support
     * $this->db->field_data, though it's possible other drivers don't support
     * more extensive metadata (such as type/max_length) supported by MySQL
     *
     * @return array    Returns the database field metadata for this model
     */
    public function get_field_info()
    {
        if (empty($this->field_info)) {
            $this->field_info = $this->db->field_data($this->table_name);
        }

        return $this->field_info;
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the modified by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_modified_by_field()
    {
        if ($this->set_modified && $this->log_user) {
            return $this->modified_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the modified field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_modified_field()
    {
        if ($this->set_modified) {
            return $this->modified_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

	/**
	 * Sets the date_format to use for setting created_on and modified_on values.
	 *
	 * @param string $format String describing format. Valid values are: 'int', 'datetime', 'date'
	 *
	 * @return bool
	 */
	public function set_date_format($format='int')
	{
		if ($format != 'int' && $format != 'datetime' && $format != 'date')
		{
			return FALSE;
		}

		$this->date_format = $format;

		return TRUE;

	}//end set_date_format()

	//--------------------------------------------------------------------

	/**
	 * Sets whether to auto-create modified_on dates in the update method.
	 *
	 * @param bool $modified
	 *
	 * @return bool
	 */
	public function set_modified($modified=TRUE)
	{
		// micro-optimization note: comparison to TRUE and FALSE is faster
		// than is_bool(), because it's a function call
		// === FALSE || === TRUE is faster than !== TRUE && !== FALSE
		// because === TRUE will only be compared for values other than FALSE
		if ($modified === FALSE || $modified === TRUE)
		{
			$this->set_modified = $modified;

			return TRUE;
		}

		return FALSE;

	}//end set_modified()

	//--------------------------------------------------------------------

	/**
	 * Sets whether soft deletes are used by the delete method.
	 *
	 * @deprecated This method is deprecated as of version 0.7.
	 *
	 * @param bool $soft
	 *
	 * @return bool
	 */
	public function set_soft_deletes($soft=TRUE)
	{
		if ($modified === FALSE || $modified === TRUE)
		{
			$this->soft_deletes = $soft;

			return TRUE;
		}

		return FALSE;

	}//end set_soft_deletes()

	//--------------------------------------------------------------------

	/**
	 * Logs an error to the Console (if loaded) and to the log files.
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	protected function logit($message='', $level='debug')
	{
		if (empty($message))
		{
			return FALSE;
		}

		if (class_exists('Console'))
		{
			Console::log($message);
		}

		log_message($level, $message);

	}//end logit()

	//--------------------------------------------------------------------

    /**
     * Extracts the model's fields (except the key and those handled by
     * Observers) from the $post_data and returns an array of name => value pairs
     *
     * @param Array $post_data The post data, usually $this->input->post() when called from the controller
     *
     * @return Array    An array of name => value pairs containing the data for the model's fields
     */
    public function prep_data($post_data)
    {
        $data = array();
        $skippedFields = array();
        $skippedFields[] = $this->get_created_field();
        $skippedFields[] = $this->get_created_by_field();
        $skippedFields[] = $this->get_deleted_field();
        $skippedFields[] = $this->get_deleted_by_field();
        $skippedFields[] = $this->get_modified_field();
        $skippedFields[] = $this->get_modified_by_field();

        // Though the model doesn't support multiple keys well, $this->key
        // could be an array or a string...
        $skippedFields = array_merge($skippedFields, (array)$this->key);

        // If the field is the primary key, one of the created/modified/deleted
        // fields, or has not been set in the $post_data, skip it
        foreach ($this->get_field_info() as $field) {
            if (isset($field->primary_key) && $field->primary_key
                || in_array($field->name, $skippedFields)
                || ! isset($post_data[$field->name])
               ) {
                continue;
            }

            $data[$field->name] = $post_data[$field->name];
        }

        return $data;
    }

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
    // CI Database  Wrappers
    //--------------------------------------------------------------------
    // To allow for more expressive syntax, we provide wrapper functions
    // for most of the query builder methods here.
    //
    // This allows for calls such as:
    //      $result = $this->model->select('...')
    //                            ->where('...')
    //                            ->having('...')
    //                            ->get();
    //

    public function select ($select = '*', $escape = NULL) { $this->db->select($select, $escape); return $this; }
    public function select_max ($select = '', $alias = '') { $this->db->select_max($select, $alias); return $this; }
    public function select_min ($select = '', $alias = '') { $this->db->select_min($select, $alias); return $this; }
    public function select_avg ($select = '', $alias = '') { $this->db->select_avg($select, $alias); return $this; }
    public function select_sum ($select = '', $alias = '') { $this->db->select_sum($select, $alias); return $this; }
    public function distinct ($val=TRUE) { $this->db->distinct($val); return $this; }
    public function from ($from) { $this->db->from($from); return $this; }
    public function join($table, $cond, $type = '') { $this->db->join($table, $cond, $type); return $this; }
    //public function where($key, $value = NULL, $escape = TRUE) { $this->db->where($key, $value, $escape); return $this; }
    public function or_where($key, $value = NULL, $escape = TRUE) { $this->db->or_where($key, $value, $escape); return $this; }
    public function where_in($key = NULL, $values = NULL) { $this->db->where_in($key, $values); return $this; }
    public function or_where_in($key = NULL, $values = NULL) { $this->db->or_where_in($key, $values); return $this; }
    public function where_not_in($key = NULL, $values = NULL) { $this->db->where_not_in($key, $values); return $this; }
    public function or_where_not_in($key = NULL, $values = NULL) { $this->db->or_where_not_in($key, $values); return $this; }
    public function like($field, $match = '', $side = 'both') { $this->db->like($field, $match, $side); return $this; }
    public function not_like($field, $match = '', $side = 'both') { $this->db->not_like($field, $match, $side); return $this; }
    public function or_like($field, $match = '', $side = 'both') { $this->db->or_like($field, $match, $side); return $this; }
    public function or_not_like($field, $match = '', $side = 'both') { $this->db->or_not_like($field, $match, $side); return $this; }
    public function group_by($by) { $this->db->group_by($by); return $this; }
    public function having($key, $value = '', $escape = TRUE) { $this->db->having($key, $value, $escape); return $this; }
    public function or_having($key, $value = '', $escape = TRUE) { $this->db->or_having($key, $value, $escape); return $this; }
    public function limit($value, $offset = '') { $this->db->limit($value, $offset); return $this; }
    public function offset($offset) { $this->db->offset($offset); return $this; }
    public function set($key, $value = '', $escape = TRUE) { $this->db->set($key, $value, $escape); return $this; }



//--------------------------------------------------------------------

public function id_company($field)
	{
		$query = $this->db->select($field)
			->get('u9_company');

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->$field;
			} 
			

		return FALSE;
		
		
	}//end vnumber()
	
public function options($opt_name)
	{
		$wheres=array('field_name'=>$opt_name);
		$query = $this->db->select('field_value')
			->where($wheres)
			->get('u9_options');

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->field_value;
			} 
			

		return FALSE;
		
		
	}//end vnumber()

public function sc($amounts,$flg)
	{
		$sc=$this->options('second_currency');
		$sc_a=$this->options('SC Allowed');
		$sc_r=(float)$this->options('second_currency_rate');
		$amount=$sc_a=='active' && $flg=='sc' ? (float)$amounts * $sc_r : $amounts;
		return $amount;
	}//end vnumber()	
			/**
	 * Collect Acchead .
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	public function create_vnumber($tdate)
	{	
		if (empty($tdate))
		{
			return FALSE;
		}
		$ts = strtotime(date('F Y', strtotime($tdate)));
		$ld= date('t', $ts);
		$ldate=date('Y-m', strtotime($tdate)).'-'.$ld;
		$fdate=date('Y-m', strtotime($tdate)).'-01';
		//echo "tdate between '$fdate' and '$ldate'";
		
		$query = $this->db->select_max('vnumber','vnumber')
			->where("tdate between '$fdate' and '$ldate'")
			->get($this->table_name);

		
			if ($query && $query->num_rows() > 0)
		{	
			foreach($query->result() as $val){
				return $val->vnumber+1;
				}
			} else {
				return 1;
				}
			

		return FALSE;
		
		
	}//end vnumber()
public function create_vnumbers($tdate)
	{	
		if (empty($tdate))
		{
			return FALSE;
		}
		$ts = strtotime(date('F Y', strtotime($tdate)));
		$ld= date('t', $ts);
		$ldate=date('Y-m', strtotime($tdate)).'-'.$ld;
		$fdate=date('Y-m', strtotime($tdate)).'-01';

		$query = $this->db->select_max('invoice','invoice')
			->where("tdate between '$fdate' and '$ldate'")
			->get('u9_inventory');

		
			if ($query && $query->num_rows() > 0)
		{	
			foreach($query->result() as $val){
				return $val->invoice+1;
				}
			} else {
				return 1;
				}
			

		return FALSE;
		
		
	}//end vnumber()
public function create_regis()
	{	

		$query = $this->db->select_max('rnumber','rnumber')
			->get($this->table_name);
			
			if ($query && $query->num_rows() > 0)
		{	
			foreach($query->result() as $val){ 
				if ($val->rnumber):
					return $val->rnumber+1;
				else:
					return 1000;
				endif;
				}
			} else {
				return 1000;
				}
			

		return FALSE;
		
		
	}//end vnumber()

//--------------------------------------------------------------------

	/**
	 * Collect Acchead .
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	public function sums($id,$tdate)
	{
		if (empty($id))
		{
			return FALSE;
		}
		$wheres=array('flag'=>0,'vnumber'=>$id, 'tdate'=>date('Y-m-d',strtotime($tdate)));

		$query = $this->db->select_sum('amount','amount')
			->where($wheres)
			->get($this->table_name);


			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->amount; echo $query->row()->amount;
			} 
			

		return FALSE;
		
		
	}//end vnumber()
public function note_addmore($id,$tdate)
	{
		if (empty($id))
		{
			return FALSE;
		}
		$wheres=array('flag'=>0,'vnumber'=>$id, 'tdate'=>date('Y-m-d',strtotime($tdate)));

		$query = $this->db->select('id_coa')
			->where($wheres)
			->get($this->table_name);

			if ($query && $query->num_rows() > 0)
		{	$note='';
			foreach($query->result() as $val){
				$note=='' ? $note.= $this->coaid($val->id_coa) : $note.=', '. $this->coaid($val->id_coa);
				}
			}
			

		return $note;
		
		
	}//end vnumber()
public function sum_amount($wheres,$scc)
	{
		$filds=$scc=='scc' ? 'sc_amount' : 'amount';
		$query = $this->db->select_sum($filds,'amount')
			->where($wheres)
			->get($this->table_name);

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->amount;
			} 
			

		return FALSE;
		
		
	}//end vnumber()

public function student_collection($wheres)
	{
		$query = $this->db->select_sum('amount','amount')
			->where($wheres)
			->get($this->table_name);

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->amount;
			} 
			

		return FALSE;
		
		
	}//end vnumber()
//--------------------------------------------------------------------

	/**
	 * Collect Acchead .
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	public function minusa($amt, $ttype)
	{
		if (empty($ttype))
		{
			return FALSE;
		}
		
		if ($ttype=='ce') {return $amt; return TRUE;}
		else if ($ttype=='cr'){return -$amt;  return TRUE;}
		else if ($ttype=='be'){return $amt;  return TRUE;}
		else if ($ttype=='br'){return -$amt;  return TRUE;}
		else if ($ttype=='pe'){return $amt;  return TRUE;}
		else if ($ttype=='pr'){return -$amt;  return TRUE;}
		else if ($ttype=='jr'){return $amt;  return TRUE;}
		else if ($ttype=='xl'){return $amt;  return TRUE;}
	
		
	}//end vnumber()

	public function minusb($amt, $ttype)
	{
		if (empty($ttype))
		{
			return FALSE;
		}
		
		if ($ttype=='ce') {return -$amt; return TRUE;}
		else if ($ttype=='cr'){return -$amt;  return TRUE;}
		else if ($ttype=='be'){return -$amt;  return TRUE;}
		else if ($ttype=='br'){return -$amt;  return TRUE;}
		else if ($ttype=='pe'){return -$amt;  return TRUE;}
		else if ($ttype=='pr'){return -$amt;  return TRUE; /* becoz sum is minus*/}
		else if ($ttype=='jr'){return -$amt;  return TRUE;}
		else if ($ttype=='xl'){return $amt;  return TRUE;} else { return false;}
		
	}//end vnumber()


//--------------------------------------------------------------------

	/**
	 * Collect Acchead .
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	public function coawitha($ttype)
	{
		if (empty($ttype))
		{
			return FALSE;
		}
		
			
		if ($ttype=='ce') {return $this->idcoa('Main Cash'); return TRUE;}
		else if ($ttype=='cr') {return $this->idcoa('Main Cash'); return TRUE;}
		else if ($ttype=='pe'){return $this->idcoa('Petty Cash');  return TRUE;}
		else if ($ttype=='pr'){return $this->idcoa('Petty Cash');  return TRUE;}
		else if ($ttype=='xl'){return 0;  return TRUE;}
	}//end vnumber()
	
	
		public function coawithb($ttype)
	{
		if (empty($ttype))
		{
			return FALSE;
		}
		
		if ($ttype=='ce') {return $this->idcoa('Main Cash'); return TRUE;}
		else if ($ttype=='cr'){return $this->idcoa('Main Cash');  return TRUE;}
		else if ($ttype=='pe'){return $this->idcoa('Petty Cash');  return TRUE;}  
		else if ($ttype=='pr'){return $this->idcoa('Petty Cash');  return TRUE;}  
		else if ($ttype=='xl'){return 0;  return TRUE;}  
	}//end vnumber()


	public function idcoa($acchead)
	{
		if (empty($acchead))
		{
			return FALSE;
		}
		
		$query = $this->db->select('id_coa')
			->where('acchead', $acchead)
			->get('u9_coa');

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->id_coa;
		} else { return false;}
		
	}//end vnumber()
public function id_gmother($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		
		$query = $this->db->select('id_gmother')
			->where('id_coa', $id_coa)
			->get('u9_coa');

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->id_gmother;
		} else { return false;}
		
	}//end vnumber()
	public function coaid($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		
		$query = $this->db->select('acchead')
			->where('id_coa', $id_coa)
			->get('u9_coa');

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->acchead;
		}
		
	}//end vnumber()

	public function subhead_parent($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		$wheres=array('id_coa'=>$id_coa,'atype'=>'Sub Head');
		$query = $this->db->select('id_parent')
			->where($wheres)
			->get('u9_coa');

		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->id_parent;
		}
		
		
	}//end vnumber()
	
	public function field_value($field , $wheres)
	{
		if (empty($field))
		{
			return FALSE;
		}

		$query = $this->db->select($field)
			->where($wheres)
			->get($this->table_name);
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->$field;
		}
		
		
	}//end vnumber()	
	public function is_group($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		$wheres=array('id_coa'=>$id_coa,'atype'=>'Group');
		$query = $this->db->select('id_parent')
			->where($wheres)
			->get('u9_coa');

		if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
		
	}//end vnumber()
	
		
public function set_current_data($c_data,$tdate,$fdate,$type,$category)
	{ 	
		$c_data= $c_data=='' ? 0 : $c_data;
		$tdate= $tdate!='' ? $tdate : '0000-00-00';
		$fdate= $fdate!='' ? $fdate : '0000-00-00';
		
	
			$wheres=array('user_id'=>$this->auth->user_id(),'rtype'=>$type);
			
			$this->db->where($wheres);
			$this->db->delete('u9_current_data');
			
		$fdate = $fdate!='' ? $fdate : '0000-00-00'; 
		$type=='vn'  ? $field ='vnumber' : $field ='id_coa';
		$type=='bgt'  ? $field = 'period' : '';
		$data=array($field=>$c_data,'tdate'=>$tdate, 'fdate'=>$fdate, 'user_id'=>$this->auth->user_id(),'rtype'=>$type,'category'=>$category); //display_name

		$status = $this->db->insert('u9_current_data', $data);

		if ($status == false) {
			$this->error = $this->get_db_error_message();
        } elseif ($this->return_insert_id) {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
			return $status;
        }
		
	}//end vnumber()
	
public function set_defauld_coa()
	{ 	
		
		$balance_sheet=$this->idcoa('Balance Sheet');
		if ($balance_sheet==false){
			$data=array('acchead'=>'Balance Sheet','id_parent'=>0, 'atype'=>'Group', 'SL'=>1,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);

		$profit_and_loss=$this->idcoa('Profit and Loss');
		if ($profit_and_loss==false){
			$data=array('acchead'=>'Profit and Loss','id_parent'=>0, 'atype'=>'Group', 'SL'=>2,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		$fixed_asset=$this->idcoa('Fixed Asset');
		if ($fixed_asset==false){
			$data=array('acchead'=>'Fixed Asset','id_parent'=>1, 'atype'=>'Group', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$fixed_asset,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		$current_asset=$this->idcoa('Current Asset');
		if ($current_asset==false){
			$data=array('acchead'=>'Current Asset','id_parent'=>1, 'atype'=>'Group', 'SL'=>2,'id_gmother'=>1,'id_gson'=>$current_asset,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$advance_deposit_prepayment=$this->idcoa('Advance Deposit and Prepayment');
		if ($advance_deposit_prepayment==false){
			$id_current_asset=$this->idcoa('Current Asset');
			$data=array('acchead'=>'Advance Deposit and Prepayment','id_parent'=>$id_current_asset, 'atype'=>'Group', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$id_current_asset,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}		
		$advance_salary=$this->idcoa('Advance Salary');
		if ($advance_deposit_prepayment==false){
			$id_advance_deposit_prepayment=$this->idcoa('Advance Deposit and Prepayment');
			$data=array('acchead'=>'Advance Salary','id_parent'=>$id_advance_deposit_prepayment, 'atype'=>'Account Head', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$id_current_asset,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}	
		$employee_loan=$this->idcoa('Employee Loan');
		if ($advance_deposit_prepayment==false){
			$id_advance_deposit_prepayment=$this->idcoa('Advance Deposit and Prepayment');
			$data=array('acchead'=>'Employee Loan','id_parent'=>$id_advance_deposit_prepayment, 'atype'=>'Account Head', 'SL'=>2,'id_gmother'=>1,'id_gson'=>$id_current_asset,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}	
				
		$cash_and_bank=$this->idcoa('Cash and Bank');
		if ($cash_and_bank==false){
			$data=array('acchead'=>'Cash and Bank','id_parent'=>1, 'atype'=>'Group', 'SL'=>3,'id_gmother'=>1,'id_gson'=>$cash_and_bank,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		$capital=$this->idcoa('Capital');
		if ($balance_sheet==false){
			$data=array('acchead'=>'Capital','id_parent'=>1, 'atype'=>'Group', 'SL'=>4,'id_gmother'=>1,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$current_liabilities=$this->idcoa('Current Liabilities');
		if ($current_liabilities==false){
			$data=array('acchead'=>'Current Liabilities','id_parent'=>1, 'atype'=>'Group', 'SL'=>5,'id_gmother'=>1,'id_gson'=>$current_liabilities,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$paid_up_capital=$this->idcoa('Paid up capital');
		if ($paid_up_capital==false){
			$id_capital=$this->idcoa('Capital');
			$data=array('acchead'=>'Paid up capital','id_parent'=>$id_capital, 'atype'=>'Account Head', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$id_capital,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$cash_account=$this->idcoa('Cash Account');
		if ($cash_account==false){
			$id_cash_and_bank=$this->idcoa('Cash and Bank');
			$data=array('acchead'=>'Cash Account','id_parent'=>$id_cash_and_bank, 'atype'=>'Group', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$id_cash_and_bank,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		$cash_account=$this->idcoa('Bank Account');
		if ($cash_account==false){
			$id_cash_and_bank=$this->idcoa('Cash and Bank');
			$data=array('acchead'=>'Bank Account','id_parent'=>$id_cash_and_bank, 'atype'=>'Group', 'SL'=>2,'id_gmother'=>1,'id_gson'=>$id_cash_and_bank,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}

		$main_cash=$this->idcoa('Main Cash');
		if ($main_cash==false){
			$id_cash_account=$this->idcoa('Cash Account');
			$data=array('acchead'=>'Main Cash','id_parent'=>$id_cash_account, 'atype'=>'Account Head', 'SL'=>1,'id_gmother'=>1,'id_gson'=>$id_cash_and_bank,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}

		$petty_cash=$this->idcoa('Petty Cash');
		if ($cash_account==false){
			$id_cash_account=$this->idcoa('Cash Account');
			$data=array('acchead'=>'Petty Cash','id_parent'=>$id_cash_account, 'atype'=>'Account Head', 'SL'=>2,'id_gmother'=>1,'id_gson'=>$id_cash_and_bank,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}

		$profitlos=$this->idcoa('Profit/loss');
		if ($cash_account==false){
			$id_current_liabilities=$this->idcoa('Current Liabilities');
			$data=array('acchead'=>'Profit/loss','id_parent'=>$id_current_liabilities, 'atype'=>'Group', 'SL'=>1,'id_gmother'=>2,'id_gson'=>$id_current_liabilities,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$income=$this->idcoa('Income');
		if ($income==false){
			$id_profit_loss=$this->idcoa('Profit and Loss');
			$data=array('acchead'=>'Income','id_parent'=>$id_profit_loss, 'atype'=>'Group', 'SL'=>1,'id_gmother'=>2,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}

		$expense=$this->idcoa('Expense');
		if ($expense==false){
			$id_profit_loss=$this->idcoa('Profit and Loss');
			$data=array('acchead'=>'Expense','id_parent'=>$id_profit_loss, 'atype'=>'Group', 'SL'=>2,'id_gmother'=>2,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$sales=$this->idcoa('Sales');
		if ($sales==false){
			$id_income=$this->idcoa('Income');
			$data=array('acchead'=>'Sales','id_parent'=>$id_income, 'atype'=>'Account Head', 'SL'=>1,'id_gmother'=>2,'id_gson'=>$id_income,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
		
		$sales=$this->idcoa('Cost of Sales');
		if ($sales==false){
			$id_income=$this->idcoa('Income');
			$data=array('acchead'=>'Cost of Sales','id_parent'=>$id_income, 'atype'=>'Account Head', 'SL'=>2,'id_gmother'=>2,'id_gson'=>$id_income,'user_id'=>$this->auth->user_id(),'idate'=>date('Y-m-d')); //display_name
			$status = $this->db->insert('u9_coa', $data);
		}
	}
		
	}//end vnumber()
	
	public function current_data_view($tpe)
	{
		$wheres=array('rtype'=>$tpe,'user_id'=>$this->auth->user_id());
		
		$query = $this->db->where($wheres)->get('u9_current_data');
		
           if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->vnumber.','.$query->row()->fdate.','.$query->row()->tdate.','.$query->row()->id_coa.','.$query->row()->rtype.','.$query->row()->category.','.$query->row()->period;
			 return true;
		} else { return false; }

	}//end vnumber()
	
	
	
		public function flag_delete($vn,$tdate)
	{
		if (empty($vn))
		{
			return FALSE;
		}
		
		$wheres=array('vnumber'=>$vn, 'tdate'=>$tdate,'flag'=>1);
		$query = $this->db->select('id_tran')
			->where($wheres)
			->get($this->table_name);

	
           if ($query && $query->num_rows() > 0)
		{	
			$wheres=array('id_tran'=>$query->row()->id_tran);
			$this->db->where($wheres);
			$this->db->delete($this->table_name);
		}
		
	}//end vnumber()
	
public function transaction_check($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		$wheres=array('id_coa'=>$id_coa, 'id_subhead'=>$id_coa);
		$query = $this->db->select('id_tran')
			->or_where($wheres)
			->get($this->table_name);

	
           if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
	}//end vnumber()
public function admission_agent_check($id_agent)
	{
		if (empty($id_agent))
		{
			return FALSE;
		}
		$wheres=array('id_agent'=>$id_agent);
		$query = $this->db->select('id_agent')
			->or_where($wheres)
			->get('u9_admission');

	
           if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
	}//end vnumber()
public function admission_trade_check($id_trade)
	{
		if (empty($id_trade))
		{
			return FALSE;
		}
		$wheres=array('id_trade'=>$id_trade);
		$query = $this->db->select('id_trade')
			->or_where($wheres)
			->get('u9_admission');

	
           if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
	}//end vnumber()
public function child_check($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		
		$wheres=array('id_parent'=>$id_coa);
		$query = $this->db->select('id_coa')
			->where($wheres)
			->get('u9_coa');
	
	
           if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
	}//end vnumber()
	
public function acchead_check($id_coa)
	{
		if (empty($id_coa))
		{
			return FALSE;
		}
		
		$wheres=array('id_coa'=>$id_coa,'atype'=>'Account Head');
		$query = $this->db->select('id_coa')
			->where($wheres)
			->get('u9_coa');
	
	
           if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else { return false;}
		
	}//end vnumber()
	
			
	public function dropdown()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('id_parent' => '16');
        $result = $this->db->select(array($key, $value))
						   ->not_like('atype','Group')
						   ->order_by($value)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }


public function dropdown_inv()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('id_parent' => '16');
        $result = $this->db->select(array($key, $value))
						   ->not_like('ptype','Group')
						   ->order_by($value)
                           ->get('product')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }	
public function dropdown_warehouse()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->order_by($value)
                           ->get('warehouse')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }	
public function dropdown_coa()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('id_parent' => '16');
        $result = $this->db->select(array($key, $value))
						   ->not_like('atype','Sub Head')
						   ->order_by($value)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_training()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		$id_parent=$this->idcoa('Student Collection');
        $result = $this->db->select(array($key, $value))
						   ->not_like('atype','Group')
						   ->not_like('id_parent',$id_parent)
						   ->order_by($value)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
		
		public function dropdown_group()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('atype' => 'Group');
		
        $result = $this->db->select(array($key, $value))
						   ->like('atype','Group')
						   ->order_by($value)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_invgroup()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('atype' => 'Group');
		
        $result = $this->db->select(array($key, $value))
						   ->like('ptype','Group')
						   ->order_by($value)
                           ->get('u9_product')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
	public function dropdown_bank()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

    	$wheres=array('atype' => 'Account Head','atype' => 'Sub Head');
		
		$id_coa_bank=$this->idcoa('Bank Account');
		
        $result = $this->db->select(array($key, $value))
						   ->where('id_parent',$id_coa_bank)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }

public function dropdown_collection()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));
	
		$id_collection=$this->idcoa('Center Training');
		$id_collection=$this->idcoa('Reg and IP');
		
        $result = $this->db->select(array($key, $value))
						   ->where('id_parent',$id_collection)
						   ->where('id_parent',$id_collection)
                           ->get('u9_coa')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }	

public function dropdown_student()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));
	
		
        $result = $this->db->select(array($key, $value))
						   ->where('deleted',0)
						   ->order_by($value)
                           ->get('u9_admission')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }	
public function dropdown_employee()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));
	
		
        $result = $this->db->select(array($key, $value))
						   ->where('deleted',0)
                           ->get('u9_employee')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }		
public function dropdown_trade()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

        $result = $this->db->select(array($key, $value))
                           ->get('u9_trade')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }

public function dropdown_agent()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

        $result = $this->db->select(array($key, $value))
							->order_by('name','ASC')
                           ->get('u9_agent')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
			
public function dropdown_project()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->not_like('id_parent','0')
						   ->order_by($value)
                           ->get('u9_project')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }

public function dropdown_budget()
    {
        $result = $this->db->select('fyear')
                           ->get('u9_budget')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->fyear] = $row->fyear;
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_product()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->not_like('ptype','Group')
						   ->order_by($value)
                           ->get('u9_product')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_mill()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->order_by($value)
                           ->get('u9_mill')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_banks()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->order_by($value)
                           ->get('u9_bank')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_cnf()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->order_by($value)
                           ->get('u9_cnf')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function dropdown_parent()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($key, $value))
						   ->order_by($value)
                           ->get('product')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }
public function sca($field_name)
    {
        $result = $this->db->select('field_value')
                           ->where(array('field_name'=>$field_name))
						   ->get('u9_options')
                           ->result();

        foreach ($result as $row)
        {
           return $row->field_value;
        }

    }	
public function trial_balance($tdate,$id_coa,$scc)
    {
		list ($period_1,$period_2)=preg_split('[=]',$this->fyear($tdate));
		$period_1=$period_1<$tdate ? $period_1 : $tdate;

 		$fields= $scc=='scc' ? 'u9_transaction.id_coa, sum(u9_transaction.sc_amount) as amount' : 'u9_transaction.id_coa, sum(u9_transaction.amount) as amount';
		$id_gmother=$this->id_gmother($id_coa);

		$id_gmother==2 ? 
			$wheres="u9_transaction.tdate between '$period_1' and '$tdate' and u9_transaction.id_coa=".$id_coa :
			$wheres="u9_transaction.tdate <= '$tdate' and u9_transaction.id_coa=".$id_coa;

		$this->db->join('u9_coa', 'u9_transaction.id_coa = u9_coa.id_coa', 'inner');
		$query = $this->db->select($fields)
						   ->where($wheres)
						   ->group_by('id_coa')
                           ->get($this->table_name);
						   
	if ($query && $query->num_rows() > 0)
		{	
			return $query->result();
		} 
    }
public function rcvd_payment($tdate,$id_coa,$scc)
    {
		list ($period_1,$period_2)=preg_split('[=]',$this->fyear($tdate));
		$period_1=$period_1<$tdate ? $period_1 : $tdate;

 		$fields= $scc=='scc' ? 'u9_transaction.id_coa, sum(u9_transaction.sc_amount) as amount' : 'u9_transaction.id_coa, sum(u9_transaction.amount) as amount';
		$id_gmother=$this->id_gmother($id_coa);

		$id_gmother==2 ? 
			$wheres="u9_transaction.tdate between '$period_1' and '$tdate' and u9_transaction.id_coa=".$id_coa ." and u9_transaction.ttype in ('cr','ce')" :
			$wheres="u9_transaction.tdate <= '$tdate' and u9_transaction.id_coa=".$id_coa ." and u9_transaction.ttype in ('cr','ce')";

		$this->db->join('u9_coa', 'u9_transaction.id_coa = u9_coa.id_coa', 'inner');
		$query = $this->db->select($fields)
						   ->where($wheres)
						   ->group_by('id_coa')
                           ->get($this->table_name);
						   
	if ($query && $query->num_rows() > 0)
		{	
			return $query->result();
		} 
    }
public function ptrial_balance($tdate,$id_coa,$scc,$p)
    {	
 		$fields= $scc=='scc' ? 'id_coa, sum(sc_amount) as amount' : 'id_coa, sum(amount) as amount';
        $query = $this->db->select($fields)
						   ->where("tdate <='$tdate' and id_coa=".$id_coa." and id_project=".$p)
						   ->group_by('id_coa')
                           ->get($this->table_name);
						   
	if ($query && $query->num_rows() > 0)
		{	
			return $query->result();
		} 
    }
public function atype($tdate,$d, $id_name,$category)
	{
		$adate=date('Y-m', strtotime($tdate)).'-'.$d;

		$wheres=array('adate'=>$adate,'id_name'=>$id_name,'category'=>$category);
		$query = $this->db->select('atype')
			->where($wheres)
			->get($this->table_name);

		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->atype;
		}
		
		
	}//end vnumber()	
		
public function auto_delete_transaction($type)
    {
 
        $query = $this->db->select('vnumber,tdate, sum(amount) as amount')
						   ->having("sum(amount)<>0")
						   ->group_by('vnumber,tdate')
                           ->get($this->table_name);

	if ($query && $query->num_rows() > 0)
		{	
			
			foreach($query->result() as $val){
				$data['deleted']=1;
				$nv=array('vnumber'=>$val->vnumber,'tdate'=>$val->tdate);
				$type!='xl' ? $this->db->delete($this->table_name,$nv) : '';

				}
		} 
    }	
public function earn_position($test_name, $tdate, $id_trade, $status)
    {
 
 		$wheres=array('id_trade'=>$id_trade,'tdate'=>$tdate,'status'=>$status,'test_name'=>$test_name);
        $query = $this->db->select('total_mark, id_trade, status')
						   ->where($wheres)
						   ->group_by('total_mark')
						   ->order_by('total_mark','DESC')
                           ->get('u9_testresult');

	if ($query && $query->num_rows() > 0)
		{	
			$i=0;
			foreach($query->result() as $val){
				$i++;
				$wheres=array('id_trade'=>$id_trade,'tdate'=>$tdate,'status'=>$status,'test_name'=>$test_name,'total_mark'=>$val->total_mark);
				$this->db->update('u9_testresult', array("position"=>$i), $wheres);
				}
		} 
    }
public function update_field($table, $field, $where)
    {
 		if (empty($table))
		{
			return FALSE;
		}
		if (empty($field))
		{
			return FALSE;
		}
       
	    if ( ! is_array($where))
		{
			$where = array('id_student' => $where);
		}
		$reuslt=$this->db->update($table, $field, $where);

	
    }	
public function sc_insert($tdate,$rate)
    {
 		if (empty($tdate))
		{
			return FALSE;
		}
       if (empty($rate))
		{
			return FALSE;
		}
		$tdate=date('Y-m-d',strtotime($tdate));
		$data = array('tdate' =>$tdate,'rate'=>$rate,'id_user'=>$this->auth->user_id(), 'idate'=>date('Y-m-d'));
		$status = $this->db->insert('u9_currency_rate', $data);

	
    }		
public function sc_insert_check($tdate)
    {
 		if (empty($tdate))
		{
			return FALSE;
		}
		
		//---
		$tdate=date('Y-m-d',strtotime($tdate));
		$query = $this->db->select('rate')
			->where(array('tdate'=>$tdate))
			->get('u9_currency_rate');
		if ($query && $query->num_rows() > 0)
		{	
			return true;
		} else {return false; }
		
		//=======
    }	
public function sc_rate($tdate)
    {
 		if (empty($tdate))
		{
			return FALSE;
		}
		
		//---
		
		$tdate=date('Y-m-d',strtotime($tdate));
		$query = $this->db->select('rate')
			->where(array('tdate'=>$tdate))
			->get('u9_currency_rate');
		
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->rate;
		} 
		//=======
    }	
public function sc_rate_suggest($tdate)
    {
 		if (empty($tdate))
		{
			return FALSE;
		}
		
		//---
		$tdate=date('Y-m-d',strtotime($tdate));
		$query = $this->db->select('rate')
			->where("tdate < '$tdate' ")
			->limit(1)
			->get('u9_currency_rate');
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->rate;
		} 
		
		//=======
    }					
public function control_main_menu($permission,$action)
    {	
 		if (empty($permission))
		{
			return FALSE;
		}
  			$query = $this->db->select('status,permission_id')
						   ->where(array('name'=>$permission))
                           ->get('u9_permissions');
		if ($query && $query->num_rows() > 0)
		{	
			$status=$query->row()->status;
			$permission_id=$query->row()->permission_id;
		}
	    if ($status=='active' && $action=='inactive')
		{	
			$where = array('name'=>$permission);
			$reuslt=$this->db->update('u9_permissions', array('status'=>'inactive'), $where);
			
			$where = array('permission_id'=>(int)$permission_id,'role_id'=>1);
			$this->role_permissions_has($permission_id)==true ?
				$reuslt=$this->db->delete('u9_role_permissions',$where) : '';
			echo $this->role_permissions_has($permission_id);

		} else if(($status=='inactive' && $action=='active')) { 
			$where = array('name'=>$permission);
			$reuslt=$this->db->update('u9_permissions', array('status'=>'active'), $where);

			$data=array('role_id'=>1,'permission_id'=>$permission_id);
			$this->role_permissions_has($permission_id)==false ?
				$this->db->insert('u9_role_permissions',$data) 
				:'';}
	
    }
public function role_permissions_has($permission_id)

    {
		if (empty($permission_id))
		{
			return FALSE;
		}
  			$query = $this->db->select('permission_id')
						   ->where(array('permission_id'=>$permission_id, 'role_id'=>1))
                           ->get('u9_role_permissions');
		if ($query && $query->num_rows()> 0)
		{	
			return true; 
		}
		else {return false;}
	}
public function employee_attendeance($id,$epass)

    {
		if (empty($id))
		{
			return FALSE;
		}
		if (empty($epass))
		{
			return FALSE;
		}
		  			$query = $this->db->select('roll')
						   ->where(array('roll'=>$id, 'password'=>$epass))
                           ->get('u9_employee');
		if ($query && $query->num_rows()> 0)
		{	
			return true; 
		}
		else {return false;}
	}		
public function developer_permission($permission)
    {
 		if (empty($permission))
		{
			return FALSE;
		}
  			$query = $this->db->select('field_value')
						   ->where(array('field_name'=>$permission))
                           ->get('u9_options');
		if ($query && $query->num_rows() > 0)
		{	
			$status=$query->row()->field_value;
		}
		
			   
	    if ($status=='active')
		{	
			return true;
		}
		else { return false;}
	
    }	
public function employee_id()
    {
 		
  			$query = $this->db->select_max('roll','roll')
                           ->get($this->table_name);
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->roll+1;
		}
    }	
public function student_id()
    {
 		
  			$query = $this->db->select_max('roll','roll')
                           ->get($this->table_name);
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->roll+1;
		}
    }
public function fyear($tdate)
    {
 		
  			$query = $this->db->select('field_value')
							->where(array('field_name'=>'Fiscal Year'))
                           ->get('options');
		if ($query && $query->num_rows() > 0)
		{	
			$fyear=$query->row()->field_value;
			list ($period_1,$period_2)=preg_split('[-]',$fyear);
			 $period_1=$period_1.' '. date('Y',strtotime($tdate));
			 $period_1=date('Y-m-',strtotime($period_1)).'01';
			 $period_2=$period_2.' '. date('Y',strtotime($tdate));
			 $period_2=date('Y-m-',strtotime($period_2)).'31';
			 return $period_1.'='.$period_2;
		} else { return false;}
    }
public function tb_previos_profit($tdate)
    {
		//echo $tdate;
		list ($period_1,$period_2)=preg_split('[=]',$this->fyear($tdate));
		$period_1=$period_1<$tdate ? $period_1 : $tdate;

			$this->db->join('u9_coa', 'u9_transaction.id_coa = u9_coa.id_coa', 'inner');
 			$query = $this->db->select_sum('amount','amount')
							->where("tdate < '$period_1' and u9_coa.id_gmother=2")
                           ->get($this->table_name);
						   
		if ($query && $query->num_rows() > 0)
		{	
			return $query->row()->amount;
		}
		else {return 0;}
    }
public function project_type()
    {

		
        $result = $this->db->select('field_value')
						   ->like('field_name','project_type')
                           ->get('u9_options')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->field_value] = $row->field_value;
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }		
public function shipment_check($fld,$id)

    {
		if (empty($fld))
		{
			return FALSE;
		}
		if (empty($id))
		{
			return FALSE;
		}
		  			$query = $this->db->select($fld)
						   ->where(array($fld=>$id))
                           ->get('u9_shipment');
		if ($query && $query->num_rows()> 0)
		{	
			return 'disabled'; 
		}
		else {return '';}
	}	
public function buyer_check($fld,$id)

    {
		if (empty($fld))
		{
			return FALSE;
		}
		if (empty($id))
		{
			return FALSE;
		}
		  			$query = $this->db->select($fld)
						   ->where(array($fld=>$id))
                           ->get('u9_lcinfo');
		if ($query && $query->num_rows()> 0)
		{	
			return 'disabled'; 
		}
		else {return '';}
	}
public function lc_check($fld,$id)

    {
		if (empty($fld))
		{
			return FALSE;
		}
		if (empty($id))
		{
			return FALSE;
		}
		  			$query = $this->db->select($fld)
						   ->where(array($fld=>$id))
                           ->get('u9_lcdetails');
		if ($query && $query->num_rows()> 0)
		{	
			return 'disabled'; 
		}
		else {return '';}
	}
public function product_child($id)

    {
	
		if (empty($id))
		{
			return FALSE;
		}
		  			$query = $this->db->select('id_product')
						   ->where(array('id_parent'=>$id))
                           ->get('u9_product');
		if ($query && $query->num_rows()> 0)
		{	
			return 'disabled'; 
		}
		else {return '';}
	}

public function inventory_check($fld,$id)

    {
		if (empty($fld))
		{
			return FALSE;
		}
		if (empty($id))
		{
			return FALSE;
		}
		  			$query = $this->db->select($fld)
						   ->where(array($fld=>$id))
                           ->get('u9_inventory');
						   //echo $query->num_rows();
		if ($query && $query->num_rows()> 0)
		{	
			return 'disabled'; 
		}
		else {return '';}
	}
	
public function bgt_period($period)
    {
 		if (empty($period))
		{
			return FALSE;
		}

  		if (strlen($period)==4 && is_numeric($period))
		{
			$range=$period.'-01-01'.'='.$period.'-12-31' ;
			return $range;
			}
		elseif (strlen($period)==8 && strpos($period,'-')>0)
		{	$range='';
			$fdate=date('Y-m', strtotime($period)).'-01';
			$ts = strtotime(date('F Y', strtotime($period)));
			$ld= date('t', $ts);
			$tdate=date('Y-m', strtotime($period)).'-'.$ld;
			return $range.=$fdate.'='.$tdate;
			}
	elseif (strlen($period)==17 && strpos($period,'-')>0)
		{	
		list ($period_1,$period_2)=preg_split('[-]',$period);
		
			$range='';
			$fdate=date('Y-m', strtotime($period_1)).'-01';
			
			$ts = strtotime(date('F Y', strtotime($period_2)));
			$ld= date('t', $ts);
			$tdate=date('Y-m', strtotime($period_2)).'-'.$ld;
			return $range.=$fdate.'='.$tdate;
			}		
		
    }	
public function set_tax_name($tax_name)
	{ 	
		$data=array('tax_name'=>$tax_name); //display_name
		$status = $this->db->insert('u9_tax_name', $data);

		if ($status == false) {
			$this->error = $this->get_db_error_message();
        } elseif ($this->return_insert_id) {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
			return $status;
        }
		
	}
public function delete_tax_name($tax_name)
	{ 	
		$data=array('tax_name'=>$tax_name); //display_name
		$status = $this->db->delete('u9_tax_name', $data);

		if ($status == false) {
			$this->error = $this->get_db_error_message();
        } elseif ($this->return_insert_id) {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
			return $status;
        }
		
	}
public function dropdown_tax()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

		
        $result = $this->db->select(array($value, $value))
						  ->order_by($value)
                           ->get('tax_name')
                           ->result();

        $options = array();
		$options[0]='--------';
        foreach ($result as $row)
        {
            $options[$row->{$value}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }	
	
	
public function bs_profit($flag,$tdate)
    {
 		$sum=0;
        $query = $this->db->select('id_coa, acchead')
						   ->where("id_parent",$this->idcoa('Profit and Loss'))
                           ->get('u9_coa');
						   $result=$query->result();
	if (is_array($result))
	{
		foreach($result as $val){
			
			$acchead_has=$this->acchead_check($val->id_coa);
			//echo $acchead_has;
			if ($acchead_has==true){
				$wheres=array("id_coa"=>$val->id_coa);
				$sum+=$this->sum_amount($wheres,$flag);
				//echo $val->acchead.'-'.$sum.'<br>';
				} else {
				$child_has=$this->child_check($val->id_coa);
				if ($child_has==true) {
					        $query = $this->db->select('id_coa, acchead')
						   ->where("id_parent",$val->id_coa)
                           ->get('u9_coa');
						   $result=$query->result();
							if (is_array($result))
							{
								foreach($result as $val){
									$acchead_has=$this->acchead_check($val->id_coa);
									if ($acchead_has==true){
										//$wheres=array("id_coa"=>$val->id_coa);
										$wheres="tdate <= '$tdate' and id_coa=".$val->id_coa;
										$sum+=$this->sum_amount($wheres,$flag);
										//echo $val->acchead.'-'.$sum.'<br>';
										} else {
											
											$child_has=$this->child_check($val->id_coa);
													if ($child_has==true) {
													$query = $this->db->select('id_coa, acchead')
												   ->where("id_parent",$val->id_coa)
												   ->get('u9_coa');
												   $result=$query->result();
													if (is_array($result))
													{
														foreach($result as $val){
															$acchead_has=$this->acchead_check($val->id_coa);
															if ($acchead_has==true){
																//$wheres=array("id_coa"=>$val->id_coa);
																$wheres="tdate <= '$tdate' and id_coa=".$val->id_coa;
																$sum+=$this->sum_amount($wheres,$flag);
																//echo $val->acchead.'-'.$sum.'<br>';
																} else {
																	
																	
																	echo "forth";
																	}
															}
														}
													}
											}
									}
								}
					}
				}
			
			
			}
		}
		return !empty($sum) ? $sum : 0 ;

    }
	
public function sum_qty($wheres,$scc)
	{
		$filds=$scc=='scc' ? 'sc_amount' : 'qty';
		$query = $this->db->select_sum($filds,'qty')
			->where($wheres)
			->get($this->table_name);

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->qty;
			} 
			

		return FALSE;
		
		
	}//end vnumber()	

public function set_war($qty, $rate,$id_product,$tdate,$invoice){
	if (empty($qty))
		{
			return FALSE;
		}
	if (empty($rate))
		{
			return FALSE;
		}
			$query=$this->db->select_sum('qty','qty')
			->where(array('id_product'=>$id_product))
			->get($this->table_name);
	
		if ($query && $query->num_rows() > 0)
		{	//echo $query->row()->qty;
				if ($query->row()->qty==0):
				
					$data=array('war'=>$rate,'tdate'=>$tdate,'id_product'=>$id_product,'invoice'=>$invoice);
					$this->db->insert('war', $data);
					else:
				//echo "ok";
					$querys=$this->db->select('war')
							->where(array('id_product'=>$id_product))
							->get('product');

							if ($querys && $querys->num_rows() > 0)
								{
									$wars=$querys->row()->war;  //echo $wars.'-';
									$amount_1 = $query->row()->qty * $wars; //echo $amount_1.'-';

									$amount_2 = $qty * $rate; //echo $amount_2.'-';
									$ttl_amount = $amount_1 + $amount_2; //echo $ttl_amount.'-';
									$ttl_qty=$query->row()->qty + $qty;
									$war=$ttl_amount / $ttl_qty; //echo $war;
									
									$data=array('war'=>$war,'tdate'=>$tdate,'id_product'=>$id_product,'invoice'=>$invoice);
									$this->db->insert('war', $data);
									}
				endif;
				
			} 
	}

public function war($id_product, $tdate){
		$querys=$this->db->select('war')
		->where(array('id_product'=>$id_product))
		->where ("tdate <= '".$tdate."'")
		->order_by('tdate','DESC')
		->limit(1)
		->get('war');

			if ($querys && $querys->num_rows() > 0)
				{
					return $querys->row()->war; //echo $querys->row()->war;
				}
	}

public function sumissue_qty($id_product){
		$wheres=array('id_product'=>$id_product);
		$query = $this->db->select_sum('qty','qty')
			->where($wheres)
			->where("qty < 0 and ttype='Issue'")
			->get('inventory');

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->qty;
			} 
	}
public function sumreturn_qty($id_product){
		$wheres=array('id_product'=>$id_product);
		$query = $this->db->select_sum('qty','qty')
			->where($wheres)
			->where(array('ttype'=>'Return'))
			->get('inventory');

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->qty;
			} 
	}
public function opening_qty($id_product,$tdate){
		$wheres=array('id_product'=>$id_product);
		$query = $this->db->select_sum('qty','qty')
			->where($wheres)
			->where("tdate < '$tdate' ")
			->get('inventory');

	
			if ($query && $query->num_rows() > 0)
		{	
				return $query->row()->qty;
			} 
	}	
public function war_delete($pid){
		$invoice='';$id_product='';
		$query = $this->db->select('id_product')
			->where(array('id_inv'=>$pid))
			->get('inventory');

		
			if ($query && $query->num_rows() > 0)
		{	
				$id_product= $query->row()->id_product;
			} 
		$query = $this->db->select('invoice')
			->where(array('id_inv'=>$pid))
			->get('inventory');

		
			if ($query && $query->num_rows() > 0)
		{	
				$invoice= $query->row()->invoice;
			} 					

		$wheres=array('id_product'=>$id_product,'invoice'=>$invoice);
		$this->db->where($wheres)
		->delete('war');

	}	
		

public function update_war($invoice, $id_product, $tdate,$war)
	{	
		if (empty($invoice))
		{
			return FALSE;
		}
		if (empty($id_product))
		{
			return FALSE;
		}if (empty($tdate))
		{
			return FALSE;
		}		
		$data=array('war'=>$war);
		$where=array('tdate'=>$tdate,'id_product'=>$id_product,'invoice'=>$invoice);
		$result = $this->db->update("u9_war", $data, $where);
		
	}//end vnumber()



			
}//end BF_model

//-------------------------------------------------------------------- 

/**
 * MY_Model
 *
 * This simply extends BF_Model for backwards compatibility,
 * and to provide a placeholder class that your project can customize
 * extend as needed.
 *
 * @package    Bonfire
 * @subpackage MY_Model
 * @category   Models
 * @author     Lonnie Ezell
 * @link       http://cibonfire.com/docs/guides/models.html
 *
 */

class MY_Model extends BF_Model { }

// END: Class MY_model

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
