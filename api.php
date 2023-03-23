<?php

include "config.php";
/*
1)URL_REGISTER
2)URL_LOGIN
3)URL_USERRLIST
4)URL_COMPLIST
5)URL_USERCOMPLIST
6)URL_COMPFILE
7)URL_USERCLIST
8)URL_UPDATECOM
VALIDATE
*/

$response = array();

if (isset($_GET['apicall'])) {

// --------------------------------------------1) URL_REGISTER---------------------------------------------------------------------//
	switch($_GET['apicall']){
	case 'register' :

		//echo "Register";

		//http://localhost/com/api.php?apicall=register&username=1&password=1&cpassword=1&mobile=1&address=1&role=1

		//checking the parameters required are available or not 
		if(isTheseParametersAvailable(array('username','email','password','cpassword','mobile','ip','up_date','role'))){
 
		//getting the values 
		$username=filter_var($_POST["username"],FILTER_SANITIZE_STRING);
		$email=filter_var($_POST["email"],FILTER_SANITIZE_STRING);
		$password=filter_var($_POST["password"],FILTER_SANITIZE_STRING);
		$cpassword=filter_var($_POST["cpassword"],FILTER_SANITIZE_STRING);
		$mobile=filter_var($_POST["mobile"],FILTER_SANITIZE_STRING);
		$ip=filter_var($_POST["ip"],FILTER_SANITIZE_STRING);
		$role=filter_var($_POST["role"],FILTER_SANITIZE_STRING);
		$up_date=filter_var($_POST["up_date"],FILTER_SANITIZE_STRING);

 
		//checking if the user is already exist with this username or email
 		//as the email and username should be unique for every user 
 		$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
 		$stmt->bind_param("s", $username);
 		$stmt->execute();
 		$stmt->store_result();
 
 		//if the user already exist in the database 
 			if($stmt->num_rows > 0){
 				$response['message'] = 'User already registered';
 				$stmt->close();
 			}else{	
 
 				//if user is new creating an insert query 
 				$stmt = $conn->prepare("INSERT INTO users (username, password, confirm_pass, email, mobile,  role, ip, up_date,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?,'1')");
 				$stmt->bind_param("ssssssss", $username, $password, $cpassword, $email, $mobile, $role, $ip, $up_date);
 
 				//if the user is successfully added to the database 
 				if($stmt->execute()){
 
 					//fetching the user back 
 					$stmt = $conn->prepare("SELECT id, id, username, mobile, role,status FROM users WHERE username = ?"); 
 					$stmt->bind_param("s",$username);
 					$stmt->execute();
 					$stmt->bind_result($userid, $id, $username, $mobile, $role, $status);
 					$stmt->fetch();
 
 					$user = array(
 						'id'=>$id, 
 						'username'=>$username, 
 						'mobile'=>$mobile,
 						'role'=>$role,
						'status'=>$status
 						);
 
 					$stmt->close();
 
 					//adding the user data in response 
 					$response['error'] = false; 
 					$response['message'] = 'User registered successfully'; 
 					$response['user'] = $user; 
 				}
 			}
 
		}else{
			$response['error'] = true; 
			$response['message'] = 'required parameters are not available'; 
	}

	break; 

// -----------------------------------------------2)URL_LOGIN--------------------------------------------------------------------//


	case 'login':
		// echo "login";

		//http://localhost/com/api.php?apicall=login&username=2&password=1
		
		//for login we need the username and password 
 		if(isTheseParametersAvailable(array('username', 'password'))){
 			//getting values 
 			$username = $_POST['username'];
 			$password = $_POST['password']; 
 			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT id, username, role,mobile, status FROM users WHERE username = ? AND password = ?");
 			$stmt->bind_param("ss",$username, $password);
 			
 			$stmt->execute();
 			
 			$stmt->store_result();
 			
 			//if the user exist with given credentials 
 			if($stmt->num_rows > 0){
 			
 				$stmt->bind_result($id, $username, $role, $mobile, $status);
 				$stmt->fetch();
 			
 				$user = array(
 					'id'=>$id, 
 					'username'=>$username, 
 					'role'=>$role,
 					'mobile'=>$mobile,
 					'status'=>$status
 				);
 			
 				$response['error'] = false; 
 				$response['message'] = 'Login successfull'; 
 				$response['user'] = $user;

 			}else{
 				//if the user not found 
 				$response['error'] = true; 
 				$response['message'] = 'Invalid username or password';
 			}
 		} else{
			$response['error'] = true; 
 			$response['message'] = 'required parameters are not available';
		}

	break; 
		
// ---------------------------------------------3)URL_USERRLIST----------------------------------------------------------------//

	case 'userrlist':
		//echo "userlist";
		
		// http://localhost/com/api.php?apicall=userlist&role=admin

 		if(isTheseParametersAvailable(array('role'))){
 			//getting values 
 			$role = $_POST['role'];
 			//$password = $_POST['password']; 
 			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT id, username, role, status FROM users WHERE role = ?");
 			$stmt->bind_param("s",$role);
 			
 			$stmt->execute();
 			
 			$stmt->store_result();
 			
 			//if the user exist with given credentials 
 			if($stmt->num_rows > 0){

 				$user=[];
				$stmt->bind_result($id, $username, $role, $status);
 				
 				while ($stmt->fetch()) {

					$user[] = array(
						'id'=>$id, 
						'username'=>$username, 
						'role'=>$role,
						'status'=>$status
					);	

					$response['error'] = false; 
					$response['message'] = 'User lister successfull'; 
					$response['user'] = $user;
 				}		
			
 			}else{
 				//if the user not found 
 				$response['error'] = false; 
 				$response['message'] = 'no user found';
 			}
 		} else{
			$response['error'] = true; 
 			$response['message'] = 'required parameters are not available';
		}

	break; 
	
// ---------------------------------------------------4)URL_COMPLIST-------------------------------------------------------------------//


	case 'complist':
		// echo "complist";

			//getting values 
 			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT id,name,complaint,address,mobile,img FROM complaint WHERE status = 0");
 			// $stmt->bind_param("s",$active);
 			
 			$stmt->execute();
 			
 			$stmt->store_result();
 			
 			//if the user exist with given credentials 
 			if($stmt->num_rows > 0){

 				$complist=[];
				$stmt->bind_result($id, $name, $complaint, $address,$mobile,$img );
 				
 				while ($stmt->fetch()) {

					$complist[] = array(
						'id'=>$id, 
						'name'=>$name, 
						'complaint'=>$complaint,
						'address'=>$address,
						'mobile'=>$mobile,
						'img'=>$img
					);	

					$response['error'] = false; 
					$response['message'] = 'complaint list successfull'; 
					$response['complist'] = $complist;
 				}
 			
	}

	break;

// -------------------------------------------------5)URL_USERCOMPLIST-----------------------------------------------------------------------//


case 'usercomplist':
		// echo "complist";

			//getting values 
		if(isTheseParametersAvailable(array('id'))){
		
		$id=filter_var($_POST["id"],FILTER_SANITIZE_STRING);
 			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT name,complaint,address,mobile,img,up_date,commands,com_state FROM complaint WHERE id=?");
 			$stmt->bind_param("s",$id);
 			
 			$stmt->execute();
 			
 			$stmt->store_result();
 			
 			//if the user exist with given credentials 
 			if($stmt->num_rows > 0){

 				$complist=[];
				$stmt->bind_result( $name, $complaint, $address,$mobile,$img,$up_date,$commands,$com_state );
 				
 				while ($stmt->fetch()) {

					$complist[] = array(
						'name'=>$name, 
						'complaint'=>$complaint,
						'address'=>$address,
						'mobile'=>$mobile,
						'up_date'=>$up_date,
						'commands'=>$commands,
						'com_state'=>$com_state,
						'img'=>'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.$img
					);	

					// $response['error'] = false; 
					// $response['message'] = 'complaint list successfull'; 
					$response['usercomplist'] = $complist;
 				}
 			
				}
 			}else{
				$response['error'] = true; 
				$response['message'] = 'required parameters are not available'; 
			}

	break; 	

// ---------------------------------------------------6)URL_COMPFILE-------------------------------------------------------------------//


	case "compfile":
	// echo "compfile";


		//http://localhost/com/api.php?apicall=register&username=1&password=1&cpassword=1&mobile=1&address=1&role=1

		//checking the parameters required are available or not 
		if(isTheseParametersAvailable(array('name','complaint','address','mobile','lat','lng','image_name','image_tag','ip','up_date'))){
 
		//getting the values 
		$name=filter_var($_POST["name"],FILTER_SANITIZE_STRING);
		$address=filter_var($_POST["address"],FILTER_SANITIZE_STRING);
		$mobile=filter_var($_POST["mobile"],FILTER_SANITIZE_STRING);
		$complaint=filter_var($_POST["complaint"],FILTER_SANITIZE_STRING);
		$mobile=filter_var($_POST["mobile"],FILTER_SANITIZE_STRING);
		$lat=filter_var($_POST["lat"],FILTER_SANITIZE_STRING);
		$lng=filter_var($_POST["lng"],FILTER_SANITIZE_STRING);
		$ip=filter_var($_POST["ip"],FILTER_SANITIZE_STRING);
		$up_date=filter_var($_POST["up_date"],FILTER_SANITIZE_STRING);
		$ImageName=filter_var($_POST["image_name"],FILTER_SANITIZE_STRING);
		$ImageData = filter_var($_POST['image_tag'],FILTER_SANITIZE_STRING);

		$ImagePath = "upload/$ImageName.jpg";
 
 
 				//if user is new creating an insert query 
 				$stmt = $conn->prepare("INSERT INTO complaint(name, complaint, address, mobile,  lat, lng, img ,ip,up_date,commands,com_state,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,'null','new','0')");
 				$stmt->bind_param("sssssssss", $name, $complaint, $address, $mobile,  $lat, $lng, $ImagePath, $ip, $up_date);
 
 				//if the user is successfully added to the database 
 				if($stmt->execute()){
 					
 					file_put_contents($ImagePath,base64_decode($ImageData));

 					//fetching the user back 
 					$stmt = $conn->prepare("SELECT id,name,complaint,address,mobile,img FROM complaint WHERE complaint = ?"); 
 					$stmt->bind_param("s",$complaint);
 					$stmt->execute();
 					$stmt->bind_result($id, $name, $complaint, $address, $mobile, $ImagePath);
 					$stmt->fetch();
 				
 					$complist[] = array(
						'id'=>$id, 
						'name'=>$name, 
						'complaint'=>$complaint,
						'address'=>$address,
						'mobile'=>$mobile,
						'ImagePath'=>$ImagePath
					);	
 
 					$stmt->close();
 
 					//adding the user data in response 
 					$response['error'] = false; 
 					$response['message'] = 'complaint registered successfully'; 
 					$response['complist'] = $complist; 
 				}
 			
 
		}else{
			$response['error'] = true; 
			$response['message'] = 'required parameters are not available'; 
	}

	break;
		
// ---------------------------------------------------7)URL_USERCLIST--------------------------------------------------------------------//


	case 'userclist':
		// echo "complist";
		//checking the parameters required are available or not 
		if(isTheseParametersAvailable(array('name'))){

			//getting values 
		    $name=filter_var($_POST["name"],FILTER_SANITIZE_STRING);
 			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT id,name,complaint,address,mobile,img FROM complaint WHERE name = ? and status = 0");
 			$stmt->bind_param("s",$name);
 			
 			$stmt->execute();
 			
 			$stmt->store_result();
 			
 			//if the user exist with given credentials 
 			if($stmt->num_rows > 0){

 				$complist=[];
				$stmt->bind_result($id, $name, $complaint, $address,$mobile,$img );
 				
 				while ($stmt->fetch()) {

					$complist[] = array(
						'id'=>$id, 
						'name'=>$name, 
						'complaint'=>$complaint,
						'address'=>$address,
						'mobile'=>$mobile,
						'img'=>'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.$img
					);	

					$response['error'] = false; 
					$response['message'] = 'complaint list successfull'; 
					$response['complist'] = $complist;
 				}
 			
	}

	}else{
			$response['error'] = true; 
			$response['message'] = 'required parameters are not available'; 
	}
	break; 	


// ---------------------------------------------------8)URL_UPDATECOM--------------------------------------------------------------------//
	case 'updatecom':
		//echo "updatecom";


		if(isTheseParametersAvailable(array('commands','com_state'))){

			//getting values 
		    $commands=filter_var($_POST["commands"],FILTER_SANITIZE_STRING);
		    $com_state=filter_var($_POST["com_state"],FILTER_SANITIZE_STRING);
		    $id=filter_var($_POST["id"],FILTER_SANITIZE_STRING);


		    	//if user is new creating an insert query 
 				
 				$stmt = $conn->prepare("update complaint set commands = ? , com_state = ? where id = ?");
 				$stmt->bind_param("sss", $username, $com_state,$id);
 
 				//if the user is successfully added to the database 
 				if($stmt->execute()){

					$response['error'] = false; 
					$response['message'] = 'complaint update successfull'; 

 				 } else{
					$response['error'] = false; 
					$response['message'] = 'complaint update failed'; 

 				 }
 			
	}else{
			$response['error'] = true; 
			$response['message'] = 'required parameters are not available'; 
	}
	break; 	

// ---------------------------------------------------END OF FUNCTION--------------------------------------------------------------------//


	default : 
		$response['error'] = true; 
		$response['message'] = 'Invalid Operation Called';
		
	}
}else{
	//if it is not api call 
	//pushing appropriate values to response array 
	$response['error'] = true; 
	$response['message'] = 'Invalid API Call';
	
}

//displaying the response in json structure 
echo header('Content-type: application/json');
// header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

// ---------------------------------------------------DATA VALIDATION--------------------------------------------------------------------//

 //function validating all the paramters are available
 //we will pass the required parameters to this function 
 function isTheseParametersAvailable($params){
 
 	//traversing through all the parameters 
 	foreach($params as $param){
 		//if the paramter is not available
 		if(!isset($_POST[$param])){
 			// echo "Missing ".$param;
 			return false; 
 		}
 	}	
 	//return true if every param is available 
 	return true; 
}

?>