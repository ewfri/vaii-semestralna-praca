<?php
//topic.php
include 'forum/dbconnect.php';
include 'head-foot/header.php';

//first select the topic based on $_GET['topic_id']
$sql = "SELECT
        topic_id,
        topic_subject
        FROM
        temy
        WHERE
        temy.topic_id = " . mysqli_real_escape_string($DB, $_GET['id']);

$result = mysqli_query($DB, $sql);
?>
<div class="container-fluid">
    <div class="row comment-section">


        <?php
        if (!$result) {
            echo 'The topic could not be displayed, please try again later.' . mysqli_error($DB);
        } else {
            if (mysqli_num_rows($result) == 0) {
                echo 'This topic does not exist.';
            } else {
                //display category data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<h2>Posts in ' . $row['topic_subject'] . ' topic</h2>';
                }

                //do a query for the posts
                $sql = "SELECT
                prispevky.post_id,
                prispevky.post_topic,
                prispevky.post_content,
                prispevky.post_date,
                prispevky.post_by,
                pouzivatelia.user_id,
                pouzivatelia.user_name
                FROM
                prispevky
                LEFT JOIN
                pouzivatelia
                ON
                prispevky.post_by = pouzivatelia.user_id
                WHERE
                prispevky.post_topic = " . mysqli_real_escape_string($DB, $_GET['id']);

                $result = mysqli_query($DB, $sql);

                if (!$result) {
                    echo 'The topic could not be displayed, please try again later.';
                } else {
                    if (mysqli_num_rows($result) == 0) {
                        echo 'This topic is empty.';
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="comment-post">';
                            echo '<div class="comment-content">';
                            echo $row['post_content'];
                            echo '</div>';
                            echo '<div class="comment-date">';
                            echo "Pridané: " . date('d-m-Y', strtotime($row['post_date']));
                            echo '</div>';
                            echo '<div class="comment-last-edited">';
                            echo "Upravené: " . date('d-m-Y', strtotime($row['post_date']));
                            echo '</div>';
                            echo '<div class="post-by">';
                            echo $row['user_name'];
                            echo '</div>';

                            if (isset($_SESSION) && $row['post_by'] == $_SESSION['user_id']) {
                                echo '<div class="comment-edit">';
                                echo '<button type="button" onclick="window.location.href=\'edit.php?id=' . $row['post_id'] . '\'" class="btn btn-primary">Edit</button>';
                                echo '</div>';
                                echo '<div class="comment-delete">';
                                echo '<button type="button" onclick="window.location.href=\'forum/post_delete.php?id=' . $row['post_id'] . '\'" class="btn btn-danger">Delete</button>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }


                    }
                }
            }
        }
        ?>

        <form class="comment-form" method="post" action="forum/post_add.php?id=<?php echo $_GET['id']; ?>">
            <textarea name="reply-content"></textarea>
            <input type="submit" value="Submit reply"/>
        </form>
    </div>
</div>
<script>
    $(function () {
        //$("form").validate();

        $('#odosli').click(function (e) {
            // using this page stop being refreshing
            event.preventDefault();

            var empt = document.forms["form-check"]["reply-content"].value;
            if (empt == "") {
                alert("Názov nesmie byť prázdny!");
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'forum/post_add.php',
                data: $('form').serialize(),
                success: function () {
                    alert('form was submitted');
                    window.location.replace("index.php");
                }
            });
        });
    });
</script>
<?php
include 'head-foot/footer.php';
?>
