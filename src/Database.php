<?php

class Database
{
    private $pdo;

    public static function create()
    {
        return new static();
    }

    public function __construct()
    {
        // Database Credentials
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;";
        $db_options [PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $db_options);
    }

    public function processRequest($request, $values)
    {
        if (method_exists($this, $request)) {
            return $this->$request($values);
        }
    }

    private function signup($values)
    {
        //Check if username or email address already exists - if anything returns the user is already in the database
        $query = $this->pdo->prepare("SELECT userID FROM users WHERE username = :username OR email = :email");
        $userCheck = [':username' => $values['username'], ':email' => $values['email']];

        $query->execute($userCheck);
        $matches = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($matches)) // 0 = empty
        {
            // Return an error message if a duplicate is found
            // return['error' => 'User already exists.'];
        }

        // Tell user if username unavailable
        $queryUsername = $this->pdo->prepare("SELECT username FROM users WHERE username = :username");
        $usernameCheck = [':username' => $values['username']];
        $queryUsername->execute($usernameCheck);
        $matchesUsername = $queryUsername->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($matchesUsername)) {
            return ['error' => 'Username is taken.'];
        }

        // Tell user if another account has the same email
        $queryEmail = $this->pdo->prepare("SELECT email FROM users WHERE email = :email");
        $emailCheck = [':email' => $values['email']];
        $queryEmail->execute($emailCheck);
        $matchesEmail = $queryEmail->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($matchesEmail)) {
            return ['error' => 'Email is taken. Forgot password?'];
        }

        // $values['role']           should always = 'customer'
        // $values['employeeHours']  should always = 0 (int)
        // $values['billingRate']    should always = "0" (string)
        // If duplicate = false, record input in user table
        $queryInsert = $this->pdo->prepare("INSERT INTO `users` (firstName, lastName, email, address, role, password, username, state, zipcode, city, phone)
	                                        VALUES (:firstName, :lastName ,:email, :address, :role, SHA1(:password), :username, :state, :zipcode, :city, :phone)");

        $insertCheck = [
            ':firstName' => $values['firstName'],
            ':lastName' => $values['lastName'],
            ':email' => $values['email'],
            ':address' => $values['address'],
            ':role' => $values['role'],
            ':password' => $values['password'],
            ':username' => $values['username'],
            ':state' => $values['state'],
            ':zipcode' => $values['zipcode'],
            ':city' => $values['city'],
            ':phone' => $values['phone'],
        ];

        // Return the new user's data
        if ($queryInsert->execute($insertCheck)) {
            $login_info = ['username' => $values['username'], 'password' => $values['password']];
            return $this->login($login_info);
        }
    }

    private function login($values)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND password = SHA1(:password)");
        $query->execute($values);

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return reset($result);
        } else {
            return ['error' => 'Incorrect login information. Please try again.'];
        }
    }

    private function contact($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `contact` (contactName, contactEmail, contactPhone, contactCarmake, contactCarmodel, contactCaryear, contactDescription)
                                                VALUES (:contactName, :contactEmail, :contactPhone, :contactCarmake, :contactCarmodel, :contactCaryear, :contactDescription)");

        $insertCheck = [
            ':contactName' => $values['contactName'],
            ':contactEmail' => $values['contactEmail'],
            ':contactPhone' => $values['contactPhone'],
            ':contactCarmake' => $values['contactCarmake'],
            ':contactCarmodel' => $values['contactCarmodel'],
            ':contactCaryear' => $values['contactCaryear'],
            ':contactDescription' => $values['contactConcerns'],
        ];

        $queryInsert->execute($insertCheck);

        /*
        if ($queryInsert->execute($insertCheck)) {
            //mailing concerns to limelight support email
            // *** make a limelight support email so i dont get spammed ***
            $to = "tsharara@hawkmail.hfcc.edu";
            $subject = $values['name'] . " --- Contact Request";
            $message = $values['concerns'];
            $headers = 'From: ' . $values['email'] . "\r\n" . 'Reply-To: ' . $values['email'] . "\r\n" . 'X-mailer: PHP/' . phpversion();

            if (!mail($to, $subject, $message, $headers)) {
                // to do
            };
        */

        if ($queryInsert->execute($insertCheck)) {
            return array("status" => true);
        } else {
            return array("error" => "insert failed");
        }

    }

    // Gets
    private function getJobInfo($values)
    {
        // Needs to send job information
        $values = array(":userID" => "%" . $values['userID'] . "%", ":jobID" =>  $values['jobID']);
        $query = $this->pdo->prepare("SELECT * FROM job WHERE userID LIKE :userID AND jobID = :jobID ");

        $query->execute($values);

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'No Job Found.'];
        }
    }
    
    private function getCustomer($values)
    {
        if (!empty($values['firstName'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE firstName LIKE :firstName AND role = 'customer' ");
            $values = array(":firstName" => "%" . $values['firstName'] . "%");
        } else if (!empty($values['lastName'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE lastName LIKE :lastName AND role = 'customer' ");
            $values = array(":lastName" => "%" . $values['lastName'] . "%");
        } else if (!empty($values['email'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND role = 'customer' ");
            $values = array(":email" => $values['email']);
        } else if (!empty($values['userID'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE userID = :userID AND role = 'customer' ");
            $values = array(":userID" =>  $values['userID']);
        } else {
            return ['error' => 'Please enter a valid search query.'];
        }

        $query->execute($values);

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'Customer not found.'];
        }
    }

    private function getInvoice($values)
    {
        if (!empty($values['userID'])) {
            $values = array(":userID" => $values['userID']);
            $query = $this->pdo->prepare("SELECT * FROM invoice
                                                    JOIN job ON invoice.jobID = job.jobID
                                                    WHERE job.userID = :userID ORDER BY invoice.invoiceDate DESC;");
        }
        elseif (!empty($values['invoiceID'])) {
            $query = $this->pdo->prepare("SELECT * FROM invoice WHERE invoiceID = :invoiceID;");
            $values = array(":invoiceID" => $values['invoiceID']);
        }
        elseif (!empty($values['jobID'])) {
            $query = $this->pdo->prepare("SELECT * FROM invoice WHERE jobID = :jobID;");
            $values = array(":jobID" => $values['jobID']);
        }

        $query->execute($values);
        if ($invoices = $query->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($invoices as $key => $invoice) {
                 $invoiceValues = array(
                     "invoiceDate" => $invoice["invoiceDate"],
                     "jobID" => $invoice["jobID"],
                 );
                 $details = $this->getJobMonthDetails($invoiceValues);
                 $invoices[$key]['workPerformed'] = $details["workPerformed"];
                 $invoices[$key]['parts'] = $details["parts"];
            }
            return $invoices;
         }
        else {
            return ['error' => 'No Invoices Found.'];
        }
}

    private function getJobMonthDetails($values) {

        $itemValues = array (
            ":firstDay" => (new DateTime($values["invoiceDate"]))
                ->modify("first day of last month")
                ->format("Y-m-d"),
            ":lastDay" => (new DateTime($values["invoiceDate"]))
                ->modify("last day of last month")
                ->format("Y-m-d"),
            ":jobID" =>  $values["jobID"],
        );

        $query = $this->pdo->prepare("SELECT * FROM workPerformed 
                                                        JOIN job ON workPerformed .jobID = job.jobID 
                                                        WHERE job.jobID = :jobID AND workerDate BETWEEN :firstDay AND :lastDay");
        $query->execute($itemValues);
        $details['workPerformed'] = $query->fetchAll(PDO::FETCH_ASSOC);
        $query = $this->pdo->prepare("SELECT * FROM parts
                                                        JOIN job ON parts.jobID = job.jobID
                                                        WHERE job.jobID = :jobID AND parts.partDate BETWEEN :firstDay AND :lastDay");
        $query->execute($itemValues);
        $details['parts'] = $query->fetchAll(PDO::FETCH_ASSOC);
        return $details;

    }

    private function getAllJobs()
    {
        $query = $this->pdo->prepare("SELECT * FROM job");
        $query->execute();

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'No Jobs Found.'];
        }
    }
    
    private function getWorkPerformed($values)
    {
		
		$values = array(":jobID" => $values['jobID']);
		
        $query = $this->pdo->prepare("SELECT * FROM workPerformed WHERE jobID = :jobID");
        $query->execute($values);

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'Work was not performed.'];
        }
    }

    private function getAllCustomers()
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE role = 'customer' OR role='Customer' ");
        $query->execute();

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'No Customers Found.'];
        }
    }

    // Updates
    private function updateJob($values)
    {
        $fields = array();
        $parameter = array();

        $parameter[':jobID'] = $values['jobID'];
        unset($values['jobID']);

        foreach($values as $key => $value)
        {
            $fields[] = "$key = :$key";
            $parameter[":$key"] = $value;
        }
        $sql = "UPDATE job SET ".implode(', ', $fields)." WHERE jobID = :jobID";
        $query = $this ->pdo->prepare($sql);

        if ($query -> execute($parameter)) {
            return array("status" => true);
        } else {
            return ['error' => 'Update job unsuccessful.'];
        }
    }

    private function updateUser($values)
    {
        $fields = array();
        $parameter = array();

        $parameter[':userID'] = $values['userID'];
        unset($values['userID']);

        foreach($values as $key => $value)
        {
            if($key == "password") { $fields[] = "$key = sha1(:$key)"; }
            else { $fields[] = "$key = :$key"; }
            $parameter[":$key"] = $value;
        }
        $sql = "UPDATE users SET ".implode(', ', $fields)." WHERE userID = :userID";
        $query = $this ->pdo->prepare($sql);

        if ($query -> execute($parameter)) {

            return array("status" => true);
        } else {
            return ['error' => 'Update customer unsuccessful.'];
        }
    }

    private function updateInvoice($values)
    {
        $fields = array();
        $parameter = array();

        $parameter[':invoiceID'] = $values['invoiceID'];
        unset($values['invoiceID']);

        foreach($values as $key => $value)
        {
                $fields[] = "$key = :$key";
                $parameter[":$key"] = $value;
        }
        $sql = "UPDATE invoice SET ".implode(', ', $fields)." WHERE invoiceID = :invoiceID";
        $query = $this ->pdo->prepare($sql);

        if ($query -> execute($parameter)) {
            return array("status" => true);
        } else {
            return ['error' => 'Update invoice unsuccessful.'];
        }
    }

    private function createJob($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `job` (userID, carMake, carModel, carYear, totalJobHours, workers)
                                                      VALUES (:userID, :carMake, :carModel, :carYear, :totalJobHours, :workers)");
        $insertCheck = [
            ':userID' => $values['userID'],
            ':carMake' => $values['carMake'],
            ':carModel' => $values['carModel'],
            ':carYear' => $values['carYear'],
            ':totalJobHours' => $values['totalJobHours'],
            ':workers' => $values['workers'],
        ];

        if ($queryInsert->execute($insertCheck)) {
            return array("status" => true);
        } else {
            return array("error" => "Job insert failed");
        }
    }

    private function addPart($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `parts` (partID, jobID, partName,quantity,description)
                                                VALUES (:partID, :jobID, :partName,:quantity,:description)");
        $insertCheck = [
            ':partID' => $values['partID'],
            ':jobID' => $values['jobID'],
            ':partName' => $values['partName'],
            ':quantity' => $values['quantity'],
            ':description' => $values['description']
        ];

        if ($queryInsert->execute($insertCheck)) {
            return array("status" => true);
        } else {
            return array("error" => "Part insert failed");
        }
    }

    private function createInvoice($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `invoice` (jobID,invoiceDate,total)
                                                      VALUES (:jobID,:invoiceDate,:total)");
        $insertCheck = [
            ':jobID' => $values['jobID'],
            ':invoiceDate' => $values['invoiceDate'],
            ':total' => $values['total']
        ];

        if ($queryInsert->execute($insertCheck)) {
            return array("status" => true);
        } else {
            return array("error" => "insert failed");
        }
    }

    //needs jobID
    private function getBalance($values){

        //if invoices are found:
        //loop invoices for total
        //query payments
        //loop payments for total
        //calculate balance

        $paymentTotal= 0;
        $invoiceTotal= 0;

        $query = $this->pdo->prepare("SELECT * FROM `invoice` WHERE invoice.jobID = :jobID;");

        $queryCheck = [
            ':jobID' => $values['jobID'],
        ];

        $query->execute($queryCheck);
        if($invoices = $query->fetchAll()){

            //loop invoices & add totals
            foreach($invoices as $key => $invoice){
                $invoiceTotal += $invoice['total'];
            }

            $query = $this->pdo->prepare("SELECT * FROM `payments` WHERE payments.jobID = :jobID;");

            $query->execute($queryCheck);
            if($payments = $query->fetchAll()){

                //loop payments & add totals
                foreach($payments as $key => $payment){
                    $paymentTotal += $payment['amountPaid'];
                }

                $balance = $invoiceTotal - $paymentTotal;
                return array("balance" => $balance);
            }
            else{
                $balance = $invoiceTotal - $paymentTotal;
                return array("balance" => $balance);
            }

        }
        else{
            return array("error" => "No Invoice Found");
        }

    }

    private function createPayment($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `payments` (invoiceID, jobID, amountPaid, datePaid)
                                                      VALUES (:invoiceID, :jobID, :amountPaid, :datePaid)");
        $insertCheck = [
            ':invoiceID' => $values['invoiceID'],
            ':jobID' => $values['jobID'],
            ':amountPaid' => $values['amountPaid'],
            ':datePaid' => $values['datePaid']
        ];

        if ($queryInsert->execute($insertCheck)) {
            return array("status" => true);
        } else {
            return array("error" => "insert failed");
        }
    }
    
       private function createWorkPerformed($values)
    {
        $queryInsert = $this->pdo->prepare("INSERT INTO `workPerformed` ( userID, jobID, totalHours, lastWorkedDate, workerDate, workDescription)
												  VALUES ( :userID, :jobID, :totalHours, :lastWorkedDate, :workerDate, :workDescription)");
        $insertCheck = [
            ':userID' => $values['userID'],
			':jobID' => $values['jobID'],
            ':totalHours' => $values['totalHours'],
            ':lastWorkedDate' => $values['lastWorkedDate'],
            ':workerDate' => $values['workerDate'],
            ':workDescription' => $values['workDescription'],
        ];

        if ($queryInsert->execute($insertCheck)) {
			
			$updateCheck = [
			':jobID' => $values['jobID'],
            ':totalHours' => $values['totalHours'],
		];
			
			$sql= "UPDATE job SET totalJobHours = totalJobHours + :totalHours WHERE jobID = :jobID";
			$query = $this ->pdo->prepare($sql);
			$query -> execute($updateCheck);
            return array("status" => true);
			
        } else {
            return array("error" => "Work performed insert failed");
        }
    }
private function getEmployee($values)
    {
        if (!empty($values['firstName'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE firstName LIKE :firstName AND role = 'employee' ");
            $values = array(":firstName" => "%" . $values['firstName'] . "%");
        } else if (!empty($values['lastName'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE lastName LIKE :lastName AND role = 'employee' ");
            $values = array(":lastName" => "%" . $values['lastName'] . "%");
        } else if (!empty($values['email'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND role = 'employee' ");
            $values = array(":email" => $values['email']);
        } else if (!empty($values['userID'])) {
            $query = $this->pdo->prepare("SELECT * FROM users WHERE userID = :userID AND role = 'employee' ");
            $values = array(":userID" =>  $values['userID']);
        } else {
            return ['error' => 'Please enter a valid search query.'];
        }

        $query->execute($values);

        if ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        } else {
            return ['error' => 'Employee not found.'];
        }
    }


}