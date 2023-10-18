<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header("Access-Control-Allow-Origin:* ");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");


$db_conn= mysqli_connect("localhost","root", "", "php_tsk");
if($db_conn===false)
{
  die("ERROR: COULD NOT CONNECT TO THIS DB".mysqli_connect_error());
}

$method = $_SERVER['REQUEST_METHOD'];



switch($method)
{
    case 'GET' : 
        $path= explode('/', $_SERVER['REQUEST_URI']);

         // Retrieve posts from the database
         $sql = "SELECT * FROM posts";
         $result = $db_conn->query($sql);
 
         $posts = [];
 
         if ($result->num_rows > 0) {
             while ($row = $result->fetch_assoc()) {
                 $posts[] = $row;
             }
         }
 
         echo json_encode($posts);
         break;
         
    case "POST":
        // Add a new post to the database
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $data["title"];
        $description = $data["description"];

        $sql = "INSERT INTO posts (title, description) VALUES ('$title', '$description')";



        if ($db_conn->query($sql) === TRUE) {
            $createdPost = [
                "title" => $title,
                "description" => $description,
                "createdAt" => date("Y-m-d H:i:s"),
                "updatedAt" => date("Y-m-d H:i:s")
            ];
            echo json_encode(["message" => "Post added successfully.", "CurrentPost" => $createdPost]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $db_conn->error]);
        }
        break;
        case "PUT":
            // Update an existing post in the database
            $data = json_decode(file_get_contents("php://input"), true);
           // Check if the 'id' value is present in the request data
            if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $title = $data["title"];
            $description = $data["description"];
    
            $sql = "UPDATE posts SET title='$title', description='$description', updatedAt=NOW() WHERE id=$id";
    
            if ($db_conn->query($sql) === TRUE) {
                $updatedPost = [
                    "title" => $title,
                    "description" => $description,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "updatedAt" => date("Y-m-d H:i:s")
                ];
                echo json_encode(["message" => "Post updated successfully." , "CurrentPost" => $updatedPost]);
            } else {
                echo json_encode(["error" => "Error updating record: " . $db_conn->error]);
            }
        }
            break;
    
        case "DELETE":
            // Delete a post from the database
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
            
            $id = $_REQUEST['id'];
    
            $sql = "DELETE FROM posts WHERE id=$id";
    
            if ($db_conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Post deleted successfully."]);
            } else {
                echo json_encode(["error" => "Error deleting record: " . $db_conn->error]);
            }
            }
            break;
    
        default:
            echo json_encode(["error" => "Invalid request method"]);
            break;
}
    
    // Close the database connection
    $db_conn->close();


?>