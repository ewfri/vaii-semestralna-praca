<?php
include 'dbconnect.php';
session_start();

if(isset($_SESSION['loggedin']) == false)
{
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //start the transaction
        $query  = "BEGIN WORK;";
        $result = mysqli_query($DB, $query);

        if(!$result)
        {
            //Damn! the query failed, quit
            echo 'An error occured while creating your topic. Please try again later.';
        }
        else
        {

            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
            $sql = "INSERT INTO
                        temy(topic_subject,
                               topic_date,
                               topic_cat,
                               topic_by)
                   VALUES('" . mysqli_real_escape_string($DB, $_POST['topic_subject']) . "',
                               NOW(),
                               " . mysqli_real_escape_string($DB, $_POST['topic_cat']) . ",
                               " . $_SESSION['user_id'] . "
                               )";

            $result = mysqli_query($DB ,$sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your data. Please try again later.' . mysqli_error($DB);
                $sql = "ROLLBACK;";
                $result = mysqli_query($DB, $sql);
            }
            else
            {
                //the first query worked, now start the second, posts query
                //retrieve the id of the freshly created topic for usage in the posts query
                $topicid = mysqli_insert_id($DB);
                $topic_category = mysqli_real_escape_string($DB, $_POST['topic_cat']);

                $sql = "INSERT INTO
                            prispevky(post_content,
                                  post_date,
                                  post_topic,
                                  post_by)
                        VALUES
                            ('" . mysqli_real_escape_string($DB ,$_POST['post_content']) . "',
                                  NOW(),
                                  " . $topicid . ",
                                  " . $_SESSION['user_id'] . "
                            )";
                $result = mysqli_query($DB, $sql);

                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your post. Please try again later.' . mysqli_error($DB);
                    $sql = "ROLLBACK;";
                    $result = mysqli_query($DB, $sql);
                }
                else
                {
                    $sql = "COMMIT;";
                    $result = mysqli_query($DB, $sql);

                    //after a lot of work, the query succeeded!
                    echo 'You have successfully created <a href="category.php?id='. $topic_category . '">your new topic</a>.';
                }
            }
        }
    }
}
?>
