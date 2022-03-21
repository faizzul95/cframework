<?php

class User_model extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'user_id';
    protected $uniqueKey = ['user_code', 'user_email', 'user_username'];
    protected $foreignKey = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_code', // <--- if this not in fillable it will not save in db, remove this if want to test
        'user_full_name',
        'user_preferred_name',
        'user_gender',
        'user_email',
        'user_username',
        'user_password',
        'user_avatar',
        'user_status',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $rules = [
        'user_id' => 'numeric',
        'user_code' => 'required|min:1|max:3',
        'user_full_name' => 'required|min:20|max:30',
    ];

    /**
     * Custom message for validation
     *
     * @return array
     */
    protected $messages = [
        'user_code' => 'Code',
        'user_full_name' => 'Full Name'
    ];

    ###################################################################
    #                                                                 #
    #               Start custom function below                       #
    #                                                                 #
    ###################################################################

    public function getlist()
    {
        //  server side datatables
        $cols = array(
            "user_code",
            "user_full_name",
            "user_gender",
            "user_email",
            "user_id",
        );

        // $this->db->join("master_role", "user.role_id=master_role.role_id", "LEFT");
        // $this->db->where('user.user_status == 1'); 
        $result = $this->db->get("" . $this->table . "", null, $cols);

        $this->serversideDt->query($this->getInstanceDB->getLastQuery());

        // $this->serversideDt->hide('created_at'); // hides 'created_at' column from the output

        $this->serversideDt->edit('user_code', function ($data) {
            return '<a href="javascript:void(0)" onclick="viewRecord(' . $data[$this->primaryKey] . ')"> ' . $data['user_code'] . ' </a>';
        });

        $this->serversideDt->edit('user_gender', function ($data) {
            if ($data['user_gender'] == 1) {
                return 'Male';
            } else {
                return 'Female';
            }
        });

        $this->serversideDt->edit('user_id', function ($data) {
            $del = $edit =  '';
            $del = '<button onclick="deleteRecord(' . $data[$this->primaryKey] . ')" data-toggle="confirm" data-id="' . $data[$this->primaryKey] . '" class="btn btn-sm btn-danger" title="Delete"> <i class="fa fa-trash"></i> </button>';
            $edit = '<button class="btn btn-sm btn-info" onclick="updateRecord(' . $data[$this->primaryKey] . ')" title="Edit"><i class="fa fa-edit"></i> </button>';

            return "<center> $del $edit </center>";
        });

        echo $this->serversideDt->generate();
    }
}
