<?php
include 'forum/dbconnect.php';
include 'head-foot/header.php';

$sql = "SELECT
                    cat_id,
                    cat_name,
                    cat_description
                FROM
                    kategorie";

$result = mysqli_query($DB, $sql);

if (!$result) {
    //the query failed, uh-oh :-(
    echo 'Error while selecting from database. Please try again later.';
} else {
    if (mysqli_num_rows($result) == 0) {
        //there are no categories, so a topic can't be posted
        if ($_SESSION['user_level'] == 1) {
            echo 'You have not created categories yet.';
        } else {
            echo 'Before you can post a topic, you must wait for an admin to create some categories.';
        }
    }
}
?>
<div class="form-box">
    <form name="form-check">
        <div class="form-group">
            <label for="exampleFormControlInput1">Subject:</label>
            <input type="text" name="topic_subject" id="exampleFormControlInput1">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput2">Category:</label>
            <select name="topic_cat" id="exampleFormControlInput2">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
        <option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>
        ';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput3">Message:</label>
            <textarea class="form-control" id="exampleFormControlTextarea3" rows="3" name="post_content"></textarea>
        </div>
        <button id="odosli" type="submit" class="btn btn-primary">Vytvoriť</button>
    </form>
</div>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    $(function () {
        //$("form").validate();

        $('#odosli').click(function (e) {
            // using this page stop being refreshing
            event.preventDefault();

            var empt = document.forms["form-check"]["topic_subject"].value;
            if (empt == "")
            {
                alert("Názov témy nesmie byť prázdny!");
                return false;
            }
            empt = document.forms["form-check"]["post_content"].value;
            if (empt == "")
            {
                alert("Úvodný text k téme nesmie byť prázdny!");
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'forum/topic_add.php',
                data: $('form').serialize(),
                success: function () {
                    alert('form was submitted');
                    //window.location.replace("index.php");
                }
            });
        });
    });
</script>
<?php
include 'head-foot/footer.php';
?>
