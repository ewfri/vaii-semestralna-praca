<?php
include 'head-foot/header.php';
?>
<div id="formular"  class="form-box">
    <form name="form-check">
        <div class="form-group">
            <label for="exampleFormControlInput3">Message:</label>
            <textarea class="form-control" id="exampleFormControlTextarea3" rows="3" name="reply-content"></textarea>
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

            var empt = document.forms["form-check"]["reply-content"].value;
            if (empt == "")
            {
                alert("Názov nesmie byť prázdny!");
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'forum/post_edit.php?id=<?php echo $_GET['id'];?>',
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
