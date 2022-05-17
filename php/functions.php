<?php
include_once "cors.php";
include_once "db_connection2.php";

function getAllContent()
{
    $db = get_db_connection_or_die();
	$sentencia = $db->query("SELECT id, year, title, sinopsis, platform, director, leading_cast, genre, content_type, image, rating FROM tContent");
    return $sentencia->fetchAll();
}
function getAllUsers()
{
    $db = get_db_connection_or_die();
	$sentencia = $db->query("SELECT id, name, surname, email,description,is_admin,profile_image FROM tUser");
    return $sentencia->fetchAll();
}

function getFavsUser($id)
{
    $db = get_db_connection_or_die();
	$sentencia = $db->prepare("SELECT content_id FROM tFavorites where user_id = ?");
    $sentencia->execute([$id]);

    return $sentencia->fetchAll();
}

function registerUser($user)
{
    $password=password_hash($user->password, PASSWORD_BCRYPT);
    $bd = get_db_connection_or_die();
    $sentencia = $bd->prepare("INSERT INTO tUser (name, surname, email,encrypted_password,description,profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    return $sentencia->execute([$user->name, $user->surname,$user->email,$password,$user->description,""]);
}
function delFav($user_id,$content_id)
{
    $bd = get_db_connection_or_die();
    $sentencia = $bd->prepare("DELETE FROM tFavorites WHERE user_id = ? AND content_id = ?");
    return $sentencia->execute([$user_id,$content_id]);
}
function newFav($fav)
{
    $bd = get_db_connection_or_die();
    $sentencia = $bd->prepare("INSERT INTO tFavorites (content_id,user_id) VALUES (?, ?)");
    return $sentencia->execute([$fav->content_id, $fav->user_id]);
}

function logUser($user){
    // $password=password_hash($user->password, PASSWORD_BCRYPT);
    $bd = get_db_connection_or_die();
    $sentencia = $bd->prepare("SELECT id,encrypted_password FROM tUser where email = ?");
     $sentencia->execute([$user->email]);
     $result = $sentencia->fetch(PDO::FETCH_BOTH);
     if (password_verify($user->password, $result[1])) {
        session_start();
        $_SESSION['user_id'] = $result[0];
        return $result[0];
        exit;
    } else {
        header("Location: logUser.php?login_failed_password=True");
        exit;
    }


}

    // $sentencia = $bd->prepare("INSERT INTO tUser (name, surname, email,encrypted_password,description,profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    // return $sentencia->execute([$user->name, $user->surname,$user->email,$password,$user->description,""]);


?>