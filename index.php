<?php
include 'forum/dbconnect.php';
include 'head-foot/header.php';

$sql = "SELECT  *
   FROM kategorie";


$result = mysqli_query($DB, $sql);
?>

<div class="container-fluid">
    <div class="row">
        <div>
            <button class="btn btn-success" onclick="location.href = 'create_cat.php';">Vytvoriť kategóriu</button>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-light">
                <thead>
                <tr>
                    <th class="bg-dark" style="width: 16.66%">Kategória</th>
                    <th class="bg-dark" style="width: 78.33%">Popis</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!$result) {
                    echo 'The categories could not be displayed, please try again later.';
                } else {
                    if (mysqli_num_rows($result) == 0) {
                        echo 'No categories defined yet.';
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td style="width: 16.66%">';
                            echo '<a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a>';
                            echo '</td>';
                            echo '<td style="width: 78.33%">';
                            echo $row['cat_description'];
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'head-foot/footer.php';
?>
