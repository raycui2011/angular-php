<?php

class Contact {
    private $conn;
    private $table_name = 'contacts';
	private $id;
    private $first_name;
    private $last_name;
    private $mobile;
    private $email;
    private $postCode;
	private $modified_at;
	private $created_at;

    function __construct($conn)
    {
		$this->conn = $conn;
		$this->init();
    }
    
    
    public function listAll(){
        $query = "SELECT id, first_name, last_name, mobile, email, post_code, created_at,deleted
                FROM
                    " . $this->table_name . "
		where deleted = 0
                ORDER BY
                    id";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$result = $stmt->fetchAll();
 
        return $result;
    }
    
	
	public function init() {
		date_default_timezone_set("Australia/Melbourne");
	}
	
    public function getFirstName() {
	return $this->first_name;
    }
    
    public function getLastName() {
	return $this->last_name;
    }
    
    public function getEmail() {
	return $this->email;
    }
    
    public function getPostCode() {
	return $this->postCode;
    }
    
    public function getMobile() {
	return $this->mobile;
    }
    
	public function getModifiedAt() {
		return $this->modified_at;
	}
	
	public function getCreatedAt() {
		return $this->created_at;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
    public function setFirstName($first_name) {
		$this->first_name = $first_name;
    }
    
    public function setLastName($last_name) {
		$this->last_name = $last_name;
    }
    
    public function setMobile($mobile) {
		$this->mobile = $mobile;
    }
    
    public function setEmail($email) {
		$this->email = $email;
    }
	
    public function setPostCode($post_code) {
		$this->postCode = $post_code;
    }
    
	public function setCreatedAt() {
		$this->created_at = $created_at;
	}
	
    public function listById($id) {
	$stmt = $this->conn->prepare("select * from contacts where id = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result = $stmt->fetch();
	
	return $result;
    }
	public function listContacts() {
		$this->conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
		$stmt = $this->conn->prepare("select * from contacts where deleted = :deleted");
		$deleted = 0;
		$stmt->bindParam(':deleted', $deleted);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
    public function create($first_name, $last_name, $mobile, $email, $post_code) {
		//$query = 'insert into contacts `first_name`,'
		$stmt = $this->conn->prepare("insert into contacts (first_name, last_name, mobile, email, created_at, modified_at, post_code)"
			. "values (:first_name, :last_name, :mobile, :email, :created_at, :modified_at, :post_code)");
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':mobile', $mobile);
		$stmt->bindParam(':email', $email);
		//$stmt->bindParam(':deleted', 0);
		$stmt->bindParam(':created_at', date('Y-m-d H:i:s'));
		$stmt->bindParam(':modified_at', date('Y-m-d H:i:s'));
		$stmt->bindParam(':post_code', $post_code);

		if ($stmt->execute()) {
			$id = $this->conn->lastInsertId();
			$arr_json = ['id' => $id, 'first_name' => $first_name, 'last_name' => $last_name, 'mobile' => $mobile, 'email' => $email,
			'created_at' => date('Y-m-d H:i:s'), 'modified_at' => date('Y-m-d H:i:s'), 'post_code' => $post_code];
			$json = json_encode($arr_json);
			$stmt = $this->conn->prepare('update contacts set json_text = :json_data where id = :id');
			$stmt->bindParam(':json_data', $json);
			$stmt->bindParam(':id', $id);

		   return $stmt->execute();
		} else {
			return false;
		}
    }
    
    public function addJsonData($id) {
		if ($id) {
		   $stmt = $htis->coon->prepare('select * from contacts where id =:id');

		   $arr_json = ['id' => $id, 'first_name' => $first_name, 'last_name' => $last_name, 'mobile' => $mobile, 'email' => $email,
			'created_at' => date('Y-m-d H:i:s'), 'modified_at' => date('Y-m-d H:i:s'), 'post_code' => $post_code];
		   $json = json_encode($arr_json);
		   $stmt = $this->conn->prepare('update contacts set json_text = :json_data where id = :id');
		   $stmt->bindParam(':json_data', $json);
		   $stmt->bindParam(':id', $id);

		   return $stmt->execute();
		}
    }
    
    
    public function editView($id) {
	$result = $this->listById($id);
	if (count($result) > 0 ) {
	    foreach ($result as $contact) {
		$return =  '<tr>'
		. '<td>' . $contact['id'] . '</td>' 
		. '<td>' . $contact['first_name'] . '</td><td>' .
		  $contact['last_name'] . '</td><td>' . 
		  $contact['mobile'] . '</td><td>' . 
		  $contact['email'];
		$return .= '</td><td>' . $contact['post_code']. '</td>';
		$return .= '<td> <a><span name="delete-'.$contact['id'].'" data-value=" '. $contact['id'] . '"  class="glyphicon glyphicon-remove">delete</span></a>';
		$return .= ' <a href="edit-contact.php?id='.$contact['id'].'"><span name="edit-'.$contact['id'].'" data-value=" '. $contact['id'] . '"  class="glyphicon glyphicon-edit">edit</span></a></td>';
		$return .= '</tr>';
	    }
	} else {
	    $return .= '<tr><td colspan="5"> No Records</td></tr>';
	}
	return $return;
    }
    
    public function updateContact(Contact $contact) {
		
		$now = date('Y-m-d H:i:s');
		$arr_json = ['id' => $contact->getId(), 'first_name' => $contact->getFirstName(), 'last_name' => $contact->getLastName(), 'mobile' => $contact->getMobile(),
			'email' => $email, 'created_at' => $contact->getCreatedAt(), 'modified_at' => $now, 'post_code' => $contact->getPostCode()];
		$sql = 'update contacts set first_name = :first_name , last_name = :last_name, mobile = :mobile, email = :email, post_code = :post_code, '
				. 'modified_at = :modified_at , json_text = :json where id = :id';
		try {
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':first_name', $contact->getFirstName());
			$stmt->bindParam(':last_name', $contact->getLastName());
			$stmt->bindParam(':mobile', $contact->getMobile());
			$stmt->bindParam(':email', $contact->getEmail());
			$stmt->bindParam(':post_code', $contact->getPostCode());
			$stmt->bindParam(':modified_at', $now);
			$stmt->bindParam(':json', json_encode($arr_json));
			$stmt->bindParam(':id', $contact->getId());
			//var_dump($stmt);exit;
			$result = $stmt->execute();
		} catch(Exception $e) {
			$res = ['code' => '400', 'status' => 'failed', 'message' => 'faile to update contact with id ' . $e->getMessage()];
		}
		if ($result) {
			$res = ['code' => '200', 'status' => 'success'];
		}
		
		return $res;
    }
    
    public function softDelete($id) {
		if ($id) {
			$deleted = 1;
			$stmt = $this->conn->prepare("update contacts set deleted = :deleted where id = :id");
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':deleted', $deleted);
			return $stmt->execute();
		} else {
			return false;
		}
    }
    
}
?>
