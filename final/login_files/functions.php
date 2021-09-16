  <?php 
    
    function verify_login($db, $username, $password)
    {
      $query = "SELECT user_password FROM users WHERE username = :user";
      $statement = $db->prepare($query);
      $statement->bindValue(':user', $username);
      $statement->execute();
      $result = $statement->fetch();
      $statement->closeCursor();
      $hash = $result['user_password'];
      return password_verify($password, $hash);
    }
    
    function existing_username($db, $username)
    {
      $query = "SELECT COUNT(username) FROM users WHERE username = :username";
      $statement = $db->prepare($query);
      $statement->bindValue(':username', $username);
      $statement->execute();
      $exists = $statement->fetch();
      $statement->closeCursor();
      return $exists[0] == 1;
    }

    function addUser($db, $username, $password) {
      $query = "INSERT INTO users (username, user_password)
                VALUES (:username, :password)";
      $statement = $db->prepare($query);
      $statement->bindValue(':username', $username);
      $statement->bindValue(':password', $password);
      $success = $statement->execute();
      $statement->closeCursor();     
      return $success;
    }
    
     
    function validPassword($password){
      $valid_pattern = '/(?=^.{8,}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$/';
      if (preg_match($valid_pattern, $password))
        return true;
      else
        return false;
    }
    
    function add_new_user_info($db, $user_name, $user_info){
        $query = 'INSERT INTO userinfo (username, firstname, lastname, address, city, stateuser, zip, telephone) VALUES (:username, :firstname, :lastname, :address, :city, :state, :zip, :phone)';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $user_name);
        $statement->bindValue(':firstname', $user_info["first_name"]);
        $statement->bindValue(':lastname', $user_info["last_name"]);
        $statement->bindValue(':address', $user_info["address"]);
        $statement->bindValue(':city', $user_info["city"]);
        $statement->bindValue(':state', $user_info["state"]);
        $statement->bindValue(':zip', $user_info["zip"]);
        $statement->bindValue(':phone', $user_info["phone"]);
        $statement->execute();
        $statement->closeCursor();      
        return false;
    }

?>
