<?php

if(isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
} else {
    echo "Invalid project ID.";
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'pms';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$page_name = "Projekt módosítás";
include("include/sidebar.php");

$sql = "SELECT * FROM project_info WHERE project_id = :project_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':project_id', $project_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="well">
                        <h3 class="text-center bg-primary" style="padding: 7px;">Projekt megtekintés</h3><br>
                        
                        <div class="row">
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-single-product">
                                        <tbody>
                                            <tr>
                                                <td>Projekt neve</td>
                                                <td><?php echo $row['p_title']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Leírás</td>
                                                <td><?php echo $row['p_description']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Státusz</td>
                                                <td><?php
                                                    if ($row['status'] == 1) {
                                                        echo "Folyamatban";
                                                    } elseif ($row['status'] == 2) {
                                                        echo "Kész";
                                                    } else {
                                                        echo "Fejlesztésre vár";
                                                    } ?></td>
                                            </tr>
                                            <tr>
                                                <td>Kapcsolattartó</td>
                                                <td><?php echo $row['contact_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Kapcsolattartó emailcíme</td>
                                                <td><?php echo $row['contact_email']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <a title="Update Task" href="project-info.php"><span class="btn btn-success-custom btn-xs">Vissza</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
include("include/footer.php");
?>
