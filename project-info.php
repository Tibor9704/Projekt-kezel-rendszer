<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=pms', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['delete_project'])) {
    $action_id = $_GET['project_id'];

    $sql = "DELETE FROM project_info WHERE project_id = :id";
    $sent_po = "project-info.php";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $action_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: $sent_po");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

if (isset($_POST['add_project_post'])) {
    $project_title = $_POST['project_title'];
    $project_description = $_POST['project_description'];
    $status = $_POST['status'];
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];

    $sql = "INSERT INTO project_info (p_title, p_description, status, contact_name, contact_email) VALUES (:title, :description, :status, :name, :email)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $project_title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $project_description, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':name', $contact_name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $contact_email, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: $sent_po");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

$page_name = "project_Info";
include("include/sidebar.php");

$projects_per_page = 10;

$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($current_page - 1) * $projects_per_page;
$sql = "SELECT * FROM project_info ORDER BY project_id ASC LIMIT :offset, :limit";

$stmt = $db->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $projects_per_page, PDO::PARAM_INT);
$stmt->execute();

$total_projects = $db->query("SELECT COUNT(*) FROM project_info")->fetchColumn();

$total_pages = ceil($total_projects / $projects_per_page);


?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog add-category-modal">
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center">Új projekt létrehozása</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form role="form" action="" method="post" autocomplete="off">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="control-label col-sm-5">Projekt név</label>
                  <div class="col-sm-7">
                    <input type="text" placeholder="Projekt név" id="project_title" name="project_title" list="expense" class="form-control" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-5">Projekt leírása</label>
                  <div class="col-sm-7">
                    <textarea name="project_description" id="project_description" placeholder="Projekt leírása" class="form-control" rows="5" cols="5"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-5">Státusz</label>
                  <div class="col-sm-7">
                      <select class="form-control" name="status" id="status">
                          <option value="0">Fejlesztésre vár</option>
                          <option value="1">Folyamatban</option>
                          <option value="2">Kész</option>
                      </select>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-5">Kapcsolattartó neve</label>
                  <div class="col-sm-7">
                    <input type="text" placeholder="Kapcsolattartó neve" id="contact_name" name="contact_name" list="expense" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-5">Kapcsolattartó email címe</label>
                  <div class="col-sm-7">
                    <input type="email" placeholder="Kapcsolattartó email címe" id="contact_email" name="contact_email" list="expense" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-3">
                    <button type="submit" name="add_project_post" class="btn btn-success-custom">Létrehozás</button>
                  </div>
                  <div class="col-sm-3">
                    <button type="button" class="btn btn-danger-custom" data-dismiss="modal">Mégse</button>
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

<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <div class="gap"></div>
      <div class="row">
        <div class="col-md-8">
          <div class="btn-group">
            <div class="btn-group">
              <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal">Új projekt létrehozása</button>
            </div>
          </div>
        </div>
      </div>
      <center><h3>Projektek</h3></center>
      <div class="gap"></div>
      <div class="gap"></div>
      <div class="table-responsive">
        <table class="table table-condensed table-custom">
          <thead>
            <tr>
              <th>Projekt azonosító</th>
              <th>Név</th>
              <th>Leírás</th>
              <th>Státusz</th>
              <th>Kapcsolattartó neve</th>
              <th>Kapcsolattartó email címe</th>
              <th>Műveletek</th>
            </tr>
          </thead>
          <tbody>
          <?php
            if ($stmt) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
              <td><?php echo $row['project_id']; ?></td> 
              <td><?php echo $row['p_title']; ?></td>
              <td><?php echo $row['p_description']; ?></td>
              <td>
                <?php  if($row['status'] == 1){
                    echo "Folyamatban <span style='color:#d4ab3a;' class='glyphicon glyphicon-refresh'></span>";
                }elseif($row['status'] == 2){
                    echo "Kész <span style='color:#00af16;' class='glyphicon glyphicon-ok'></span>";
                }else{
                    echo "Fejlesztésre vár <span style='color:#d00909;' class='glyphicon glyphicon-remove'></span>";
                } ?>
              </td>
              <td><?php echo $row['contact_name']; ?></td>
              <td><?php echo $row['contact_email']; ?></td>
              <td>
                <a title="Módosítás"  href="edit-project.php?project_id=<?php echo $row['project_id'];?>"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                <a title="Megtekintés" href="project-details.php?project_id=<?php echo $row['project_id']; ?>"><span class="glyphicon glyphicon-folder-open"></span></a>&nbsp;&nbsp;
                  <a title="Törlés" href="?delete_project=delete_project&project_id=<?php echo $row['project_id']; ?>" onclick="return check_delete();"><span class="glyphicon glyphicon-trash"></span></a>
              </td>
            </tr>
            <?php }
             } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<ul class="pagination">
  <?php
  for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == $current_page) {
          echo '<li class="active"><a href="#">' . $i . '</a></li>';
      } else {
          echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
      }
  }
  ?>
</ul>

<?php
include("include/footer.php");
?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
