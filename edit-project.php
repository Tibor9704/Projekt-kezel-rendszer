<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=pms', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Adatbázis kapcsolat sikertelen: " . $e->getMessage());
}

if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
} else {
    echo "Érvénytelen projekt azonosító.";
    exit;
}

if (isset($_POST['update_project_info'])) {
    $project_title = $_POST['project_title'];
    $project_description = $_POST['project_description'];
    $status = $_POST['status'];
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];

    $updateSql = "UPDATE project_info SET p_title = :title, p_description = :description, status = :status, contact_name = :c_name, contact_email = :c_email WHERE project_id = :id";

    $stmt = $db->prepare($updateSql);
    $stmt->bindParam(':title', $project_title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $project_description, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':c_name', $contact_name, PDO::PARAM_STR);
    $stmt->bindParam(':c_email', $contact_email, PDO::PARAM_STR);
    $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: project-info.php");
        exit();
    } else {
        echo "Hiba: " . $stmt->errorInfo()[2];
    }
}
if (isset($_POST['update_project_info'])) {
    $project_title = $_POST['project_title'];
    $project_description = $_POST['project_description'];
    $status = $_POST['status'];
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];

    $sql = "SELECT * FROM project_info WHERE project_id = :project_id";
    $info = $db->prepare($sql);
    $info->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $info->execute();
    $oldData = $info->fetch(PDO::FETCH_ASSOC);

    $updateSql = "UPDATE project_info SET p_title = :title, p_description = :description, status = :status, contact_name = :c_name, contact_email = :c_email WHERE project_id = :id";

    $stmt = $db->prepare($updateSql);
    $stmt->bindParam(':title', $project_title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $project_description, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':c_name', $contact_name, PDO::PARAM_STR);
    $stmt->bindParam(':c_email', $contact_email, PDO::PARAM_STR);
    $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: project-info.php");
        exit();
    } else {
        echo "Hiba: " . $stmt->errorInfo()[2];
    }
}
$page_name = "Projekt módosítás";
include("include/sidebar.php");

$sql = "SELECT * FROM project_info WHERE project_id = :project_id";
$info = $db->prepare($sql);
$info->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$info->execute();

if ($info->rowCount() > 0) {
    $row = $info->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Nincs eredmény a megadott projekt azonosítóhoz.";
}

include("include/header.php");
?>

<?php
include("include/footer.php");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="well">
                        <h3 class="text-center bg-primary" style="padding: 7px;">Projekt szerkesztése</h3><br>

                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Projekt név</label>
                                        <div class="col-sm-7">
                                            <input type="text" placeholder="Projekt név" id="project_title" name="project_title" list="expense" class="form-control" value="<?php echo isset($row['p_title']) ? $row['p_title'] : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Projekt leírása</label>
                                        <div class="col-sm-7">
                                            <textarea name="project_description" id="project_description" placeholder="Projekt leírása" class="form-control" rows="5" cols="5"><?php echo isset($row['p_description']) ? $row['p_description'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Státusz</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="status" id="status">
                                                <option value="0" <?php if(isset($row['status']) && $row['status'] == 0){ echo 'selected'; } ?>>Fejlesztésre vár</option>
                                                <option value="1" <?php if(isset($row['status']) && $row['status'] == 1){ echo 'selected'; } ?>>Folyamatban</option>
                                                <option value="2" <?php if(isset($row['status']) && $row['status'] == 2){ echo 'selected'; } ?>>Kész</option>
                                            </select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="control-label col-sm-5">Kapcsolattartó</label>
                                        <div class="col-sm-7">
                                            <input type="text" placeholder="Kapcsolattartó" id="contact_name" name="contact_name" list="expense" class="form-control" value="<?php echo isset($row['contact_name']) ? $row['contact_name'] : ''; ?>" >
                                        </div>
                                    </div>
									<div class="form-group">
    									<label class="control-label col-sm-5">Kapcsolattartó email címe</label>
    									<div class="col-sm-7">
        									<input type="text" placeholder="Kapcsolattartó email címe" id="contact_email" name="contact_email" list="expense" class="form-control" value="<?php echo isset($row['contact_email']) ? $row['contact_email'] : ''; ?>" >
    									</div>
									</div>
									<script>
    									document.getElementById("contact_email").addEventListener("input", function() {
        								var emailInput = this.value;
        								if (!emailInput.includes("@")) {
            								this.setCustomValidity("Az email címnek tartalmaznia kell a '@' karaktert.");
        								} else {
           									 this.setCustomValidity("");
        								}
    									});
									</script>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-3">
                                        </div>

                                        <div class="col-sm-3">
                                            <button type="submit" name="update_project_info" class="btn btn-success-custom">Szerkesztés</button>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <a title="Update Task" href="project-info.php"><span class="btn btn-success-custom btn-xs">Vissza</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
include("include/footer.php");
?>
