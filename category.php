<?php
//category.php
include 'forum/dbconnect.php';
include 'head-foot/header.php';

//first select the category based on $_GET['cat_id']
$sql = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM
            kategorie
        WHERE
            cat_id = " . mysqli_real_escape_string($DB, $_GET['id']);

$result = mysqli_query($DB, $sql);
?>

<div class="container-fluid">
    <div class="row">
        <div>
            <button class="btn btn-success" onclick="location.href = 'create_topic.php';">create</button>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-light">
                <thead>
                <tr>
                    <th class="bg-dark" style="width: 80.00%">Téma</th>
                    <th class="bg-dark" style="width: 10.00%">Vytvorené</th>
                </tr>
                </thead>
                <?php

if(!$result)
{
    echo 'The category could not be displayed, please try again later.' . mysqli_error($DB);
}
else
{
    if(mysqli_num_rows($result) == 0)
    {
        echo 'This category does not exist.';
    }
    else
    {
        //display category data
        while($row = mysqli_fetch_assoc($result))
        {
            echo '<h2>Topics in ′' . $row['cat_name'] . '′ category</h2>';
        }

        //do a query for the topics
        $sql = "SELECT
                    topic_id,
                    topic_subject,
                    topic_date,
                    topic_cat
                FROM
                    temy
                WHERE
                    topic_cat = " . mysqli_real_escape_string($DB, $_GET['id']);

        $result = mysqli_query($DB, $sql);

        if($result)
        {
            if(mysqli_num_rows($result) == 0)
            {
                echo 'There are no topics in this category yet.';
            }
            else
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    echo '<tr>';
                        echo '<td style="width: 90.00%">';
                            echo '<a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a>';
                        echo '</td>';
                        echo '<td style="width: 10.00%">';
                            echo date('d-m-Y', strtotime($row['topic_date']));
                        echo '</td>';
                    echo '</tr>';
                }
            }
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
