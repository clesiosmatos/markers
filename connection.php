<?php 


function connection()
{
  $conn = new PDO('mysql:host=localhost;dbname=markers;charset=utf8', 'root', 'Mas27368400');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $conn;
}

  function create($data = [])
  {

    $stmt = connection()->prepare(
      "INSERT INTO places SET 
      name = :name, 
      address = :address, 
      lat = :lat, 
      lng = :lng, 
      type = :type"
    );
    $stmt->execute($data);
    header('Location: index.php');

  }

  function read($id)
  {
    $stmt = connection()->prepare('SELECT * FROM places WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
  }

  function readAll()
  {
    $stmt = connection()->prepare("SELECT * FROM places");
    $stmt->execute();
    foreach($stmt->fetchAll() as $value){
      $value_[] = $value;
    }

    return json_encode($value_);
  }

  function update($id, $data = [])
  {

    $stmt = connection()->prepare(
     "UPDATE places SET 
     name = :name, 
     address = :address, 
     lat = :lat, 
     lng = :lng, 
     type = :type 
     WHERE id = $id"
    );
    $stmt->execute($data);
    header('Location: index.php');

  }

  function delete($id)
  {
    $stmt = connection()->prepare('DELETE FROM places WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    header('Location: index.php');
  }